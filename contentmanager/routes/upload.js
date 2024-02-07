const express = require("express");
const router = express.Router();
var database = require("../connection");
var session;
const uploadMulter = require("../src/multer/multer");
const fs = require("fs");
const cacheControlMiddleware = require("../middleware/cacheControlMiddleware"); // Adjusted path

// Use the cache control middleware for all routes in this file
router.use(cacheControlMiddleware);

/* GET home page. */
router.get("/", function (req, res, next) {
  if (!req.session.user) {
    // Go back to log in if user is not authenticated
    req.session.destroy();
    res.redirect("/");
  } else {
    const uploadSuccess = req.session.uploadSuccess || null;
    const uploadError = req.session.uploadError || null;
    delete req.session.uploadSuccess;
    delete req.session.uploadError;
    req.session.save();
    res.render("upload", {
      title: "Express",
      uploadSuccess: uploadSuccess,
      uploadError: uploadError,
    });
  }
});

router.post("/", uploadMulter, async (req, res) => {
  if (!req.session.user) {
    // Go back to log in if user is not authenticated
    req.session.destroy();
    response.redirect("/");
  }
  // Access uploaded files through req.files array
  if (!req.files || req.files.length === 0) {
    return res.status(400).json({ message: "No files uploaded." });
  }
  const uploadedFiles = req.files;
  let isError = false;

  try {
    const promises = uploadedFiles.map((el) => {
      return new Promise((resolve, reject) => {
        const query =
          "INSERT INTO history (UserID, filePath, fileName, dateUploaded) VALUES (?, ?, ?, NOW());";

        database.query(
          query,
          [req.session.user.userID, el.path, el.filename],
          (error, results, fields) => {
            if (error) {
              console.error("Error inserting data:", error);
              isError = true;
              fs.unlink(el.path, (err) => {
                if (err) {
                  console.error("Error deleting file:", err);
                }
                resolve();
              });
            } else {
              console.log("Data inserted successfully:", results);
              resolve();
            }
          }
        );
      });
    });

    await Promise.all(promises);

    req.session.user = req.session.user;
    if (isError) {
      req.session.uploadError = true;
    } else {
      req.session.uploadSuccess = true;
    }
    req.session.save();

    res.redirect("/upload");
  } catch (err) {
    console.error("Error processing files:", err);
    res.status(500).json({ message: "Internal server error." });
  }
});

module.exports = router;
