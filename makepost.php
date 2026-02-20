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
$post_image_dir = __DIR__."/post-images/";

function checkft($ftype)
{
	echo("11");
	$allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp');
	if (in_array($ftype, $allowTypes))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function checks()
{
	$bcheck = 0;
	if (!empty($_FILES["b_image"]["name"])) {
		$background_file = basename($_FILES["b_image"]["name"]);
    		$background_filepath = __DIR__."/images/" . $background_file;
    		$background_filetype = pathinfo($background_filepath, PATHINFO_EXTENSION);
		echo("1");
		// Check if background Image filetype is valid.
		if (checkft($background_filetype))
		{
			echo("2");
			// Check if background image name already exists.
			//if (!file_exists($background_filepath))
			//{
				echo("3<br>".$_FILES['b_image']['size']."<br>");
				// Check filesize of the background image.
				if (!($_FILES["b_image"]["size"] > 50000))
				{
					echo("4");
					// Check if its a real image.
					if (getimagesize($_FILES["b_image"]["tmp_name"]))
					{
						echo("5");
						// Image is validated!
						$bcheck = 1;
					}
				}
			//}
		}
	}
	else
	{
		$bcheck = 1;
	}

	$pcheck = 0;
	if (!empty($_FILES["p_image"]["name"])) {
    		$post_file = basename($_FILES["p_image"]["name"]);
    		$post_filepath = __DIR__."/post-images/". $post_file;
    		$post_filetype = pathinfo($post_filepath, PATHINFO_EXTENSION);
		echo("p1");
		// Check if post Image filetype is valid.
                if (checkft($post_filetype))
		{       
			echo("p2");
                        // Check if post image name already exists.
                        //if (!file_exists($post_filepath))
			//{       
				echo("p3");
                                // Check filesize of the post image.
                                if (!($_FILES["p_image"]["size"] > 50000))
				{       
					echo("p4");
                                        // Check if its a real image.
                                        if (getimagesize($_FILES["p_image"]["tmp_name"]))
					{       
						echo("p5");
                                                // Image is validated!
                                                $pcheck = 1;
                                        }       
                                }       
                        //}       
                }  
	}
	else
	{
		$pcheck = 1;
	}
	echo("<br>bcheck:".$bcheck);
	echo("<br>pcheck:".$pcheck);
	if ($bcheck = 1 && $pcheck == 1)
	{
		return true;
	}
	else
	{
		return false;
	}
}

if (!empty($_FILES["b_image"]["name"]) && !empty($_FILES["p_image"]["name"])) {

    $background_file = hash('sha256',strval(rand(0, 999))).basename($_FILES["b_image"]["name"]);
    $background_filepath = $background_image_dir . $background_file;
    $background_filetype = pathinfo($background_filepath, PATHINFO_EXTENSION);

    $post_file = hash('sha256',strval(rand(0, 999))).basename($_FILES["p_image"]["name"]);
    $post_filepath = $post_image_dir . $post_file;
    $post_filetype = pathinfo($post_filepath, PATHINFO_EXTENSION);

    if (checks()) {
	// Move files.    
	move_uploaded_file($_FILES["b_image"]["tmp_name"], $background_filepath);
        move_uploaded_file($_FILES["p_image"]["tmp_name"], $post_filepath);

	// Insert into DB.
        $stmt = $conn->prepare("INSERT INTO posts (content, uid, title, image, post_image) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sisss", $data, $username_id, $title, $background_file, $post_file);
        $stmt->execute();
        $stmt->close();
    }
} else if (!empty($_FILES["b_image"]["name"])) { // Just background Image
	
    $file = hash('sha256',strval(rand(0, 999))).basename($_FILES["b_image"]["name"]);
    $filepath = $background_image_dir . $file;
    $filetype = pathinfo($filepath, PATHINFO_EXTENSION);

    if (checks()) {
	
	// Move file.
        move_uploaded_file($_FILES["b_image"]["tmp_name"], $filepath);

	// Insert into DB
        $stmt = $conn->prepare("INSERT INTO posts (content, uid, title, image) VALUES (?,?,?,?)");
        $stmt->bind_param("siss", $data, $username_id, $title, $file);
        $stmt->execute();
        $stmt->close();

        ?><br><?php echo "file was uploaded successfully!"; ?><br><?php
    } else {
        // FAILED CHECKS!
        $conn->close();
	// header("Location: mainpage.php");
    }
} else if (!empty($_FILES["p_image"]["name"])) { // Just post image

    $file = hash('sha256',strval(rand(0, 999))).basename($_FILES["p_image"]["name"]);
    $filepath = $post_image_dir . $file;
    $filetype = pathinfo($filepath, PATHINFO_EXTENSION);

    if (checks()) {
	// Move file.    
	move_uploaded_file($_FILES["p_image"]["tmp_name"], $filepath);

	// Insert into DB.
        $stmt = $conn->prepare("INSERT INTO posts (content, uid, title, post_image) VALUES (?,?,?,?)");
        $stmt->bind_param("siss", $data, $username_id, $title, $file);
        $stmt->execute();
        $stmt->close();

        ?><br><?php echo "file was uploaded successfully!"; ?><br><?php
    } else {
    	// FAILED CHECKS!
	$conn->close();
    	// header("Location: mainpage.php");	
    }
} else { // No images uploaded.

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO posts (content, uid, title) VALUES (?,?,?)");
    $stmt->bind_param("sis", $data, $username_id, $title);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: mainpage.php");
