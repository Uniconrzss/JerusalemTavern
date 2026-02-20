<?php
// DB MODEL SYSTEM
require "model/condb.php";

// USER DATA
$liked_post_id = $_POST['post_id'];

// FROM OLD COOKIE SYSTEM - RENEW!!
$username = $_POST['username'];
echo "Username is $username    ";

//Connect
$conn = conDB();
echo "Connected";

$sql = "SELECT id, username FROM users";

$result = $conn->query($sql);

//Find User ID
// FROM OLD COOKIE SYSTEM - RENEW!!
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
$sql = "SELECT uid, id, pid FROM likes";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
// output data of each row
    while ($row = $result->fetch_assoc()) {
        if ($row['pid'] == $liked_post_id && $row['uid'] == $username_id) {
            //Post has already been liked!
            header('Location: mainpage.php');
            die();

        }
    }
} else {
    echo "0 results";
}

//Like the post

$stmt = $conn->prepare("INSERT INTO likes (pid, uid) VALUES (?,?)");
$stmt->bind_param("ss", $liked_post_id, $username_id);

$stmt->execute();
$stmt->close();

//$sql = "UPDATE messages SET likes = ADD(likes,1) WHERE";

$sql = "UPDATE posts SET likes = likes + 1 WHERE (id = {$liked_post_id})";
if (mysqli_query($conn, $sql)) {
    //good
	echo("Post updated");
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

$conn->close();
header("Location: mainpage.php");
