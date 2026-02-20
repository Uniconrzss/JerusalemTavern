<!DOCTYPE html>
<?php
// DB MODEL SYSTEM
require "model/condb.php";

if (isset($_COOKIE["u_cookie"]))
{
	$usercookie = $_COOKIE['u_cookie'];
	// Validate Cookie
	$conn = conDB();

	$stmt = $conn->prepare("SELECT users.username FROM sessions, users WHERE users.id = sessions.uid AND sessions.cookie = ?");
	$stmt->bind_param("s", $usercookie);
	$stmt->execute();
	$stmt->bind_result($username);
	$stmt->fetch();
	$stmt->close();

	// Check if 
}
else
{
	header("Location: index.html");
}
?>
<html>
<head>
	<link rel="stylesheet" href="mainpage_style.css">
    <title>The Jerusalem Tavern > Home</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <script>
        function search(ele)
        {
            let key = event.key;
            if(event.key === 'Enter')
            {
                document.getElementById("p_data").value +="<br>";
                alert(document.getElementById("p_data").value);
            }
            else if (key.length < 2)
            {
                document.getElementById("p_data").value += key;
            }
            else if (key == "Backspace" && document.getElementById("p_data").value.slice(-3,-1) === "br>")
            {
                document.getElementById("p_data").value = document.getElementById("p_data").value.slice(0,-7);
            }
            else if (key == "Backspace")
            {
            }
        }
    </script>
</head>
<body>
<h1>Jerusalem Tavern</h1>
<p id="welcome_msg"><?php
echo "Welcome $username to the tavern.";
?></p><br>
<a href="logout.php"><button class="post_button">Logout</button></a>
<br>
<nav id="home-item" class="dev_news">
    <h1>Dev News</h1>
    <p class="dev_news_message">
        This is where im going to be writing development news about the website
    </p>
</nav>

<nav id="home-item" class="top3">
    <h1>Top Posts</h1>
<?php
// CONNECT

$sql = "SELECT posts.image, posts.post_image, posts.title, posts.content, users.username, posts.id FROM posts, users WHERE users.id = uid ORDER BY posts.likes DESC";

$result = $conn->query($sql);
$curr_post = 0;
//Display all of the posts
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($curr_post == 3) {
            break;
        } else {
            ; //Display Post
            ?>
            <h3 id="top_post_title"><?php echo $row["title"]; ?></h3>
            <h3>From: <?php echo $row["username"]; ?></h3>
            <p id="top_post_message"><?php echo $row["content"]; ?></p><?php
}
        $curr_post = $curr_post + 1;

    }
}
?>
</nav>

<!-- Create new post -->
<form id="home-item" class="posting_form" action="makepost.php" method="post" autocomplete="off" enctype="multipart/form-data">
    <h2>Create a New Post</h2>
	<input type="textarea" style="display:none;" name="user" value="<?php echo $username; ?>"/>
    <!-- <input type="hidden" name="p_data" id="p_data" value=" "/> -->
	<label class="new_post_label" for="p_title">Title</label><br>
	<input class="new_post_title"type="text" id="p_title" name="p_title"><br><br>
	<label class="new_post_label" for="p_data">Post</label><br>
	<textarea id="textarea"class="new_post_text" name="p_data" id="p_data" type="text" rows="10" cols="30" required></textarea><br>
    <!-- <textarea id="p_data"class="new_post_text" style="display:none;" name="p_data" id="p_data" type="text" rows="10" cols="30" required></textarea> -->
    <label>Background Image: </label>
    <input type="file" name="b_image" id="b_image"><br>
    <label>Post Image: </label>
    <input type="file" name="p_image" id="p_image"><br>
    <input class="post_button" type="submit" name="Post_Button" value="Post"/><br>
</form>
<br>
<br>
<br>
<footer>
<h1>Others Posts</h1>
<!-- List all posts -->
<?php
// CONNECT
$conn = conDB();

$sql = "SELECT posts.image, posts.post_image, posts.title, posts.content, users.username, posts.id FROM posts, users WHERE users.id = uid ORDER BY posts.id DESC";

$result = $conn->query($sql);

//Display all of the posts
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        //Count Likes
        $post_like_ammount = 0;
        $likes_sql = "SELECT likes.pid, posts.content FROM posts, likes WHERE likes.pid = posts.id";
        $likes_result = $conn->query($likes_sql);
        if (mysqli_num_rows($likes_result) > 0) {
            while ($likes_row = $likes_result->fetch_assoc()) {
                if ($likes_row["pid"] == $row["id"]) {
                    $post_like_ammount += 1;
                }
            }
        } else {
            $post_like_ammount = 0;
        }
        $imageURL = 'images/' . $row['image'];
        $post_imageURL = 'post-images/' . $row['post_image'];
        //Display Post
        ?>
        <br>
        <div class="post" style="background-image: url('<?php echo $imageURL; ?>');">
            <h3 id="post_title"><?php echo $row["title"]; ?></h3>
            <h3 id="likes"><?php echo $post_like_ammount; ?> Likes</h3>
            <form id="like_button" action="likepost.php" method="post">
            	<input type="hidden" name="post_id" value="<?php echo $row["id"]; ?>">
            	<input type="hidden" name="username" value="<?php echo $username; ?>">
            	<input id="emoji_button" type="submit" name="like_button" value="👍">
                <a>
                </a>
            </form>
        	<p class="post_message"><?php echo $row["content"]; ?></p>
            <?php

        if ($post_imageURL != "post-images/") {
            ?> <img class="post_image" src="<?php echo $post_imageURL; ?>"> <?php
}
        ?>

        	<p class="post_user">From: <?php echo $row["username"]; ?></p><br>
    	</div>
    	<?php
}
} else {
    echo "0 results";
}
$conn->close();
#echo "<h1>testins</h1>";
?>
</footer>
</body>
</html>
