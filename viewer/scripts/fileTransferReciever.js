const socket = io("http://localhost:3001", {
  withCredentials: true,
});

const videoElement = document.getElementById("myVideo");
const fileNameElement = document.getElementById("filename");
const muteBtnElement = document.getElementById("muteButton");

socket.on("onLoadVideo", (data) => {
 loadVideo(data);
});

socket.on("onVideoStart", (data) => {
    videoElement.src = ""
    loadVideo(data);
});

socket.on("onVideoEnd", (data) => {
    videoElement.src = "";
    muteBtnElement.style.display = "none"; 
    document.getElementById('spinner').style.display = "flex";
});

function loadVideo(data){
    setTimeout(() => {
        if (data != null) {
          document.getElementById('spinner').style.display = "none";
          let videoFileBlob = new Blob([data.videoFile]);
          const videoFileUrl = URL.createObjectURL(videoFileBlob);
          muteBtnElement.style.display = "block";       
          videoElement.src = videoFileUrl;
          fileName = data.fileName;
      }
    }, 1000);
}

// socket.on('fileTransfer', data => {
//     const video = document.getElementById('myVideo');
//     const videoSource = document.createElement('source');

//     if(data != null){
//         let videoFileBlob = new Blob([data[0].videoFile]);
//         const videoFileUrl = URL.createObjectURL(videoFileBlob);
//         videoSource.setAttribute('id', 'video-source');
//         videoSource.src = videoFileUrl;
//         video.append(videoSource);

//         console.log('listening')
//     }else{
//         console.log(data);
//         const refreshBtn = document.getElementById('refresh-video');

//         // if(!videoSource){
//         //     console.log('line')
//         //     video.removeChild(videoSource);
//         // }
//     }
// })
