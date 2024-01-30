const schedule = require("node-schedule");
const { sendFeedBack, noFeedBack } = require("./fileTransferService");
const moment = require("moment");
const { jobMap } = require('./fileTransferService');
const database = require('../../connection');

async function scheduleTask(data) {
  const durationID = parseInt(data[0].durationID);

  //Parse dateTime using moment library
  const currentTime = new moment();
  const fromTime = moment(data[0].from, "HH:mm");
  const toTime = moment(data[0].to, "HH:mm");

  const ruleStart = new schedule.RecurrenceRule();
  const ruleEnd = new schedule.RecurrenceRule();  

  //format time and set rules for the job
  ruleStart.hour = fromTime.format("HH");
  ruleStart.minute = fromTime.format("mm");

  ruleEnd.hour = toTime.format("HH");
  ruleEnd.minute = toTime.format("mm");

  //Send the video and schedule a task
  if (currentTime.isSameOrAfter(fromTime) && currentTime.isSameOrBefore(toTime)) {
      sendFeedBack(durationID);
    
    const jobStart = schedule.scheduleJob(ruleStart, () => {
      sendFeedBack(durationID);
    });

    const jobEnd = schedule.scheduleJob(ruleEnd, () => {
      noFeedBack();
    });

    jobMap.set(`${durationID}-start`, jobStart);
    jobMap.set(`${durationID}-end`, jobEnd);
  } else {
    isCurrentVideo().then(result => {
      console.log(result);
      if(!result){
        noFeedBack();
      }
    })
   
    
    const jobStart = schedule.scheduleJob(ruleStart, () => {
      sendFeedBack(durationID);
    });

    const jobEnd = schedule.scheduleJob(ruleEnd, () => {
      noFeedBack();
    });

    jobMap.set(`${durationID}-start`, jobStart);
    jobMap.set(`${durationID}-end`, jobEnd);
  }
}


function cancelTask(durationID) {
  var jobStart = jobMap.get(`${durationID}-start`);
  var jobEnd = jobMap.get(`${durationID}-end`);

  if (jobStart != null  && jobEnd != null) {
    jobStart.cancel();
    jobEnd.cancel();
  }
}

function deleteTask(data){
    const durationID = parseInt(data[0].durationID);
    cancelTask(durationID);
    const currentTime = moment();
      const fromTime = moment(data[0].from, "HH:mm");
      const toTime = moment(data[0].to, "HH:mm");

      if (currentTime.isSameOrAfter(fromTime) && currentTime.isSameOrBefore(toTime)) {
        noFeedBack();
      }
}

function isCurrentVideo() {
  var queryString = "SELECT content.ContentID, content.durationID, content.Title, content.Description, content.Type, content.`status`, duration.`from`, duration.`to`, history.fileName FROM content INNER JOIN duration ON content.durationID = duration.durationID INNER JOIN history ON content.historyID = history.historyID WHERE TIME(duration.from) <= TIME(NOW()) AND TIME(duration.to) >= TIME(NOW())";

  return new Promise((resolve, reject) => {
      database.query(queryString, (err, result) => {
          if (err) {
              console.log(err);
              reject(err);
          } else {
              if (result.length > 0) {
                  resolve(true);
              } else {
                  resolve(false);
              }
          }
      });
  });
}

module.exports = { scheduleTask, cancelTask, deleteTask };
