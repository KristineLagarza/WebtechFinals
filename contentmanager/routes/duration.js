const express = require("express");
const router = express.Router();
var database = require("../connection");
const moment = require('moment');
const { scheduleTask, cancelTask, deleteTask } = require('../src/services/jobSchedulerService');
const connection = require("../connection");
const cacheControlMiddleware = require("../middleware/cacheControlMiddleware"); // Adjusted path

// Use the cache control middleware for all routes in this file
router.use(cacheControlMiddleware);

router.get("/", (req, res, next) => {
  const user = req.session.user || null;
  if (user) {
    const durationSuccess = req.session.durationSuccess || null;
    const durationError = req.session.durationError || null;
    const durationUpdateSuccess = req.session.durationUpdateSuccess || null;
    const durationDeleteSuccess = req.session.durationDeleteSuccess || null;

    delete req.session.durationSuccess;
    delete req.session.durationError;
    delete req.session.durationUpdateSuccess;
    delete req.session.durationDeleteSuccess;
    req.session.save();

    getDurations()
      .then((data) => {
        res.render("duration", {
          title: "Duration",
          durationSuccess: durationSuccess,
          durationError: durationError,
          results: data,
          durationUpdateSuccess: durationUpdateSuccess,
          durationDeleteSuccess: durationDeleteSuccess,
        });
      })
      .catch((err) => {
        console.log(err);
        res.render("duration", {
          title: "Duration",
          durationSuccess: durationSuccess,
          durationError: durationError,
          results: [],
          durationUpdateSuccess: durationUpdateSuccess,
          durationDeleteSuccess: durationDeleteSuccess,
        });
      });
  } else {
    // Go back to log in if user is not authenticate
    req.session.destroy();
    res.redirect("/");
  }
});

router.post("/", function (req, res, next) {
  const user = req.session.user || null;
  if (user) {
    const { id, from, to, description, btnUpdateDuration, btnDeleteDuration } =
      req.body;
    if (btnUpdateDuration) {
      checkIfExist(from, to)
        .then((valid) => {
          updateDuration(id, from, to, description)
            .then((data) => {
              req.session.user = req.session.user;
              req.session.durationUpdateSuccess = true;
              req.session.save();
              return res.redirect("/duration");
            })
            .catch((err) => {
              console.log(err);
              req.session.user = req.session.user;
              req.session.durationError = err;
              req.session.save();
              return res.redirect("/duration");
            });
        })
        .catch((err) => {
          req.session.user = req.session.user;
          req.session.durationError = "Air Time Exist";
          req.session.save();
          return res.redirect("/duration");
        });
    } else if (btnDeleteDuration) {
      checkIfExistId(id)
        .then((data) => {
          deleteDuration(id)
            .then((success) => {
              req.session.user = req.session.user;
              req.session.durationDeleteSuccess = true;
              req.session.save();
              return res.redirect("/duration");
            })
            .catch((err) => {
              console.log(err);
              req.session.user = req.session.user;
              req.session.durationError = err;
              req.session.save();
              return res.redirect("/duration");
            });
        })
        .catch((err) => {
          console.log(err);
          req.session.user = req.session.user;
          req.session.durationError = err;
          req.session.save();
          return res.redirect("/duration");
        });
    } else {
      checkIfExist(from, to)
        .then((data) => {
          if (data.length === 0) {
            saveDuration(from, to, description)
              .then((data) => {
                req.session.user = req.session.user;
                req.session.durationSuccess = true;
                req.session.save();
                return res.redirect("/duration");
              })
              .catch((err) => {
                console.log(err);
                req.session.user = req.session.user;
                req.session.durationError = err;
                req.session.save();
                return res.redirect("/duration");
              });
          } else {
            req.session.user = req.session.user;
            req.session.durationError = "Air Time Exist";
            req.session.save();
            return res.redirect("/duration");
          }
        })
        .catch((err) => {
          saveDuration(from, to, description)
            .then((data) => {
              req.session.user = req.session.user;
              req.session.durationSuccess = true;
              req.session.save();
              return res.redirect("/duration");
            })
            .catch((err) => {
              console.log(err);
              req.session.user = req.session.user;
              req.session.durationError = err;
              req.session.save();
              return res.redirect("/duration");
            });
        });
    }
  } else {
    // Go back to log in if user is not authenticate
    req.session.destroy();
    res.redirect("/");
  }
});

function deleteDuration(id) {
  const queryString =
  "SELECT durationID, `from`, `to` FROM duration WHERE duration.durationID=?";
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


  return new Promise((resolve, reject) => {
    database.query(
      "DELETE FROM duration WHERE `durationID`=?",
      [parseInt(id)],
      (err, results) => {
        if (err) {
          reject(err);
        } else {
          resolve(results);
        }
      }
    );
  });
}

function updateDuration(id, from, to, description) {
  var promise = new Promise((resolve, reject) => {
    database.query(
      "UPDATE duration Set `from`=?,`to`=?,`description`=? WHERE `durationID`=?",
      [from, to, description, parseInt(id)],
      (err, results) => {
        if (err) {
          reject(err);
        } else {
          resolve(results);
        }
      }
    );
  });

  let durationID = id;
  cancelTask(id);
  const duration = [{durationID, from, to, description}];
  const queryString =
  "SELECT ContentID FROM content WHERE `durationID`=?";
  connection.query(queryString,
  [id], (err, result)=>{
    if(err){
      console.log(err);
    }else{
      if(result.length > 0){
        cancelTask(parseInt(duration[0].durationID));
        scheduleTask(duration);
      }
    }
  });

  return promise;

}

function saveDuration(from, to, description) {
  return new Promise((resolve, reject) => {
    database.query(
      "INSERT INTO duration (`from`,`to`,`description`,`createdAt`) VALUES (?,?,?,NOW())",
      [from, to, description],
      (err, results) => {
        if (err) {
          reject(err);
        } else {
          resolve(results);
        }
      }
    );
  });
}

function checkIfExistId(id) {
  return new Promise((resolve, reject) => {
    database.query(
      "SELECT * FROM duration where `durationID`=?",
      [id],
      (error, results) => {
        if (error) {
          console.log(error);
          reject(error);
        } else {
          resolve(results);
        }
      }
    );
  });
}

function checkIfExist(from, to) {
  return new Promise((resolve, reject) => {
    database.query(
      "SELECT * FROM duration WHERE `from`=? and `to`=?",
      [from, to],
      (error, results) => {
        if (error) {
          console.log(error);
          reject(error);
        } else {
          resolve(results);
        }
      }
    );
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

module.exports = router;
