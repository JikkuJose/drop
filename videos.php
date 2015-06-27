<?php

require_once 'db.php';

$query="SELECT * FROM videos";

$videos = array();

if ($result = $db->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $video = array(
        	'id' => $row['id'],
			'title' => $row['title'],
			'video' => $row['video'],
			'start' => $row['start'],
			'description' => $row['description']
        );

		$videos[] = $video;
    }

    $result->free();
}

echo json_encode($videos);
?>