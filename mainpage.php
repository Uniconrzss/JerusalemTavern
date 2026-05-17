<!DOCTYPE html>
<?php
// TODO:
// 1. Pages
// 4. Image



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

	// Check if session exists
	if (!$username)
	{
		header("Location: index.html");
	}
}
else
{
	header("Location: index.html");
}
?>
<html>
    <head>
        <link rel="stylesheet" href="looks/css/mainpage_style.css">
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
        <p class="welcomeMessage">
            <a href="logout.php"><button class="logoutBtn">Logout</button></a>
            <?php
                echo "Welcome $username to the tavern.";
            ?>
        </p>

        <!-- Main Container -->
        <div class="container">
            <div class="container_item">
                <h2>Dev News</h2>
                <div class="smallContainer">
                    <h3>5/17/2026 - Look changes</h3>
                    <p>Changed looks of the main page</p>
                </div>
                <div class="smallContainer">
                    <h3>5/16/2026 - Look changes</h3>
                    <p>Changed looks of the login and register + index pages</p>
                </div>
            </div>

            <!-- Create new post -->
            <div class="container_item">
                <h2>Create a New Post</h2>
                <form id="home-item" class="posting_form" action="makepost.php" method="post" autocomplete="off" enctype="multipart/form-data">
                    <label class="newPostLabel" for="p_title">Post Title</label><br>
                    <input class="newPostTitle"type="text" id="p_title" name="p_title"><br>
                    <br>
                    <label class="newPostLabel" for="p_data">Post Text:</label><br>
                    <textarea id="textarea" class="newPostText" name="p_data" id="p_data" type="text" rows="10" cols="30" required></textarea><br>
                    <br>

                    <!-- <textarea id="p_data"class="new_post_text" style="display:none;" name="p_data" id="p_data" type="text" rows="10" cols="30" required></textarea> -->
                    <!--<label>Background Image: </label>
                    <input type="file" name="b_image" id="b_image"><br> -->

                    <label class="btn" for="p_image">
                        Upload Image
                        <input class="newPostImage" type="file" name="p_image" id="p_image" accept="image/*">
                    </label><br>
                    
                    <input class="btn" type="submit" value="Post"/>
                </form>
            </div>

            <div class="container_item">
                <h2>Top Posts</h2>
                <?php
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
                            <div class="smallContainer">
                                <h3 class="topPostTitle"><?php echo $row["title"]; ?></h3>
                                <h3 class="topPostUser">From: <?php echo $row["username"]; ?></h3>
                                <p class="topPostMessage"><?php echo $row["content"]; ?></p>
                            </div>
                            <?php
                        }
                        $curr_post = $curr_post + 1;
                    }
                }
                ?>
            </div>
        </div>
        <br>
        <br>
        <br>
        <footer>
            <h1>Others Posts</h1>
            <!-- List posts -->

            <?php
            // Page Logic
            $currentPage = 0;
            if (isset($_GET["page"]))
            {
                $currentPage = $_GET["page"];
            }

            for ($i = $currentPage - 3; $i <= $currentPage + 3; $i++)
            {
                if ($i >= 0)
                {
                    if ($i == $currentPage)
                    {
                        echo("<a class='currentPageNumber' href='?page=".$i."'>".$i."</a>");
                    }
                    else
                    {
                        echo("<a class='pageNumber' href='?page=".$i."'>".$i."</a>");
                    }
                }
            }

            // CONNECT
            $conn = conDB();

            $sql = "SELECT posts.image, posts.post_image, posts.title, posts.content, users.username, posts.id FROM posts, users WHERE users.id = uid ORDER BY posts.post_date DESC LIMIT 5 OFFSET ".(5*$currentPage);

            $result = $conn->query($sql);

            // Display all of the posts
            if ($result->num_rows > 0) 
            {
                // output data of each row
                while ($row = $result->fetch_assoc()) 
                {
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
                    } 
                    else 
                    {
                        $post_like_ammount = 0;
                    }

                    $imageURL = 'images/' . $row['image'];
                    $post_imageURL = 'post-images/' . $row['post_image'];

                    //Display Post
                    ?>
                    <br>
                    <div class="post">
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
                        <p class="post_user">From: <?php echo $row["username"]; ?></p>
                        <?php

                    if ($post_imageURL != "post-images/") 
                    {
                        ?> <img class="post_image" src="<?php echo $post_imageURL; ?>"> <?php
                    }
                    ?>
                        
                    </div>
                    <?php
                }
            } 
            else 
            {
                echo "0 results";
            }
            $conn->close();
            #echo "<h1>testins</h1>";
            ?>
        </footer>
    </body>
</html>
