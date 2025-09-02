const path = require('path');
const fs = require('fs');
const express = require('express');
const session = require('express-session');
const bodyParser = require('body-parser');
const SQLiteStore = require('connect-sqlite3')(session);
const expressLayouts = require('express-ejs-layouts');
require('dotenv').config();

const { ensureDirectoriesExist } = require('./src/utils/fs');
const authRoutes = require('./src/routes/auth');
const adminRoutes = require('./src/routes/admin');
const userRoutes = require('./src/routes/user');

const app = express();

// Ensure required directories
ensureDirectoriesExist([
  path.join(__dirname, 'data'),
  path.join(__dirname, process.env.UPLOAD_DIR || 'uploads'),
  path.join(__dirname, 'public'),
  path.join(__dirname, 'public', 'css'),
]);

app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'src', 'views'));
app.set('layout', 'layout');
app.use(expressLayouts);

app.use(express.static(path.join(__dirname, 'public')));
app.use('/uploads', express.static(path.join(__dirname, process.env.UPLOAD_DIR || 'uploads')));
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

app.use(
  session({
    store: new SQLiteStore({ db: 'sessions.db', dir: path.join(__dirname, 'data') }),
    secret: process.env.SESSION_SECRET || 'keyboard cat',
    resave: false,
    saveUninitialized: false,
    cookie: { maxAge: 7 * 24 * 60 * 60 * 1000 },
  })
);

// Expose auth info to views
app.use((req, res, next) => {
  res.locals.authUser = req.session.user || null;
  next();
});

// Routes
app.use('/', userRoutes);
app.use('/auth', authRoutes);
app.use('/admin', adminRoutes);

// 404
app.use((req, res) => {
  res.status(404).render('404', { title: 'Page Not Found' });
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Tacheto portal running on http://localhost:${PORT}`);
}); 