<?php
// DB MODEL SYSTEM
require "model/condb.php";

// USER DATA
$liked_post_id = $_POST['post_id'];

//Connect
$conn = conDB();
echo "Connected";

// LOGIN SYSTEM UPDATED
if (isset($_COOKIE["u_cookie"]))
{
        $usercookie = $_COOKIE['u_cookie'];
        // Validate Cookie
        
        $stmt = $conn->prepare("SELECT users.username, users.id FROM sessions, users WHERE users.id = sessions.uid AND sessions.cookie = ?");
        $stmt->bind_param("s", $usercookie);
        $stmt->execute();
        $stmt->bind_result($username, $username_id);
        $stmt->fetch();
        $stmt->close();

        // Check if session exists
        if (!$username)
	{
		// Session doesnt exist, return to index.
                header("Location: index.html");
	}
}
else
{
	// No user cookie at all!
        header("Location: index.html");
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
