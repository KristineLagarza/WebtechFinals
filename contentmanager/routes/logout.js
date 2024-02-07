const express = require("express");
const router = express.Router();
const cacheControlMiddleware = require("../middleware/cacheControlMiddleware"); // Adjusted path

// Use the cache control middleware for all routes in this file
router.use(cacheControlMiddleware);

router.get("/", function (request, response, next) {
    if (request.session) {
        request.session.destroy(function (err) {
            if (err) {
                console.error(err);
                // Handle error if needed
            }
            console.log("Session destroyed");
            // Prevent caching
            response.setHeader("Cache-Control", "no-cache, no-store, must-revalidate");
            response.setHeader("Pragma", "no-cache");
            response.setHeader("Expires", "0");

            // Redirect to index page
            response.redirect("/"); // Replace "/index" with your index route
        });
    } else {
        // If no session, redirect to index page
        response.redirect("/"); // Replace "/index" with your index route
    }
});

module.exports = router;
