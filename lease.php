<?php

require_once 'functions.php';

if(!isset($_GET['id'])) {
	echo json_encode(array(
		'error' => 'No ID specified!'
	));
	exit();
}

$id = $_GET['id'];

if(($video = video_for_lease($id)) !== false) {
	echo json_encode(array('url' => $video));
    exit();
}

echo json_encode(array(
	'error' => 'Invalid ID specified!'
));

?>
