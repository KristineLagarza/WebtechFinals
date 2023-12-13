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
    const arrangeError = req.session.arrangeError || null;
    delete req.session.arrangeError;
    req.session.user = req.session.user;
    req.session.save();
    getListOfUploadedVideos()
      .then((data) => {
        res.render("arrangement", {
          title: "Express",
          uploadedVideos: data,
          arrangeError: arrangeError,
        });
      })
      .catch((err) => {
        res.status(500).json({ errorMessage: err });
      });
  }
});

router.post("/", function (req, res, next) {
  if (!req.session.user) {
    // Go back to log in if user is not authenticated
    req.session.destroy();
    res.redirect("/");
  } else {
    console.log("body>>", req.body);
    if (req.body) {
      const desc = req.body.description || null;
      const selected = req.body.selectedVideos || null;
      if (!desc) {
        req.session.user = req.session.user;
        req.session.arrangeError = "empty description";
        req.session.save();
        res.redirect("/arrangement");
      } else {
        if (!selected) {
          req.session.user = req.session.user;
          req.session.arrangeError = "Please Do Not Forget To Select A Video";
          req.session.save();
          res.redirect("/arrangement");
        }
      }
    }
  }
});

router.get("../login", function (request, response, next) {
  request.session.destroy();
  console.log("Session destroyed");
  response.redirect("/");
});

function getListOfUploadedVideos() {
  return new Promise((resolve, reject) => {
    const query = "SELECT * FROM history";
    database.query(query, (error, results, fields) => {
      if (error) {
        console.error("Error fetching data:", error);
        reject(error); // Reject the promise with the error
      } else {
        resolve(results); // Resolve the promise with the results
      }
    });
  });
}

module.exports = router;
