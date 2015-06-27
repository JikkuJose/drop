<?php
    require_once "functions.php";

    $a = explode("/", $_SERVER['REQUEST_URI']);
    $a = array_pop($a);
    $a = explode("?", $a);
    $script_name = $a[0];

    if($script_name == "edit.php" && !isset($_GET['id']) && !isset($_POST['action'])) {
        header("Location: add.php");
    }

    $video = $title = $description = $start = "";
    $notification_type = $notification = ""; $error = false;
    $action = isset($_POST['action']) ? $_POST['action']: "";

    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
    if($id) {

        if(($v = video($id)) === false) {
            $notification_type = "error"; $notification = "Invalid Video!!"; $error = true;
        }
        else {
            $video = $v['video'];
            $title = $v['title'];
            $description = $v['description'];
            $start = $v['start'];
        }
    }

    if(isset($_POST['action'])) {
        $video = $_POST['video'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $start = (int) $_POST['start'];
    }

    if(!$error) {
        if($action == "Edit") {
            $query = "UPDATE videos SET ".
                     "video = \"" . addslashes($video) . "\", ".
                     "title = \"" . addslashes($title) . "\", ".
                     "description = \"" . addslashes($description) . "\", ".
                     "start = $start ".
                     "WHERE id = $id";

             $result = $db->query($query);
             header("Location: edit.php?id=$id");

        }
        else if($action == "Add") {
            $query = "INSERT INTO videos(video, title, description, start) VALUES(".
                     "\"" . addslashes($video) . "\", ".
                     "\"" . addslashes($title) . "\", ".
                     "\"" . addslashes($description) . "\", ".
                     "$start)";

            $result = $db->query($query);
            $id = $db->insert_id;
            header("Location: edit.php?id=$id");
        }
    }

    if($action == "") {
        $action = (isset($id) && $id) ? "Edit" : "Add";
    }

?>

<style>
.var  {
    width: 100px;
}

.value input, .value textarea {
    width: 400px;
}

#action {
    margin: 30px 0px;
}

.error {
    color: red;
}

.success {
    color: green;
}
</style>

<title><?php echo "$action Video";?></title>

<div id="notifications">
<?php if($notification_type) { ?>
    <span class="<?php echo $notification_type;?>"><?php echo $notification;?></span>
<? } ?>
</div>

<?php if($error) exit(); ?>

<form action="edit.php" method="POST">
<?php if(isset($_REQUEST['id'])) { ?>
    <input type="hidden" id="id" name="id" value="<?php echo $id;?>">
<?php } ?>
    <table>
        <tr>
            <td class="var">Video ID</td>
            <td class="value"><input id="video" name="video" value="<?php echo $video;?>"></td>
        </tr>
        <tr>
            <td class="var">Title</td>
            <td class="value"><input id="title" name="title"  value="<?php echo $title;?>"></td>
        </tr>
        <tr>
            <td class="var">Description</td>
            <td class="value">
                <textarea id="description" name="description"><?php echo $description;?></textarea>
            </td>
        </tr>
        <tr>
            <td class="var">Start</td>
            <td class="value"><input id="start" name="start" value="<?php echo $start;?>"></td>
        </tr>
    </table>
    <input type="submit" name="action" id="action" value="<?php echo $action;?>">
</form>