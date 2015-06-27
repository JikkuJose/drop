<?php

require_once 'functions.php';

if(!isset($_GET['id'])) {
	echo json_encode(array(
		'error' => 'No ID specified!'
	));
	exit();
}

$id = (int) $_GET['id'];

if(($video = video($id, true)) !== false) {
	echo json_encode($video);
    exit();
}

echo json_encode(array(
	'error' => 'Invalid ID specified!'
));

?>
