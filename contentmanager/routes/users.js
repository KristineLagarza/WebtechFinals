const express = require('express');
const router = express.Router();
var database = require('../connection');
var session;
const cacheControlMiddleware = require("../middleware/cacheControlMiddleware"); // Adjusted path

// Use the cache control middleware for all routes in this file
router.use(cacheControlMiddleware);


/* GET users listing. */
router.get('/', function(req, res, next) {
  if (!req.session.user) { // Go back to log in if user is not authenticated
    req.session.destroy();
    response.redirect('/');
  }
  res.send('respond with a resource');
});

router.get("../login", function(request, response, next){
  request.session.destroy();
  console.log("Session destroyed");
  response.redirect('/');
});

module.exports = router;
