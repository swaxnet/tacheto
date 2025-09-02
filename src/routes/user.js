const path = require('path');
const express = require('express');
const { getDb } = require('../db');
const { getPaginationParams, buildPageUrls } = require('../utils/pagination');

const router = express.Router();

// Home: list batches by form and year with search/pagination
router.get('/', (req, res) => {
  const db = getDb();
  const { page, pageSize, offset } = getPaginationParams(req.query);
  const search = (req.query.q || '').trim();
  const where = [];
  const params = [];
  if (search) {
    where.push('(form_level LIKE ? OR year LIKE ? OR title LIKE ?)');
    params.push(`%${search}%`, `%${search}%`, `%${search}%`);
  }
  const whereSql = where.length ? 'WHERE ' + where.join(' AND ') : '';

  db.get(`SELECT COUNT(*) as c FROM results_batches ${whereSql}`, params, (err, countRow) => {
    if (err) return res.status(500).send('Tatizo la ndani');
    const total = countRow.c;
    db.all(
      `SELECT * FROM results_batches ${whereSql} ORDER BY year DESC, created_at DESC LIMIT ? OFFSET ?`,
      [...params, pageSize, offset],
      (err2, rows) => {
        if (err2) return res.status(500).send('Tatizo la ndani');
        const pagination = buildPageUrls('/', req.query, total);
        res.render('public/home', { title: 'Matokeo - Tacheto', batches: rows, search, pagination });
      }
    );
  });
});

// View a batch: show overall summary download and per-school list with pagination/search
router.get('/batch/:id', (req, res) => {
  const db = getDb();
  const batchId = req.params.id;
  const { page, pageSize, offset } = getPaginationParams(req.query);
  const search = (req.query.q || '').trim();

  db.get('SELECT * FROM results_batches WHERE id = ?', [batchId], (err, batch) => {
    if (err || !batch) return res.status(404).render('404', { title: 'Haipatikani' });

    const where = ['sr.batch_id = ?'];
    const params = [batchId];
    if (search) {
      where.push('s.name LIKE ?');
      params.push(`%${search}%`);
    }
    const whereSql = 'WHERE ' + where.join(' AND ');

    db.get(
      `SELECT COUNT(*) as c FROM school_results sr JOIN schools s ON s.id = sr.school_id ${whereSql}`,
      params,
      (err2, countRow) => {
        if (err2) return res.status(500).send('Tatizo la ndani');
        const total = countRow.c;
        db.all(
          `SELECT sr.*, s.name as school_name, s.code as school_code
           FROM school_results sr
           JOIN schools s ON s.id = sr.school_id
           ${whereSql}
           ORDER BY s.name ASC
           LIMIT ? OFFSET ?`,
          [...params, pageSize, offset],
          (err3, schoolRows) => {
            if (err3) return res.status(500).send('Tatizo la ndani');
            const pagination = buildPageUrls(`/batch/${batchId}`, req.query, total);
            res.render('public/batch', { title: batch.title, batch, schools: schoolRows, search, pagination });
          }
        );
      }
    );
  });
});

module.exports = router; 