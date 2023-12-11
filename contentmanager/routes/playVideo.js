const express = require("express");
const path = require("path");
const router = express.Router();

router.get("/:fileName", function (req, res, next) {
  if (req.params.fileName) {
    const videoPath = path.resolve(
      __dirname,
      "../uploads",
      "videos",
      req.params.fileName
    );

    if (res.headersSent) {
      console.error("Headers already sent, cannot send file.");
      return;
    }

    res.status(200).sendFile(videoPath, (err) => {
      if (err) {
        if (!res.headersSent) {
          if (err.code === 'ENOENT') {
            res.status(404).json({ message: "File not found." });
          } else {
            res.status(500).json({ message: "Internal server error." });
          }
        }
      } else {
        console.log("Sent:", videoPath);
      }
    });
  } else {
    return res.status(400).json({ message: "Invalid file name." });
  }
});

module.exports = router;
