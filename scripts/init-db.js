const fs = require('fs');
const path = require('path');
const bcrypt = require('bcryptjs');
require('dotenv').config();
const { getDb, databaseFile } = require('../src/db');

const dbDir = path.dirname(databaseFile);
if (!fs.existsSync(dbDir)) {
  fs.mkdirSync(dbDir, { recursive: true });
}

const db = getDb();

const schema = `
PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  email TEXT UNIQUE NOT NULL,
  password_hash TEXT NOT NULL,
  role TEXT NOT NULL CHECK(role IN ('admin','viewer')),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS schools (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  code TEXT UNIQUE NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS results_batches (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  form_level TEXT NOT NULL, -- e.g., Kidato cha Nne, Kidato cha Sita
  year INTEGER NOT NULL,
  title TEXT NOT NULL,
  summary_pdf_path TEXT, -- optional overall results PDF
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS school_results (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  batch_id INTEGER NOT NULL REFERENCES results_batches(id) ON DELETE CASCADE,
  school_id INTEGER NOT NULL REFERENCES schools(id) ON DELETE CASCADE,
  pdf_path TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_batches_level_year ON results_batches(form_level, year);
CREATE INDEX IF NOT EXISTS idx_school_results_batch ON school_results(batch_id);
`;

db.serialize(() => {
  db.exec(schema, async (err) => {
    if (err) {
      console.error('Failed to initialize schema:', err);
      process.exit(1);
    }

    const email = process.env.ADMIN_DEFAULT_EMAIL || 'admin@tacheto.local';
    const password = process.env.ADMIN_DEFAULT_PASSWORD || 'admin123';
    const passwordHash = bcrypt.hashSync(password, 10);

    db.get('SELECT id FROM users WHERE email = ?', [email], (err2, row) => {
      if (err2) {
        console.error('Admin lookup error:', err2);
        process.exit(1);
      }
      if (row) {
        console.log('Admin already exists:', email);
        db.close();
        return;
      }
      db.run(
        'INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)',
        [email, passwordHash, 'admin'],
        (err3) => {
          if (err3) {
            console.error('Failed to create admin user:', err3);
            process.exit(1);
          }
          console.log('Database initialized. Admin:', email, 'Password:', password);
          db.close();
        }
      );
    });
  });
}); 