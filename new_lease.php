<?php

require_once 'functions.php';

if(!isset($_GET['id'])) {
    echo json_encode(array(
        'error' => 'No ID specified!'
    ));
    exit();
}

$id = $_GET['id'];

if(($lease = new_lease_for_video($id)) !== false) {
    echo json_encode(array('lease' => $lease));
    exit();
}

echo json_encode(array(
    'error' => 'Invalid ID specified!'
));

?>
