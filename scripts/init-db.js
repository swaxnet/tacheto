const bcrypt = require('bcryptjs');
require('dotenv').config();
const { getPool } = require('../src/db');

(async () => {
  const pool = getPool();
  const conn = await pool.getConnection();
  try {
    await conn.query('CREATE DATABASE IF NOT EXISTS ?? CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci', [process.env.MYSQL_DATABASE]);
    await conn.query('USE ??', [process.env.MYSQL_DATABASE]);

    await conn.query('SET FOREIGN_KEY_CHECKS=1');

    await conn.query(`CREATE TABLE IF NOT EXISTS users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      email VARCHAR(255) UNIQUE NOT NULL,
      password_hash VARCHAR(255) NOT NULL,
      role ENUM('admin','viewer') NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB`);

    await conn.query(`CREATE TABLE IF NOT EXISTS schools (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      code VARCHAR(64) UNIQUE NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB`);

    await conn.query(`CREATE TABLE IF NOT EXISTS results_batches (
      id INT AUTO_INCREMENT PRIMARY KEY,
      form_level VARCHAR(100) NOT NULL,
      year INT NOT NULL,
      title VARCHAR(255) NOT NULL,
      summary_pdf_path VARCHAR(512) NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      INDEX idx_batches_level_year (form_level, year)
    ) ENGINE=InnoDB`);

    await conn.query(`CREATE TABLE IF NOT EXISTS school_results (
      id INT AUTO_INCREMENT PRIMARY KEY,
      batch_id INT NOT NULL,
      school_id INT NOT NULL,
      pdf_path VARCHAR(512) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      INDEX idx_school_results_batch (batch_id),
      CONSTRAINT fk_sr_batch FOREIGN KEY (batch_id) REFERENCES results_batches(id) ON DELETE CASCADE,
      CONSTRAINT fk_sr_school FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE
    ) ENGINE=InnoDB`);

    const email = process.env.ADMIN_DEFAULT_EMAIL || 'admin@tacheto.local';
    const password = process.env.ADMIN_DEFAULT_PASSWORD || 'admin123';
    const passwordHash = bcrypt.hashSync(password, 10);

    const [rows] = await conn.execute('SELECT id FROM users WHERE email = ?', [email]);
    if (!rows || rows.length === 0) {
      await conn.execute('INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)', [email, passwordHash, 'admin']);
      console.log('Database initialized. Admin:', email, 'Password:', password);
    } else {
      console.log('Admin already exists:', email);
    }
  } catch (err) {
    console.error('Failed to initialize schema:', err);
    process.exit(1);
  } finally {
    conn.release();
    pool.end();
  }
})(); 