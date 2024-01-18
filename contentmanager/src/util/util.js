const database = require("../../connection");

function saveLogToDB(userID, action) {
    return new Promise((resolve, reject) => {
      const query =
        "INSERT INTO log (`UserID`, `Action`, `Timestamp`) VALUES (?,?,NOW())";
      database.query(query, [userID, action], (error, results, fields) => {
        if (error) {
          console.log("Error in logging -> ", error);
          reject(error); // Reject the promise in case of an error
        } else {
          resolve(results); // Resolve the promise with the results in case of success
        }
      });
    });
  }
  

module.exports = saveLogToDB;
