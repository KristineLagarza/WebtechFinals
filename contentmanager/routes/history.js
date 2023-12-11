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
    const query =
      "SELECT history.historyID,`user`.username,history.filePath,history.fileName,history.dateUploaded FROM history INNER JOIN `user` ON history.UserID = `user`.UserID";
    database.query(query, (error, results, fields) => {
      if (error) {
        console.error("Error fetching data:", error);
        // Handle error
      } else {
        res.render("history", { title: "Express", results: results });
      }
    });
  }
});

router.get("../login", function (request, response, next) {
  request.session.destroy();
  console.log("Session destroyed");
  response.redirect("/");
});

module.exports = router;
