const express = require('express');
const router = express.Router();
const database = require('../connection');

router.get('/', (req, res, next) => {
    res.render('index', { title: 'Express' });
});

router.post('/', (req, res) => {
    const username = req.body.username;
    const password = req.body.password;

    const userAccountQuery = "SELECT * FROM user WHERE Username = ? AND Password = ? AND Type = 'content_manager'";

    database.query(userAccountQuery, [username, password], (error, data) => {
        if (error) {
            console.error(error);
            res.render('error', { message: 'Database error' });
        } else if (data.length === 1) {
            req.session.user = {
                date: new Date().toLocaleDateString(),
                username: username,
            };
            req.session.save();

            console.log("\n=====================================");
            console.log("Date: " + req.session.user.date);
            console.log("Session ID: " + req.session.id);
            console.log("=====================================\n");

            res.redirect('/dashboard');
        } else {
            res.render('error', { message: 'Invalid credentials or not a content manager' });
        }
    });
});

module.exports = router;
