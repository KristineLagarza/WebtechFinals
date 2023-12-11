const express = require("express");
const router = express.Router();

router.get("/", function (req, res, next) {
  if (!req.session.user) {
    // Go back to log in if user is not authenticated
    req.session.destroy();
    return res.redirect("/");
  } else {
    console.log(req.session);
    const loginSuccess2 = req.session.loginSuccess | null;
    delete req.session.loginSuccess;
    req.session.loginSuccess = null;
    req.session.save();

    res.render("dashboard", { title: "Dashboard", loginSuccess: loginSuccess2 });
  }
});

module.exports = router;
