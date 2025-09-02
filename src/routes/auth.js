const express = require('express');
const bcrypt = require('bcryptjs');
const { getDb } = require('../db');
const { redirectIfAuthenticated } = require('../middleware/auth');

const router = express.Router();

router.get('/login', redirectIfAuthenticated, (req, res) => {
  res.render('auth/login', { title: 'Admin Login', error: null });
});

router.post('/login', (req, res) => {
  const { email, password } = req.body;
  const db = getDb();
  db.get('SELECT * FROM users WHERE email = ?', [email], (err, user) => {
    if (err) {
      console.error(err);
      return res.render('auth/login', { title: 'Admin Login', error: 'Tatizo la ndani. Jaribu tena.' });
    }
    if (!user || !bcrypt.compareSync(password, user.password_hash)) {
      return res.render('auth/login', { title: 'Admin Login', error: 'Email au neno siri sio sahihi.' });
    }
    req.session.user = { id: user.id, email: user.email, role: user.role };
    const redirectTo = req.query.next || '/admin';
    res.redirect(redirectTo);
  });
});

router.post('/logout', (req, res) => {
  req.session.destroy(() => {
    res.redirect('/');
  });
});

module.exports = router; 