const express = require("express");
const router = express.Router();
var database = require("../connection");
const cacheControlMiddleware = require("../middleware/cacheControlMiddleware"); // Adjusted path

// Use the cache control middleware for all routes in this file
router.use(cacheControlMiddleware);

router.get("/view", function (req, res, next) {
  if (!req.session.user) {
    // Go back to log in if user is not authenticated
    req.session.destroy();
    res.redirect("/");
  } else {
    const query = "SELECT * FROM log";
    database.query(query, (error, results, fields) => {
      if (error) {
        console.error("Error fetching data:", error);
        // Handle error
      } else {
        res.render("videoLogs", {
          title: "Express",
          uploadSuccess: null,
          uploadError: null,
          uploadedVideos: [],
          results: results,
        });
      }
    });
  }
});

router.post("/insert", function (req, res, next) {
  if (req.headers["x-api-key"] !== "52fdecfa-d755-4426-88ee-53c4753e9e44") {
    // Go back to log in if user is not authenticated
    req.session.destroy();
    res
      .status(401)
      .json({ message: "You're not authorized to access this endpoint." });
  } else {
    console.log(req.body);
    // if (!req.body.userID || !req.body.action || !req.body.video) {
    //   return res.status(400).json({ message: "Missing Required Paramaters" });
    // }
    const query =
      "INSERT INTO log (`UserID`, `Action`,`Video`, `Timestamp`) VALUES (?,?,?,NOW())";
    database.query(
      query,
      [req.body.userID, req.body.action, req.body.video],
      (error, results, fields) => {
        if (error) {
          res.status(500).json({ message: error });
        } else {
          res.status(200).json({ message: "Success" });
        }
      }
    );
  }
});

module.exports = router;
