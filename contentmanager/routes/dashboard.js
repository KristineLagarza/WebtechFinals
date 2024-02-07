const express = require("express");
const router = express.Router();
const cacheControlMiddleware = require("../middleware/cacheControlMiddleware"); // Adjusted path

// Use the cache control middleware for all routes in this file
router.use(cacheControlMiddleware);

router.get("/", function (req, res, next) {
    if (!req.session.user) {
        // Go back to log in if the user is not authenticated
        req.session.destroy();
        return res.redirect("/");
    } else {
        const loginSuccess2 = req.session.loginSuccess || null;
        delete req.session.loginSuccess;
        req.session.loginSuccess = null;
        req.session.save();

        res.render("dashboard", { title: "Dashboard", loginSuccess: loginSuccess2 });
    }
});

module.exports = router;
