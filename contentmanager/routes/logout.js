const express = require("express");
const router = express.Router();

router.get("/", function (request, response, next) {
  request.session.destroy();
  console.log("Session destroyed");
  response.redirect("/");
});

module.exports = router;
