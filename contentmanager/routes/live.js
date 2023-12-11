const express = require("express");
const router = express.Router();
var database = require("../connection");
var session;

/* GET home page. */
router.get("/", function (req, res, next) {
  if (!req.session.user) {
    // Go back to log in if user is not authenticated
    req.session.destroy();
    res.redirect("/");
  } else {
    res.render("live", { title: "Express" });
  }
});

router.get("../login", function (request, response, next) {
  request.session.destroy();
  console.log("Session destroyed");
  response.redirect("/");
});

module.exports = router;
