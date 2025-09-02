const path = require('path');
const fs = require('fs');
const express = require('express');
const multer = require('multer');
const { getDb } = require('../db');
const { requireAuth } = require('../middleware/auth');

const router = express.Router();

const uploadsDir = path.join(process.cwd(), process.env.UPLOAD_DIR || 'uploads');
const storage = multer.diskStorage({
  destination: (req, file, cb) => cb(null, uploadsDir),
  filename: (req, file, cb) => {
    const unique = Date.now() + '-' + Math.round(Math.random() * 1e9);
    const ext = path.extname(file.originalname).toLowerCase();
    cb(null, unique + ext);
  },
});
const upload = multer({
  storage,
  fileFilter: (req, file, cb) => {
    if (file.mimetype !== 'application/pdf') return cb(new Error('PDF tu inaruhusiwa'));
    cb(null, true);
  },
});

router.use(requireAuth);

router.get('/', (req, res) => {
  const db = getDb();
  db.get('SELECT COUNT(*) as c FROM schools', [], (e1, sCount) => {
    db.get('SELECT COUNT(*) as c FROM results_batches', [], (e2, bCount) => {
      db.get('SELECT COUNT(*) as c FROM school_results', [], (e3, rCount) => {
        res.render('admin/dashboard', {
          title: 'Dashibodi',
          metrics: {
            schools: sCount ? sCount.c : 0,
            batches: bCount ? bCount.c : 0,
            schoolResults: rCount ? rCount.c : 0,
          },
        });
      });
    });
  });
});

// Schools CRUD
router.get('/schools', (req, res) => {
  const db = getDb();
  db.all('SELECT * FROM schools ORDER BY name ASC', [], (err, rows) => {
    res.render('admin/schools', { title: 'Shule', schools: rows || [] });
  });
});

router.post('/schools', (req, res) => {
  const { name, code } = req.body;
  const db = getDb();
  db.run('INSERT INTO schools (name, code) VALUES (?, ?)', [name, code], () => res.redirect('/admin/schools'));
});

router.post('/schools/:id/delete', (req, res) => {
  const db = getDb();
  db.run('DELETE FROM schools WHERE id = ?', [req.params.id], () => res.redirect('/admin/schools'));
});

// Batches CRUD
router.get('/batches', (req, res) => {
  const db = getDb();
  db.all('SELECT * FROM results_batches ORDER BY year DESC, created_at DESC', [], (err, batches) => {
    res.render('admin/batches', { title: 'Makundi ya Matokeo', batches: batches || [] });
  });
});

router.post('/batches', upload.single('summaryPdf'), (req, res) => {
  const { form_level, year, title } = req.body;
  const summaryPath = req.file ? path.join('uploads', path.basename(req.file.path)) : null;
  const db = getDb();
  db.run(
    'INSERT INTO results_batches (form_level, year, title, summary_pdf_path) VALUES (?, ?, ?, ?)',
    [form_level, parseInt(year, 10), title, summaryPath],
    () => res.redirect('/admin/batches')
  );
});

router.post('/batches/:id/delete', (req, res) => {
  const db = getDb();
  db.get('SELECT summary_pdf_path FROM results_batches WHERE id = ?', [req.params.id], (e, row) => {
    if (row && row.summary_pdf_path) {
      try { fs.unlinkSync(path.join(process.cwd(), row.summary_pdf_path)); } catch {}
    }
    db.run('DELETE FROM results_batches WHERE id = ?', [req.params.id], () => res.redirect('/admin/batches'));
  });
});

// School results for a batch
router.get('/batches/:id/schools', (req, res) => {
  const db = getDb();
  const batchId = req.params.id;
  db.get('SELECT * FROM results_batches WHERE id = ?', [batchId], (e, batch) => {
    if (!batch) return res.redirect('/admin/batches');
    db.all('SELECT * FROM schools ORDER BY name ASC', [], (e2, schools) => {
      db.all(
        `SELECT sr.*, s.name as school_name FROM school_results sr JOIN schools s ON s.id = sr.school_id WHERE sr.batch_id = ? ORDER BY s.name ASC`,
        [batchId],
        (e3, rows) => {
          res.render('admin/batch_schools', { title: 'Matokeo ya Shule - ' + batch.title, batch, schools, uploads: rows || [] });
        }
      );
    });
  });
});

router.post('/batches/:id/schools', upload.single('schoolPdf'), (req, res) => {
  const db = getDb();
  const batchId = req.params.id;
  const { school_id } = req.body;
  const pdfPath = req.file ? path.join('uploads', path.basename(req.file.path)) : null;
  if (!pdfPath) return res.redirect(`/admin/batches/${batchId}/schools`);
  db.run(
    'INSERT INTO school_results (batch_id, school_id, pdf_path) VALUES (?, ?, ?)',
    [batchId, school_id, pdfPath],
    () => res.redirect(`/admin/batches/${batchId}/schools`)
  );
});

router.post('/batches/:batchId/schools/:id/delete', (req, res) => {
  const db = getDb();
  const { batchId, id } = req.params;
  db.get('SELECT pdf_path FROM school_results WHERE id = ?', [id], (e, row) => {
    if (row && row.pdf_path) {
      try { fs.unlinkSync(path.join(process.cwd(), row.pdf_path)); } catch {}
    }
    db.run('DELETE FROM school_results WHERE id = ?', [id], () => res.redirect(`/admin/batches/${batchId}/schools`));
  });
});

module.exports = router; 