<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>View</title>
</head>

<body>
</div>
<?php include('./spinner/spinner.php')?>
    <div class="video-container" id="video-container">
        <div class="video">
            <video id="myVideo" src="" type="video/mp4" autoplay muted loop width="110%" height="110%">
                <span style="display: none;">Your browser does not support the video tag.</span>
            </video>
            <button style="display: none;" id="muteButton" class="btn btn-info text-white">Mute/Unmute</button>';
         <!-- <video id="myVideo" autoplay muted loop width="110%" height="110%" >
                <source src="" type="video/mp4" alt="ERROR">
        </video> -->
        </div>
        
    </div>
    <div id="live-container" class="live-container">
        <div class="live"></div>
    </div>
    <button id="toggleContainer" class="btn btn-info text-black">Go to LIVE</button>
    <?php include('./footer/footer.php') ?>

    <script>
       let fileName = "";
        var liveContainer = document.getElementById("live-container");
        var videoContainer = document.getElementById("video-container");
        var toggleButton = document.getElementById("toggleContainer");

        liveContainer.style.display = "none";

        toggleButton.addEventListener("click", function() {

            if (liveContainer.style.display === "none" || liveContainer.style.display === "") {
                liveContainer.style.display = "block";
                videoContainer.style.display = "none";
                toggleButton.textContent = "Back";
                video.muted = true;

                addIframe();
            } else {
                video.muted = false;
                liveContainer.style.display = "none";
                videoContainer.style.display = "block"
                toggleButton.textContent = "Go to LIVE";

                removeIframe();
            }
        });

        function addIframe() {
            var container = document.getElementById('live-container');

            var iframe = document.createElement('iframe');
            iframe.id = 'liveVideo';
            // change 'ipaddress:port' to their actual ipaddress and port number
            iframe.src = 'http://ipaddress:port/embed/video';
            iframe.title = 'Owncast';
            iframe.height = '720px';
            iframe.width = '1280px';
            iframe.referrerpolicy = 'origin';
            iframe.allowfullscreen = true;

            container.appendChild(iframe);
        }

        function removeIframe() {
            var iframe = document.getElementById('liveVideo');

            if (iframe) {
                var parent = iframe.parentNode;
                parent.removeChild(iframe);
            }
        }

        var video = document.getElementById("myVideo");
        var muteButton = document.getElementById("muteButton");

        if(muteButton){
            muteButton.addEventListener("click", function() {
            try {
                if (video.muted) {
                    video.muted = false;
                    muteButton.textContent = "Mute";
                } else {
                    video.muted = true;
                    muteButton.textContent = "Unmute";
                }
            } catch (ex) {
                video.muted = false;
                muteButton.textContent = "Mute";
            }
        }); 
        }
       
        // var video = document.getElementById('myVideo');
        video.addEventListener('canplaythrough', function() {
            setTimeout(function() {
                video.play()
                    .then(() => {
                        if (fileName != "") {
                            fetch('http://127.0.0.1:3000/logs/insert', {
                                    method: 'POST', // You can adjust the method based on your server's expectations
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'x-api-key': '52fdecfa-d755-4426-88ee-53c4753e9e44'
                                    },
                                    body: JSON.stringify({
                                        userID: 0,
                                        action: `Viewer visits the page and played a video`,
                                        video: `${fileName}`,
                                    }),
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    // You can add further handling if needed
                                    console.log('Video playback logged successfully');
                                })
                                .catch(error => {
                                    // Handle any errors that occurred during the HTTP request
                                    console.error('Error logging video playback:', error);
                                });
                        }

                    })
                    .catch(error => {
                        // Handle any errors that occurred during playback
                        console.error('Error playing the video:', error);
                    });
            }, 10);
        });

        // setTimeout(function() {
        //     muteButton.click();
        // }, 10000);
    </script>
    <script defer src="https://cdn.socket.io/4.7.4/socket.io.min.js" integrity="sha384-Gr6Lu2Ajx28mzwyVR8CFkULdCU7kMlZ9UthllibdOSo6qAiN+yXNHqtgdTvFXMT4" crossorigin="anonymous"></script>
    <script defer type="text/javascript" src="scripts/filetransferReciever.js"></script>


</body>

</html>