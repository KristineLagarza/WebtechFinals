const express = require('express');
const router = express.Router();
var database = require('../connection');
var session;


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
