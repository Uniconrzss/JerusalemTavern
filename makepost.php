<?php
// DB MODEL SYSTEM
require "model/condb.php";

// FORM POST DATA
$user = $_POST["user"];
$title = $_POST["p_title"];
$data = $_POST["p_data"];
//$image = $_POST["p_image"];

// CONNECT
$conn = conDB();
echo "Connected";

//Find id of user
// OLD COOKIE SYSTEM - RENEW!!
$users_table_data = "SELECT id, username FROM users";
$users_table_result = $conn->query($users_table_data);
if ($users_table_result->num_rows > 0) {
    // output data of each row
    while ($users_row = $users_table_result->fetch_assoc()) {
        echo "Comparing $user with " . $users_row["username"] . " id: " . $users_row["id"];?><br><?php
if ($users_row["username"] == $user) {
            $username_id = (int)$users_row["id"];
            //echo "$user ";
            //echo "$username_id";
            break;
        }
    }
} else {
    echo "Couldnt post, not logged in?";
    die();
}

$background_image_dir = __DIR__."/images/";
$post_image_dir = __DIR__."post-images/";

if (!empty($_FILES["b_image"]["name"]) && !empty($_FILES["p_image"]["name"])) {

    $background_file = basename($_FILES["b_image"]["name"]);
    $background_filepath = $background_image_dir . $background_file;
    $background_filetype = pathinfo($background_filepath, PATHINFO_EXTENSION);

    $post_file = basename($_FILES["p_image"]["name"]);
    $post_filepath = $post_image_dir . $post_file;
    $post_filetype = pathinfo($post_filepath, PATHINFO_EXTENSION);
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (in_array($post_filetype, $allowTypes) && in_array($background_filetype, $allowTypes)) {
        move_uploaded_file($_FILES["b_image"]["tmp_name"], $background_filepath);
        move_uploaded_file($_FILES["p_image"]["tmp_name"], $post_filepath);
        echo($post_filepath);
        $stmt = $conn->prepare("INSERT INTO posts (content, uid, title, image, post_image) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sisss", $data, $username_id, $title, $background_file, $post_file);
        $stmt->execute();
        $stmt->close();
    }
} else if (!empty($_FILES["b_image"]["name"])) {
    $file = basename($_FILES["b_image"]["name"]);
    echo "File is :$file";?><br><?php
$filepath = $background_image_dir . $file;

    ?><br><?php echo "full pathaaaa: $filepath"; ?><br><?php
$filetype = pathinfo($filepath, PATHINFO_EXTENSION);

    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    echo("testestets");
    if (in_array($filetype, $allowTypes)) {
        move_uploaded_file($_FILES["b_image"]["tmp_name"], $filepath);
        echo($filepath);
        echo("testestest");
        $stmt = $conn->prepare("INSERT INTO posts (content, uid, title, image) VALUES (?,?,?,?)");
        $stmt->bind_param("siss", $data, $username_id, $title, $file);
        $stmt->execute();
        $stmt->close();

        ?><br><?php echo "file was uploaded successfully!"; ?><br><?php
} else {
        echo "Your filetype is not allowed!";
        $stmt = $conn->prepare("INSERT INTO posts (content, uid, title) VALUES (?,?,?)");
        $stmt->bind_param("sis", $data, $username_id, $title);
        $stmt->execute();
        $stmt->close();
    }
} else if (!empty($_FILES["p_image"]["name"])) {
    $file = basename($_FILES["p_image"]["name"]);
    echo "File is :$file";?><br><?php
$filepath = $post_image_dir . $file;

    ?><br><?php echo "full pathajkkj: $filepath"; ?><br><?php
$filetype = pathinfo($filepath, PATHINFO_EXTENSION);

    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

    if (in_array($filetype, $allowTypes)) {
        if (isset($_FILES["p_image"]) && $_FILES["p_image"]["error"] == 0) {
            echo("Bumba");
        } else {
            echo "Error: " . $_FILES["p_image"]["error"];
        }
        if(move_uploaded_file($_FILES["p_image"]["tmp_name"], $filepath))
        {
            echo("Viss labi");
            
        }
        else
        {
            echo("Error: ". $_FILES["p_image"]["error"]);
            echo("<br>".$filepath."<br>".$_FILES["p_image"]["tmp_name"]);
        }
        //echo (__DIR__."/".$filepath);
        //echo(sgsd);
        // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        echo("<br>Echo ".gettype($username_id)."<br>");
        $stmt = $conn->prepare("INSERT INTO posts (content, uid, title, post_image) VALUES (?,?,?,?)");
        $stmt->bind_param("siss", $data, $username_id, $title, $file);
        echo ("second testestes2");
        $stmt->execute();
        $stmt->close();
        echo ("second testestes2");

        ?><br><?php echo "file was uploaded successfully!"; ?><br><?php
} else {
        echo "Your filetype is not allowed!";
        $stmt = $conn->prepare("INSERT INTO posts (content, uid, title) VALUES (?,?,?)");
        $stmt->bind_param("sis", $data, $username_id, $title);
        $stmt->execute();
        $stmt->close();
    }
} else {
    $stmt = $conn->prepare("INSERT INTO posts (content, uid, title) VALUES (?,?,?)");
    $stmt->bind_param("sis", $data, $username_id, $title);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: mainpage.php");
