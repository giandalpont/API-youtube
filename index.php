<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>YouTube Channel video importing using Data API & PHP - Learn infinity</title>
    
    <!-- Bootstrap Core Css -->
    <link href="css/bootstrap.css" rel="stylesheet" />

    <!-- Font Awesome Css -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />

	<!-- Bootstrap Select Css -->
    <link href="css/bootstrap-select.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="css/app_style.css" rel="stylesheet" />
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link href="youtube/youtube_api.css" rel="stylesheet" />

	<style>
	
	</style>
</head>
<body>
    <div class="all-content-wrapper">
		<!-- Top Bar -->
		<?php require_once './include/header.php'; ?>
		<!-- #END# Top Bar -->
	
		<section class="container">
			<div class="form-group custom-input-space has-feedback">
				<div class="page-heading">
					<h3 class="post-title">YouTube Channel video importing using Data API & PHP - Learn infinity</h3>
				</div>
				<div class="page-body clearfix">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">Video List(s):</div>
								<div class="panel-body">
									<div id="my_video_list">
									
									<?php

                                    $API_Url = 'https://www.googleapis.com/youtube/v3/';
                                    $API_Key = 'AIzaSyAtJTiHyYPvNxpiLAAx84dUCppwcRrNXcs';

                                    // If you don't know the channel ID see below
                                    $channelId = 'UCviB93MjwO96GHSxW8qWBTg';

                                    $parameter = [
                                        'id' => $channelId,
                                        'part' => 'contentDetails',
                                        'key' => $API_Key,
                                    ];
                                    $channel_URL = $API_Url.'channels?'.http_build_query($parameter);
                                    $json_details = json_decode(file_get_contents($channel_URL), true);

                                    $playlist = $json_details['items'][0]['contentDetails']['relatedPlaylists']['uploads'];

                                    $parameter = [
                                        'part' => 'snippet',
                                        'playlistId' => $playlist,
                                        'maxResults' => '50',
                                        'key' => $API_Key,
                                    ];
                                    $channel_URL = $API_Url.'playlistItems?'.http_build_query($parameter);
                                    $json_details = json_decode(file_get_contents($channel_URL), true);

                                    $my_videos = [];
                                    foreach ($json_details['items'] as $video) {
                                        //$my_videos[] = $video['snippet']['resourceId']['videoId'];
                                        $my_videos[] = array('v_id' => $video['snippet']['resourceId']['videoId'], 'v_name' => $video['snippet']['title']);
                                    }

                                    while (isset($json_details['nextPageToken'])) {
                                        $nxt_page_URL = $channel_URL.'&pageToken='.$json_details['nextPageToken'];
                                        $json_details = json_decode(file_get_contents($nxt_page_URL), true);
                                        foreach ($json_details['items'] as $video) {
                                            $my_videos[] = $video['snippet']['resourceId']['videoId'];
                                        }
                                    }
                                    //print_r($my_videos);

                                    foreach ($my_videos as $video) {
                                        if (isset($video)) {
                                            echo '<a href="https://www.youtube.com/watch?v='.$video['v_id'].'" style="background: url(\'https://img.youtube.com/vi/'.$video['v_id'].'/mqdefault.jpg\')">
														<div>'.$video['v_name'].'</div>
												</a>';
                                        }
                                    }

                                    ?>

									</div>

								</div>
							</div>
						</div>

						<div id="my_player"><div></div></div>

					</div>
				</div>
			</div>
		</section>
    </div>
	
	<!-- Jquery Core Js -->
    <script src="js/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Bootstrap Select Js -->
    <script src="js/bootstrap-select.js"></script>
	
	<script>

	$(document).ready(function(e){

		$('#my_video_list a').on('click',function(e){

		e.preventDefault();

		var video_url = $(this).attr('href');

		
		var video_id = video_url.substring(video_url.search('=')+1,video_url.length);
		
		$('#my_player DIV').html('<iframe width="560" height="315" src="https://www.youtube.com/embed/' + video_id + '" frameborder="0" allowfullscreen></iframe>');

		  $('#my_player').fadeIn(500);

		});


		$('#my_player').on('click',function(e){
			$('#my_player').fadeOut(500);
			$('#my_player DIV').empty();
		});


	});
	</script>
</body>
</html>
