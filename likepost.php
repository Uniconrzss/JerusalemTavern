<?php
$liked_post_id = $_POST['post_id'];
$username = $_POST['username'];
echo "Username is $username    ";

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

$sql = "SELECT id, username FROM users";

$result = $conn->query($sql);

//Find User ID
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        ?><br><?php echo "Comparing " . $row["username"] . " against $username";
        if ($row['username'] == $username) {
            $username_id = $row['id'];
            echo " Found match! Id is $username_id";
        }
    }
} else {
    echo "0 results";
}

//Check if user has already liked the post
$sql = "SELECT user_id, id, message_id FROM likes";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
// output data of each row
    while ($row = $result->fetch_assoc()) {
        if ($row['message_id'] == $liked_post_id && $row['user_id'] == $username_id) {
            //Post has already been liked!
            header('Location: mainpage.php');
            die();

        }
    }
} else {
    echo "0 results";
}

//Like the post

$stmt = $conn->prepare("INSERT INTO likes (message_id, user_id) VALUES (?,?)");
$stmt->bind_param("ss", $liked_post_id, $username_id);

$stmt->execute();
$stmt->close();

//$sql = "UPDATE messages SET likes = ADD(likes,1) WHERE";

$sql = "UPDATE messages SET likes = likes + 1 WHERE (id = {$liked_post_id})";
if (mysqli_query($conn, $sql)) {
    //good
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

$conn->close();
header("Location: mainpage.php");
