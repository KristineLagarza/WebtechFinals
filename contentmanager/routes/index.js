const express = require("express");
const router = express.Router();
const database = require("../connection");
const saveLogToDB = require("../src/util/util");

router.get("/", (req, res, next) => {
  if (req.session.user) {
    // Go back to log in if user is not authenticate
    res.redirect("/dashboard");
  } else {
    res.render("index", { title: "Express" });
  }
});

router.post("/", (req, res) => {
  const username = req.body.username;
  const password = req.body.password;

  const userAccountQuery =
    "SELECT * FROM user WHERE Username = ? AND Password = ? AND Type = 'content_manager'";

  database.query(userAccountQuery, [username, password], (error, data) => {
    if (error) {
      console.error(error);
      res.render("error", { message: "Database error" });
    } else if (data.length === 1) {
      req.session.user = {
        date: new Date().toLocaleDateString(),
        username: username,
        userID: data[0].UserID,
        userType: data[0].Type,
      };
      req.session.loginSuccess = true;
      req.session.save();

      // let uType = data[0].Type;

      // if (uType === "content_manager") {
      //   saveLogToDB(
      //     data[0].UserID,
      //     `Content Manager UserID:${data[0].UserID} Logged In`
      //   );
      // } else if (uType === "admin") {
      //   saveLogToDB(data[0].UserID, "Admin Logged In");
      // }

      console.log("\n=====================================");
      console.log("Date: " + req.session.user.date);
      console.log("Session ID: " + req.session.id);
      console.log("=====================================\n");

      res.redirect("/dashboard");
    } else {
      res.render("error", {
        message: "Invalid credentials or not a content manager",
      });
    }
  });
});

module.exports = router;
