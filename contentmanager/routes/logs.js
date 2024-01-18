const express = require("express");
const router = express.Router();
var database = require("../connection");

router.post("/insert", function (req, res, next) {
  if (req.headers["x-api-key"] !== "52fdecfa-d755-4426-88ee-53c4753e9e44") {
    // Go back to log in if user is not authenticated
    req.session.destroy();
    res
      .status(401)
      .json({ message: "You're not authorized to access this endpoint." });
  } else {
    if (req.body.userID === null || req.body.userID === undefined) {
      return res.status(400).json({ message: "Missing Required Paramaters" });
    }
    if (req.body.action === null || req.body.action === undefined) {
      return res.status(400).json({ message: "Missing Required Paramaters" });
    }
    console.log(req.body);

    const query =
      "INSERT INTO log (`UserID`, `Action`, `Timestamp`) VALUES (?,?,NOW())";
    database.query(
      query,
      [req.body.userID, req.body.action],
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
