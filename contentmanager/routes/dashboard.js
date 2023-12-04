const express = require('express');
const router = express.Router();

router.get('/', function (req, res, next) {
    if (!req.session.user) { // Go back to log in if user is not authenticated
        return res.redirect('/');
    }

    res.render('dashboard', { title: 'Dashboard' });
});

module.exports = router;
