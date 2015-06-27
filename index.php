<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="assets/images/favicon.png" rel="shortcut icon" type="image/x-icon"/>
<title>Drop - Concept Video Player</title>

<script type="text/javascript" src="assets/js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.effects.core.js"></script>
<script type="text/javascript" src="assets/js/jquery.effects.slide.js"></script>
<script type="text/javascript" src="assets/js/script.js"></script>
<script type="text/javascript" src="assets/js/drop_player.js"></script>
<script type="text/javascript" src="assets/js/tubeplayer.js"></script>
<script type="text/javascript" src="assets/js/swfobject.js"></script>
<script type="text/javascript" src="assets/js/jquery.li-scroller.1.0.js"></script>
<script type="text/javascript" src="assets/js/test.js"></script>

<link rel="stylesheet" type="text/css" href="assets/css/style.css" />
<link rel="stylesheet" type="text/css" href="assets/css/li-scroller.css" />
<link rel="stylesheet" type="text/css" href="assets/css/test.css" />

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-4760750-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>


</head>
<body>

<div id="container">

	<div id="header">
        <a href="#"><img src="assets/images/logo.png"/></a>
	</div> <!-- header -->

	<div id="nav_bar" class="textShadow shadow">
		<div id="left_nav_button" class="nav_bar_button" style="display:none">
			<img src="assets/images/back.png">
		</div>

		<div class="heading_wrapper">
		<div class="heading">

		</div>
		<div class="news_detail">

		</div>
		</div>

		<div id="right_nav_button" class="nav_bar_button" style="display:none">
			<a href="http://www.youtube.com/watch?v=KB5S-F8efIc"><img src="assets/images/yt.png"></a>
		</div>
	</div> <!-- nav_bar -->

	<div id="video_title" class="textShadow" style="display:none">
		<div id="video_name"></div>
		<div id="youtube_link"><a href="http://www.youtube.com/watch?v=KB5S-F8efIc">Youtube Link</a></div>
	</div>

	<div id="video_player_container" class="shadow" style="clear:both">
	   <div id="video_player"></div>
	</div> <!-- video_player_container -->

	<div id="ticker_panel" class="shadow">
		<div class="ticker_container">
		    <ul id="ticker"></ul>
		</div> <!-- ticker_container -->
	</div> <!-- ticker_panel -->

	<div id="thumbnail_panel" class="shadow">
		<div id="thumbnail_container">
		</div> <!-- thumbnail_container -->
	</div> <!-- thumbnail_panel -->

	<div id="no_more_related_videos" class="shadow" style="display:none">
		<div class="center_content">
		<img src="assets/images/no_more_icon.png"/> <div>No related videos found</div>
		</div>
	</div> <!-- no_more_related_videos -->

	<div id="footer" class="textShadow">
		<a href="http://qucentis.com/labs/drop/" >Drop</a> | Concept Video Player for Kerala WebTV by <a href="http://www.qucentis.com">Qucentis</a>.
	</div><!-- footer -->

</div> <!-- container -->

<div id="templates" style="display:none">
    <ul id="ticker-template">
        <li class="videoChangeButton">
            <div class="heading">
                <span class="serial"></span>. <span class="title"></span>
            </div>
            <div class="news_detail">
            </div>
        </li>
    </ul>

    <div id="thumbnail-template">
        <div class="thumbnail clickable videoChangeButton">
        	<img class="thumbnail_overlay_play_button" src="assets/images/play_small.png"/>
        	<img class="thumbnail_overlay_star" src="assets/images/star_small.png"/>
            <img class="thumbnail_image" src=""/>
        </div>
    </div>

    <div id="dialog-template">
    	<div class="dialog-wrapper shadow">
    		<div class="dialog-header">
    			<img src=""/><div class="dialog-header-text textShadow"></div>
    		</div>

    		<div class="dialog-content-wrapper">
    			<ul class="dialog-pages">
    			<li class="page-1">
    				<div class="dialog-content-header"></div>
    				<div class="dialog-content"></div>
    				<input class="grey-field phone-field" type="text" placeholder="+91 xxx xxx xxxx" />
    				<div class="action-button grey-button sendCodeToTelephone"></div>
    			</li>
    			<li class="page-2" style="display:none">
    				<div class="dialog-content-header"></div>
    				<div class="dialog-content"></div>
    				<input class="grey-field code-field" type="text" placeholder="x x x x" />
    				<div class="action-button green-button verifyCode"></div><div class="action-button red-button cancelDialog"></div>
    			</li>
    			<li class="page-3" style="display:none">
    				<div class="dialog-content-header"></div>
    				<div class="dialog-content"></div>
    				<div class="action-button grey-button videoURL"></div>
    			</li>

    			</ul>
    		</div>
    	</div>
    </div>

</div>

</body>
</html>
