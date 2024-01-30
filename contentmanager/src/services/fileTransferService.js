const { getIO } = require('../socket.io/socket-setup');
const database = require('../../connection');
const path = require('path');
const fs = require('fs');
const moment = require('moment');
const schedule = require("node-schedule");
const jobMap = new Map();

async function sendFeedBack(durationID)
{
  if(durationID != null && durationID > 0){
    var data = await getFileToTransfer(durationID);
    getIO().emit('onVideoStart', data);
  }
}

function noFeedBack(){
    getIO().emit('onVideoEnd', 'Video has ended');
}

function loadVideo(){
var queryString = "SELECT content.ContentID, content.durationID, content.Title, content.Description, content.Type, content.`status`, duration.`from`, duration.`to`, history.fileName FROM content INNER JOIN duration ON content.durationID = duration.durationID INNER JOIN history ON content.historyID = history.historyID";

    database.query(queryString, async (err, results) => {
        if(err){
            console.log(err);
        }else{
            if(results.length > 0){
                results.forEach(async result => {
                  scheduleTaskOnLoad(result);
                });
            }
        }
    });
}

async function scheduleTaskOnLoad(result){
  const durationID = parseInt(result.durationID);
  const fromTime = moment(result.from, "HH:mm");
  const toTime = moment(result.to, "HH:mm");
  const currentTime = new moment();
  const ruleStart = new schedule.RecurrenceRule();
  const ruleEnd = new schedule.RecurrenceRule();  

  ruleStart.hour = fromTime.format("HH");
  ruleStart.minute = fromTime.format("mm");

  ruleEnd.hour = toTime.format("HH");
  ruleEnd.minute = toTime.format("mm");
  var data = await streamFile(result)
  if (currentTime.isSameOrAfter(fromTime) && currentTime.isSameOrBefore(toTime)) {
    getIO().emit("onLoadVideo", data);
  }

  const jobStartObj =  jobMap.get(`${durationID}-start`);
  const jobEndObj =  jobMap.get(`${durationID}-end`);
  if (jobStartObj == null  && jobEndObj == null) {
    jobStart = schedule.scheduleJob(ruleStart, () => {
      getIO().emit('onVideoStart', data);
    });

    jobEnd = schedule.scheduleJob(ruleEnd, () => {
      getIO().emit('onVideoEnd', 'Video has ended');
    });

    jobMap.set(`${durationID}-start`, jobStart);
    jobMap.set(`${durationID}-end`, jobEnd);
  }
}

async function getFileToTransfer(durationID) {

    const data = await getCurrentVideo(durationID);
    return await streamFile(data[0]);
}

async function streamFile(data){
  if (data != null) {
    const fileName = data.fileName;
    const videoPath = path.resolve(
      __dirname,
      "../../uploads",
      "videos",
      fileName
    );

    let videoFile = await new Promise((resolve, reject) => {
      fs.readFile(videoPath, async (err, file) => {
        if (err) {
          reject(err);
        }else{
          resolve(file);
        }
      });
    })

    data.videoFile = videoFile;

    return data;
  }
}

function getCurrentVideo(durationID) {
  return new Promise((resolve, reject) => {
    var queryString = "SELECT content.ContentID, content.Title, content.Description, content.Type, content.`status`, duration.`from`, duration.`to`, history.fileName FROM content INNER JOIN duration ON content.durationID = duration.durationID INNER JOIN history ON content.historyID = history.historyID WHERE content.`durationID` = ?";
    database.query(queryString, [durationID],  (error, result) => {
      if (error) {
        reject(error);
      } else {
        resolve(result);
      }
    });
  });
}

module.exports = { sendFeedBack, noFeedBack, loadVideo, jobMap};
