const mysql = require('mysql2/promise');
require('dotenv').config();

const MYSQL_HOST = process.env.MYSQL_HOST || 'localhost';
const MYSQL_PORT = parseInt(process.env.MYSQL_PORT || '3306', 10);
const MYSQL_USER = process.env.MYSQL_USER || 'root';
const MYSQL_PASSWORD = process.env.MYSQL_PASSWORD || '';
const MYSQL_DATABASE = process.env.MYSQL_DATABASE || 'tacheto';

let pool;

function getPool() {
  if (!pool) {
    pool = mysql.createPool({
      host: MYSQL_HOST,
      port: MYSQL_PORT,
      user: MYSQL_USER,
      password: MYSQL_PASSWORD,
      database: MYSQL_DATABASE,
      connectionLimit: 10,
      charset: 'utf8mb4_general_ci',
    });
  }
  return pool;
}

function getDb() {
  const p = getPool();
  return {
    get(sql, params, cb) {
      p.execute(sql, params || [])
        .then(([rows]) => cb(null, rows && rows[0] ? rows[0] : undefined))
        .catch((err) => cb(err));
    },
    all(sql, params, cb) {
      p.execute(sql, params || [])
        .then(([rows]) => cb(null, rows || []))
        .catch((err) => cb(err));
    },
    run(sql, params, cb) {
      p.execute(sql, params || [])
        .then(() => cb && cb())
        .catch((err) => cb ? cb(err) : undefined);
    },
  };
}

module.exports = { getDb, getPool }; 