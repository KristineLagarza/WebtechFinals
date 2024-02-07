const express = require("express");
const router = express.Router();
var database = require("../connection");
const webSocket = require("../src/socket.io/socket-setup");
const sendFeedBack = require("../src/services/fileTransferService");
const { scheduleTask, deleteTask } = require("../src/services/jobSchedulerService");
const moment = require("moment");
var session;
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
    const arrangeError = req.session.arrangeError || null;
    const arrangeSuccess = req.session.arrangeSuccess || null;
    const deleteSuccess = req.session.deleteSuccess || null;
    delete req.session.arrangeError;
    delete req.session.arrangeSuccess;
    delete req.session.deleteSuccess;
    req.session.user = req.session.user;
    req.session.save();
    getArrangements()
      .then((a) => {
        processArrangement(res, arrangeError, arrangeSuccess, deleteSuccess, a);
      })
      .catch((errors) => {
        processArrangement(
          res,
          arrangeError,
          arrangeSuccess,
          deleteSuccess,
          []
        );
      });
  }
});

function processArrangement(
  res,
  arrangeError,
  arrangeSuccess,
  deleteSuccess,
  list
) {
  getListOfUploadedVideos()
    .then((data) => {
      getDurations()
        .then((durations) => {
          res.render("arrangement", {
            title: "Express",
            uploadedVideos: data,
            arrangeError: arrangeError,
            durations: durations,
            arrangeSuccess: arrangeSuccess,
            deleteSuccess: deleteSuccess,
            list: list,
          });
        })
        .catch((errorDuration) => {
          res.render("arrangement", {
            title: "Express",
            uploadedVideos: data,
            arrangeError: errorDuration,
            durations: [],
            arrangeSuccess: arrangeSuccess,
            deleteSuccess: deleteSuccess,
            list: list,
          });
        });
    })
    .catch((err) => {
      res.status(500).json({ errorMessage: err });
    });
}

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
      const { btnDeleteArrange } = req.body;
      if (btnDeleteArrange) {
        const { durationID } = req.body;
        if (durationID) {
          checkIfExist(durationID)
            .then((d) => {
              deleteArrangement(btnDeleteArrange)
                .then((s) => {
                  req.session.user = req.session.user;
                  req.session.deleteSuccess = true;
                  req.session.save();
                  res.redirect("/arrangement");
                })
                .catch((errs) => {
                  console.log(errs);
                  req.session.user = req.session.user;
                  req.session.arrangeError = JSON.stringify(errs);
                  req.session.save();
                  res.redirect("/arrangement");
                });
            })
            .catch((err) => {
              console.log(err);
              req.session.user = req.session.user;
              req.session.arrangeError = JSON.stringify(err);
              req.session.save();
              res.redirect("/arrangement");
            });
        }
      } else {
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
          } else {
            const {
              from,
              title,
              description,
              selectedVideos,
              btnDeleteArrange,
            } = req.body;
            saveArrangement(from, title, description, selectedVideos)
              .then((s) => {
                req.session.user = req.session.user;
                req.session.arrangeSuccess = true;
                req.session.save();
                res.redirect("/arrangement");
              })
              .catch((err) => {
                console.log(err);
                req.session.user = req.session.user;
                req.session.arrangeError = err;
                req.session.save();
                res.redirect("/arrangement");
              });
          }
        }
      }
    }
  }
});

function deleteArrangement(id) {
  const queryString =
    "SELECT content.durationID, duration.`from`, duration.`to` FROM content INNER JOIN duration ON content.durationID = duration.durationID WHERE ContentID=?";
  //Cancel the task before deleting
  database.query(queryString, [id], (err, result) => {
    if (err) {
      console.log(err);
    } else {
      if (result.length > 0){
        deleteTask(result);
      }
    }
  });

  return new Promise((resolves, rejects) => {
    database.query(
      "DELETE FROM content WHERE ContentID=?",
      [id],
      (err, results) => {
        if (err) {
          rejects(err);
        } else {
          resolves(results);
        }
      }
    );
  });
}

function saveArrangement(durationID, title, description, historyID) {

  var promise = new Promise((resolves, reject) => {
    checkIfExist(parseInt(durationID))
      .then((d) => {
        reject("Air Time Exist");
      })
      .catch((err) => {
        database.query(
          "INSERT INTO content (`Title`,`Description`,`Type`,`status`,`createdAt`,`historyID`,`durationID`) VALUES (?,?,?,?,NOW(),?,?)",
          [title, description, "Video", "Active", historyID, durationID],
          (errs, results) => {
            if (errs) {
              reject(errs);
            } else {
              resolves(results);
            }
          }
        );
      });
  });

    database.query(
      "SELECT * FROM duration WHERE `durationID`=?",
      [parseInt(durationID)],
      (err, result) => {
        if (err) {
          console.log(err);
        } else {
          if(result.length > 0){
            scheduleTask(result);
          }
        }
      }
    );


  return promise;
}

function checkIfExist(durationID) {
  return new Promise((resolve, reject) => {
    database.query(
      "SELECT * FROM content WHERE `durationID`=?",
      [durationID],
      (err, results) => {
        if (err) {
          reject(err);
        } else {
          if (results.length === 0) {
            reject();
          } else {
            resolve(results);
          }
        }
      }
    );
  });
}

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

function getDurations() {
  return new Promise((resolve, reject) => {
    database.query("SELECT * FROM duration", (error, results, fields) => {
      if (error) {
        reject(error);
      } else {
        resolve(results);
      }
    });
  });
}

function getArrangements() {
  return new Promise((resolve, reject) => {
    database.query(
      "SELECT content.ContentID,content.Title,content.Description,content.Type,content.`status`,content.createdAt,history.fileName,duration.durationID,duration.description AS ShowTime,duration.`from`,duration.`to` FROM content INNER JOIN history ON content.historyID = history.historyID INNER JOIN duration ON content.durationID = duration.durationID",
      (error, results, fields) => {
        if (error) {
          reject(error);
        } else {
          resolve(results);
        }
      }
    );
  });
}

module.exports = router;
