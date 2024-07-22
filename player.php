<?php include './components/config.php' ?>
<?php include './api/isUserLogged.php' ?>
<?php include './components/include.php' ?>

<?php 

$crsnoq = $db->quote($event_code);
$live = $db->query("SELECT * FROM live_streams,courses_live WHERE courses_live.crsno = $crsnoq AND live_streams.id = courses_live.live_id")->fetch();

$now = new DateTime();

$st = $live['appointment_day']." ".$live['appointment_start_at'];
$start_at = DateTime::createFromFormat("Y-m-d H:i:s",$st);

$end = $live['appointment_day']." ".$live['appointment_end_at'];
$end_at = DateTime::createFromFormat("Y-m-d H:i:s",$end);

//Se la live è oggi ma poco più avanti nel futuro ti rimanda indietro alla locandina

if($now->getTimestamp() < $start_at->getTimestamp()){
    ?>
    <script>
        window.location = './locandina.php'
    </script>
    <?php
}

//Se la live è oggi ma è finita ti rimanda indietro alla locandina

if($now->getTimestamp() > $end_at->getTimestamp()){
    ?>
    <script>
        window.location = './locandina.php'
    </script>
    <?php
}

?>

<!-- 
    
    THEOplayer library and css 

    <script type="text/javascript" src="https://cdn.myth.theoplayer.com/a004ba5e-0481-45ac-b10b-192ebf5923dee/THEOplayer.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.myth.theoplayer.com/a004ba5e-0481-45ac-b10b-192ebf5923dee/ui.css" />
    <!-- CSS customization style  
    <style>
            /* Customization css style */
            .theo-primary-color,
            .vjs-selected {
                color: #3ea4ff !important;
            }

            .theo-primary-background {
                color: #000000 !important;
                background-color: #3ea4ff !important;
            }
    </style>

-->

<link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet" />
<script src="https://vjs.zencdn.net/7.17.0/video.min.js"></script>
<link href="https://players.brightcove.net/videojs-quality-menu/1/videojs-quality-menu.css" rel='stylesheet'>
<script src="https://players.brightcove.net/videojs-quality-menu/1/videojs-quality-menu.min.js"></script>

<style>

			/* Prevent CC settings menu item from displaying */
			.vjs-texttrack-settings {
				display: none;
			}

			.vjs-caption-settings {
				display: none;
			}
			
			.vjs-subs-caps-button {
				display: none;
			}

			.vjs-big-play-centered .vjs-big-play-button {
				color: white;
				border-color: #467fbf;
				background-color: #467fbf;

				font-size: 8em;
			}

			.video-js:hover .vjs-big-play-button,.video-js .vjs-big-play-button:focus,.video-js .vjs-big-play-button:active {
				color: white;
				border-color: #396DA7;
				background-color: #396DA7;
			}

</style>

<body class="blue darken-4">
    <div class="container">
        <section class="section section-player row">
            <div class="col s12">
                <div class="card ">
                    <div class="card-content center-align">
                        <img style="width:100%;max-width:240px" src="https://www.assodpo.it/wp-content/uploads/2019/10/Asso-DPO-Associazione-Data-Protection-Officer.png" alt="" srcset="">                            
                        <!-- <div class="theoplayer-container video-js theoplayer-skin vjs-16-9"></div> -->
                        <video class="video-js vjs-big-play-centered vjs-16-9" id="video-js"></video>
                    </div>
                </div>
            </div>
        </section>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large red" id="logout">
                <i class="fas fa-power-off"></i>
            </a>
        </div>
        
    </div>
</body>

<script>
    init_player();

    function init_player(){
        const user_id = `<?php echo $_SESSION['uid'] ?>`
        const live_id = `<?php echo $live['live_id'] ?>`

        const playlist_url = `<?php echo $live_source?>livestream/master1.m3u8?liveid=${live_id}&id=${user_id}`

        /** 

            var element = document.querySelector(".theoplayer-container");
            player = new THEOplayer.Player(element, {
                libraryLocation: "https://cdn.myth.theoplayer.com/a004ba5e-0481-45ac-b10b-192ebf5923dee",
                license: "sZP7IYe6T6PgTQ4KTS1eImzo3lP1FSaLTSe-TQ31C6zZTu5t3KCZ0KBc0uB6FOPlUY3zWokgbgjNIOf9flA1Iu413QP6FDatCLB-3uRkImzrClaoFSBtIuPlCDxeCDao3mfVfK4_bQgZCYxNWoryIQXzImf90SCz3Lfk0l5i0u5i0Oi6Io4pIYP1UQgqWgjeCYxgflEc3lacTuBo0S5i0uCkFOPeWok1dDrLYtA1Ioh6TgV6v6fVfKcqCoXVdQjLUOfVfGxEIDjiWQXrIYfpCoj-fgzVfKxqWDXNWG3ybojkbK3gflNWf6E6FOPVWo31WQ1qbta6FOPzdQ4qbQc1sD4ZFK3qWmPUFOPLIQ-LflNWfK1zWDikfgzVfG3gWKxydDkibK4LbogqW6f9UwPkIYz"
            });

            // OPTIONAL CONFIGURATION

            // Customized video player parameters
            player.source = {
                sources: [{
                    "src": playlist_url,
                    "type": "application/x-mpegurl"
                }]

            };

            player.autoplay = true;
            player.muted = true;
            player.preload = 'auto';
            $(`li[aria-label="Open the video speed settings menu"]`).remove();

        **/

            const player = videojs('video-js', {
              controls: true, 
              autoplay: false, 
              playsinline: true,
              muted: false,
              preload: 'auto', 
              textTrackSettings: false,
              html5: {nativeTextTracks: false},
              controlBar: {
                  fullscreenToggle: true,
                  pictureInPictureToggle: false,
                  subtitlesButton: false
              }
            });

            player.src({type: 'application/x-mpegURL', src: playlist_url})

            player.qualityMenu({
                defaultResolution: 'auto'
            });

            player.on('pause', function(e) {
                player.play()
            });
    }

</script>

<script>

    $('#logout').on('click', () => {
        fetch('./api/logout.php')
        .then(res => res.text())
        .then(data => {
            if(data == "success"){
                window.location = `./index.php`
            }else{
                console.log(data)
            }
        })
        .catch(e => console.log(e))
    })

</script>

<script>

// setInterval(() => {
//     const url = "./api/tracker.php";

//     fetch(url)
//     .then(res => res.text())
//     .then(data => console.log(data))
//     .catch(e => console.log(e))

// }, 20000);


</script>