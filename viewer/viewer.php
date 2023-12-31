<?php
require 'baseURL.php';
require 'connect.php';

$query = "SELECT content.ContentID, content.Title, content.Description, content.Type, content.`status`, duration.`from`, duration.`to`, history.fileName FROM content INNER JOIN duration ON content.durationID = duration.durationID INNER JOIN history ON content.historyID = history.historyID WHERE TIME(duration.from) <= TIME(NOW()) AND TIME(duration.to) >= TIME(NOW())";
$result = $conn->query($query);
$data = array();
if ($result->num_rows > 0) {
    
    while ($row = $result->fetch_assoc()) {
        array_push($data, $row);
    }
}
?>
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
    <div class="video-container" id="video-container">
        <div class="video">
            <?php
            if ($data) {
                echo '<video id="myVideo" autoplay muted loop width="110%" height="110%">
                <source src="' . $baseUrl . '/playVideo/' . $data[0]['fileName'] . '" type="video/mp4">
            </video><br><button id="muteButton" class="btn btn-info text-white">Mute/Unmute</button>';
            } else {
                echo '<video id="myVideo" autoplay muted loop width="110%" height="110%" >
                <source src="" type="video/mp4" alt="ERROR">
                Your browser does not support the video tag.
            </video>';
            }
            ?>
            </div>
        </div>
    <div id="live-container" class="live-container" >
        <div class="live"></div>
    </div>
    <button id="toggleContainer" class="btn btn-info text-black">Go to LIVE</button>
    <?php include('./footer/footer.php') ?>
    
    <script>
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

        muteButton.addEventListener("click", function() {
            if (video.muted) {
                video.muted = false;
                muteButton.textContent = "Mute";
            } else {
                video.muted = true;
                muteButton.textContent = "Unmute";
            }
        });
    </script>
</body>
</html>