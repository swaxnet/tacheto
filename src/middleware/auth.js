function requireAuth(req, res, next) {
  if (req.session && req.session.user && req.session.user.role === 'admin') {
    return next();
  }
  return res.redirect('/auth/login?next=' + encodeURIComponent(req.originalUrl || '/admin'));
}

function redirectIfAuthenticated(req, res, next) {
  if (req.session && req.session.user) {
    return res.redirect('/admin');
  }
  next();
}

module.exports = { requireAuth, redirectIfAuthenticated }; 