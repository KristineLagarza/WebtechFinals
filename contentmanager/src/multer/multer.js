const multer = require("multer");
const path = require("path");

// Storage for video upload
const storage = multer.diskStorage({
  destination: "uploads/videos/", // Destination directory for storing videos
  filename: function (req, file, cb) {
    cb(
      null,
      file.fieldname + "-" + Date.now() + path.extname(file.originalname)
    );
  },
});

// File filter for extension
let fileFilter = function (req, file, cb) {
  console.log(file.mimetype);
  const allowedMimes = ["audio/mp3", "video/mp4", "video/mpeg", "video/webm"]; // Include other video MIME types as needed

  if (allowedMimes.includes(file.mimetype)) {
    cb(null, true);
  } else {
    cb(null, false);
  }
};

// Upload configuration to pass storage, file size limit, and filter
// Maximum file size is set to 50MB (adjust as needed)
const uploadMulter = multer({
  storage: storage,
  limits: { fileSize: 150 * 1024 * 1024 }, // 150MB limit for each file
  fileFilter: fileFilter,
}).array("files", 5); // Accepts up to 5 files, change as needed

module.exports = uploadMulter;
