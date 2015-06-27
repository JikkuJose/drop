<?php

define('EXPIRY', 300);

require_once 'db.php';

function get_random_string($valid_chars, $length) {
    $random_string = "";

    $num_valid_chars = strlen($valid_chars);

    for ($i = 0; $i < $length; $i++) {
        $random_pick = mt_rand(1, $num_valid_chars);
        $random_char = $valid_chars[$random_pick-1];
        $random_string .= $random_char;
    }

    return $random_string;
}

function delete_expired_leases() {
    global $db;

    $now = new DateTime();
    $expiry = $now->format('Y-m-d H:i:s');

    $query = "DELETE FROM leases WHERE expiry <= \"$expiry\"";

    $result = $db->query($query);
}

function new_lease_for_video($id, $expiry=-1) {
    global $db;

    delete_expired_leases();

    $id = (int) $id;
    $ip = addslashes($_SERVER['REMOTE_ADDR']);

    if($lease = lease_for_video($id)) {
        return $lease;
    }

    if($expiry == -1)
        $expiry = EXPIRY;

    $now = new DateTime();
    $now->modify("+{$expiry} seconds");
    $expiry = $now->format('Y-m-d H:i:s');

    $url = get_random_string("abcdefghijklmnopqrstuvwxyz0123456789", 3);

    $query = "INSERT INTO leases(video, url, ip, expiry) VALUES($id, \"$url\", \"$ip\", \"$expiry\")";

    if($result = $db->query($query)) {
        return $url;
    }

    return false;
}

function lease_for_video($id) {
    global $db;

    delete_expired_leases();

    $id = (int) $id;
    $ip = $_SERVER['REMOTE_ADDR'];

    $now = new DateTime();
    $now = $now->format('Y-m-d H:i:s');

    $lease = false;

    $query = "SELECT url FROM leases WHERE video = $id AND ip = \"$ip\" AND expiry > \"$now\" LIMIT 1" ;

    if ($result = $db->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $lease = $row['url'];
        }

        $result->free();
    }

    return $lease;

}

function video_for_lease($url) {
    global $db;

    delete_expired_leases();

    $url = addslashes($url);
    $ip = $_SERVER['REMOTE_ADDR'];

    $now = new DateTime();
    $now = $now->format('Y-m-d H:i:s');

    $video = false;

    $query = "SELECT video FROM leases WHERE url = \"$url\" AND ip = \"$ip\" AND expiry > \"$now\" LIMIT 1" ;

    if ($result = $db->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $video = $row['video'];
        }

        $result->free();
    }

    if(!$video)
        return false;

    $query = "SELECT video FROM videos WHERE id = $video" ;


    if ($result = $db->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $video = $row['video'];
        }

        $result->free();
    }

    return $video;

}

function video($id, $get_related_videos=false) {
    global $db;

    $query = "SELECT * FROM videos WHERE id = $id";

    if ($result = $db->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $video = array(
                'id' => $row['id'],
                'title' => $row['title'],
                'video' => $row['video'],
                'start' => $row['start'],
                'description' => $row['description'],
                'thumbnail' => $row['thumbnail'],
                'premium' => $row['premium']
            );
        }

        $result->free();
    }

    if(!isset($video))
        return false;

    if($get_related_videos) {
        $video['relatedVideos'] = array();

        $query = "SELECT V2.*
                  FROM videos V1, related_videos, videos V2
                  WHERE V1.id = related_videos.video
                  AND V2.id = related_videos.related_video
                  AND V1.id = $id";

        if ($result = $db->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $related_video = array(
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'thumbnail' => $row['thumbnail'],
                    'premium' => $row['premium']
                );

                $video['relatedVideos'][] = $related_video;
            }

            $result->free();
        }
    }

    if($video['premium'] == 1) {
        unset($video['video']);
        if(($lease = lease_for_video($video['id'])) !== false) {
            $video['video'] =$lease;
        }
    }

    return $video;

    return false;
}
?>
