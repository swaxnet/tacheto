const path = require('path');
const sqlite3 = require('sqlite3').verbose();
require('dotenv').config();

const databaseFile = process.env.DB_FILE || path.join(__dirname, '..', '..', 'data', 'tacheto.db');

function getDb() {
  return new sqlite3.Database(databaseFile);
}

module.exports = { getDb, databaseFile }; 