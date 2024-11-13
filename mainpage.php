<!DOCTYPE html>
<?php
$username = $_COOKIE['u_name'];
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

<nav id="home-item" class="dev_news">
    <h1>Development News</h1>
    <p class="dev_news_message">
        This is where im going to be writing development news about the website
    </p>
</nav>

<nav id="home-item" class="top3">
    <h1>🌟 Top Posts 🌟</h1>
    <?php
$servername = "127.0.0.1";
$dbusername = "root";
$password = "";
$dbname = "emila_baze";

//Izveidot savienojumu
$conn = mysqli_connect($servername, $dbusername, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT messages.image, messages.post_image, messages.title, messages.message, users.username, messages.id FROM messages, users WHERE users.id = user_id ORDER BY messages.likes DESC";

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
            <p id="top_post_message"><?php echo $row["message"]; ?></p><?php
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
    <input type="file" name="p_image" id="p_image" style="float:left;"><br>
	<input class="post_button" type="submit" name="Post_Button" value="Post"><br>
</form>
<br>
<br>
<footer>
<h1>Others Posts</h1>
<!-- List all posts -->
<?php
$servername = "127.0.0.1";
$dbusername = "root";
$password = "";
$dbname = "emila_baze";

//Izveidot savienojumu
$conn = mysqli_connect($servername, $dbusername, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT messages.image, messages.post_image, messages.title, messages.message, users.username, messages.id FROM messages, users WHERE users.id = user_id ORDER BY messages.id DESC";

$result = $conn->query($sql);

//Display all of the posts
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        //Count Likes
        $post_like_ammount = 0;
        $likes_sql = "SELECT likes.message_id, messages.message FROM messages, likes WHERE likes.message_id = messages.id";
        $likes_result = $conn->query($likes_sql);
        if (mysqli_num_rows($likes_result) > 0) {
            while ($likes_row = $likes_result->fetch_assoc()) {
                if ($likes_row["message_id"] == $row["id"]) {
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
        	<p class="post_message"><?php echo $row["message"]; ?></p>
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
