<?php
//data
$user = $_POST["user"];
$title = $_POST["p_title"];
$data = $_POST["p_data"];
//$image = $_POST["p_image"];

echo $data;
//database_info
$login_host = "127.0.0.1";
$login_name = "root";
$login_password = "";
$login_dbname = "emila_baze";

//Connect
$conn = mysqli_connect($login_host, $login_name, $login_password, $login_dbname);

//Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected";

//Find id of user
$users_table_data = "SELECT id, username FROM users";
$users_table_result = $conn->query($users_table_data);
if ($users_table_result->num_rows > 0) {
    // output data of each row
    while ($users_row = $users_table_result->fetch_assoc()) {
        echo "Comparing $user with " . $users_row["username"] . " id: " . $users_row["id"];?><br><?php
if ($users_row["username"] == $user) {
            $username_id = $users_row["id"];
            echo "$user ";
            echo "$username_id";
            break;
        }
    }
} else {
    echo "Couldnt post, not logged in?";
    die();
}

$background_image_dir = "images/";
$post_image_dir = "post-images/";

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

        $stmt = $conn->prepare("INSERT INTO messages (message, user_id, title, image, post_image) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $data, $username_id, $title, $background_file, $post_file);
        $stmt->execute();
        $stmt->close();
    }
} else if (!empty($_FILES["b_image"]["name"])) {
    $file = basename($_FILES["b_image"]["name"]);
    echo "File is :$file";?><br><?php
$filepath = $background_image_dir . $file;

    ?><br><?php echo "full path: $filepath"; ?><br><?php
$filetype = pathinfo($filepath, PATHINFO_EXTENSION);

    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

    if (in_array($filetype, $allowTypes)) {
        move_uploaded_file($_FILES["b_image"]["tmp_name"], $filepath);

        $stmt = $conn->prepare("INSERT INTO messages (message, user_id, title, image) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $data, $username_id, $title, $file);
        $stmt->execute();
        $stmt->close();

        ?><br><?php echo "file was uploaded successfully!"; ?><br><?php
} else {
        echo "Your filetype is not allowed!";
        $stmt = $conn->prepare("INSERT INTO messages (message, user_id, title) VALUES (?,?,?)");
        $stmt->bind_param("sss", $data, $username_id, $title);
        $stmt->execute();
        $stmt->close();
    }
} else if (!empty($_FILES["p_image"]["name"])) {
    $file = basename($_FILES["p_image"]["name"]);
    echo "File is :$file";?><br><?php
$filepath = $post_image_dir . $file;

    ?><br><?php echo "full path: $filepath"; ?><br><?php
$filetype = pathinfo($filepath, PATHINFO_EXTENSION);

    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

    if (in_array($filetype, $allowTypes)) {
        move_uploaded_file($_FILES["p_image"]["tmp_name"], $filepath);

        $stmt = $conn->prepare("INSERT INTO messages (message, user_id, title, post_image) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $data, $username_id, $title, $file);
        $stmt->execute();
        $stmt->close();

        ?><br><?php echo "file was uploaded successfully!"; ?><br><?php
} else {
        echo "Your filetype is not allowed!";
        $stmt = $conn->prepare("INSERT INTO messages (message, user_id, title) VALUES (?,?,?)");
        $stmt->bind_param("sss", $data, $username_id, $title);
        $stmt->execute();
        $stmt->close();
    }
} else {
    $stmt = $conn->prepare("INSERT INTO messages (message, user_id, title) VALUES (?,?,?)");
    $stmt->bind_param("sss", $data, $username_id, $title);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: mainpage.php");