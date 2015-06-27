<?php

$db = new mysqli("localhost", "qucentis_drop", "branjtrucid", "qucentis_drop");

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>