<?php

/* Importing the Result class from the LDAP namespace. */
use LDAP\Result;

/**
 * It takes a string, trims it, strips slashes, and converts special characters to HTML entities
 * param data The data to be validated.
 * return the data that has been trimmed, stripped of slashes, and converted to html special
 * characters.
 */
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * It sets the session variables for the user who just signed up.
 * param conn The connection to the database
 */
function setSession($conn){
    $last_id = mysqli_insert_id($conn);
    $sql = "SELECT * FROM user WHERE id= '$last_id'";
    $result = mysqli_query($conn, $sql);
    $details= mysqli_fetch_array($result);
    mysqli_free_result($result);
    $_SESSION["user-id"] = $details["id"];
    $_SESSION["username"] = $details["username"];
    $_SESSION["user-email"] = $details["email"];
    $_SESSION["user-type"] = $details["type"];
    $_SESSION["user-dp"] = $details["image"];
    if(preg_match('/\S/', $details["about"])){
        $_SESSION["user-about"] = $details["about"];
    }
    if(preg_match('/(https:\/\/twitter.com\/(?![a-zA-Z0-9_]+\/)([a-zA-Z0-9_]+))/i', $details["twitter"])){
        $_SESSION["user-twitter"] = $details["twitter"];
    }
    if(preg_match('/(?:https?:\/\/)?(?:www\.)?(?:facebook|fb|m\.facebook)\.(?:com|me)\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-\.]+)(?:\/)?/i', $details["facebook"])){
        $_SESSION["user-facebook"] = $details["facebook"];
    }
    if(preg_match('/(?:(?:http|https):\/\/)?(?:www.)?(?:instagram.com|instagr.am|instagr.com)\/(\w+)/i', $details["instagram"])){
        $_SESSION["user-instagram"] = $details["instagram"];
    }
    if(preg_match('/^https?:\/\/(?:www\.)?tiktok\.com\/@((?!.*\.\.)(?!.*\.$)[^\W][\w.]{2,24})/i', $details["tiktok"])){
        $_SESSION["user-tiktok"] = $details["tiktok"];
    }
    $_SESSION["user-joined"] = $details["joined"];
}
/**
 * It sets the session variables for the user.
 * param id The user's id
 */
function loginSession($id){
    $_SESSION["user-id"] = $id["id"];
    $_SESSION["username"] = $id["username"];
    $_SESSION["user-email"] = $id["email"];
    $_SESSION["user-type"] = $id["type"];
    $_SESSION["user-dp"] = $id["image"];
    if(preg_match('/\S/', $id["about"])){
        $_SESSION["user-about"] = $id["about"];
    }
    if(preg_match('/(https:\/\/twitter.com\/(?![a-zA-Z0-9_]+\/)([a-zA-Z0-9_]+))/i', $id["twitter"])){
        $_SESSION["user-twitter"] = $id["twitter"];
    }
    if(preg_match('/(?:https?:\/\/)?(?:www\.)?(?:facebook|fb|m\.facebook)\.(?:com|me)\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-\.]+)(?:\/)?/i', $id["facebook"])){
        $_SESSION["user-facebook"] = $id["facebook"];
    }
    if(preg_match('/(?:(?:http|https):\/\/)?(?:www.)?(?:instagram.com|instagr.am|instagr.com)\/(\w+)/i', $id["instagram"])){
        $_SESSION["user-instagram"] = $id["instagram"];
    }
    if(preg_match('/^https?:\/\/(?:www\.)?tiktok\.com\/@((?!.*\.\.)(?!.*\.$)[^\W][\w.]{2,24})/i', $id["tiktok"])){
        $_SESSION["user-tiktok"] = $id["tiktok"];
    }
    $_SESSION["user-joined"] = $id["joined"];
}
/**
 * It uploads the dp to the server and updates the database with the new dp name.
 * param image The image file
 * param conn The connection to the database
 */
function uploadDP($image, $conn){
    if(isset($image["name"])){
        $id = $_SESSION["user-id"];
        $imageName = $image["name"];
        $imageSize = $image["size"];
        $tmpName = $image["tmp_name"];
        $target_dir = "assets/images/dp/";
        // Image validation
        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $imageName);
        $imageExtension = strtolower(end($imageExtension));
        if (!in_array($imageExtension, $validImageExtension)){
            $_SESSION['message'] = "Invalid Image Extension!";
        }
        elseif ($imageSize > 1200000){
            $_SESSION['message'] = "Image Size Is Too Large!";
        }
        else{
            // Generate new image name
            $newImageName = date("Y.m.d") ."-" . $id . "-" . date("h.i"); 
            $newImageName .= '.' . $imageExtension;
            $query = "UPDATE user SET image = '$newImageName' WHERE id = $id";
            mysqli_query($conn, $query);
            // Upload image 
            move_uploaded_file($tmpName, $target_dir . $newImageName);
            $oldDP = $_SESSION["user-dp"];
            // if user had an image then delete it
            if($oldDP != "def.jpg"){
                unlink($target_dir.$oldDP);
            }
            $_SESSION["user-dp"] = $newImageName;
        }
    }
}
// upload Gallery image
function uploadImage($image, $dimension, $conn){
    if(isset($image["name"])){
        $id = $_SESSION["user-id"];
        $imageName = $image["name"];
        $imageSize = $image["size"];
        $tmpName = $image["tmp_name"];
        $target_dir = "assets/images/gallery/";
        // Image validation
        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $imageName);
        $imageExtension = strtolower(end($imageExtension));
        if (!in_array($imageExtension, $validImageExtension)){
            $_SESSION['message'] = "Invalid Photo Extension!";
        }
        elseif ($imageSize > 12000000){
            $_SESSION['message'] = "Photo Size Is Too Large!";
        }
        else{
            // Generate new image name
            $newImageName = bin2hex(random_bytes(6)) . $id . "-" .date("Y.m.d"); 
            $newImageName .= '.' . $imageExtension;
            $query = "INSERT INTO `gallery`(`image`, `user-id`, `dimension`) VALUES ('$newImageName','$id','$dimension')";
            $result = mysqli_query($conn, $query);
            if ($result){
                // Upload image 
                move_uploaded_file($tmpName, $target_dir . $newImageName);
            } else{
                $_SESSION['message'] = "Error uploading photo!";
            }
            
        }

    }
}

// It takes a timestamp and returns the date.
function explodeDate($stamp){
    $date = explode(' ', $stamp);
    return $date[0];
}

// It takes a timestamp and returns the time portion of it
function getTime($stamp){
    $time = explode(' ', $stamp);
    return end($time);
}

// get dimension classes
function dimension($dimension){
    $dimension = explode('x', $dimension);
    $classes = 'h-' . $dimension[0] .  ' w-' . $dimension[1];
    return $classes;
}

// Get user name from id
function getName($conn, $id){
    $sql = "SELECT `username` FROM `user` WHERE `id` = $id";
    $result = mysqli_query($conn, $sql);
    $result1 = mysqli_fetch_row($result);
    mysqli_free_result($result);
    return $result1[0];
}
// Get user email from id
function getEmail($conn, $id){
    $sql = "SELECT `email` FROM `user` WHERE `id` = $id";
    $result = mysqli_query($conn, $sql);
    $result1 = mysqli_fetch_row($result);
    mysqli_free_result($result);
    return $result1[0];
}
// update Gallery image
function updateImage($id, $image, $conn){
    if(isset($image["name"])){
        $oldImage = imageName($conn, $id);
        $imageName = $image["name"];
        $imageSize = $image["size"];
        $tmpName = $image["tmp_name"];
        $target_dir = "assets/images/gallery/";
        // Image validation
        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $imageName);
        $imageExtension = strtolower(end($imageExtension));
        if (!in_array($imageExtension, $validImageExtension)){
            $_SESSION['message'] = "Invalid Photo Extension!";
        }
        elseif ($imageSize > 12000000){
            $_SESSION['message'] = "Photo Size Is Too Large!";
        }
        else{
            // Generate new image name
            $newImageName = bin2hex(random_bytes(6)) . $id . "-" .date("Y.m.d"); 
            $newImageName .= '.' . $imageExtension;
            $query = "UPDATE `gallery` SET `image`='$newImageName' WHERE `id`=$id";
            $result = mysqli_query($conn, $query);
            if ($result){
                // Upload image 
                move_uploaded_file($tmpName, $target_dir . $newImageName);
                unlink($target_dir.$oldImage);
            } else{
                $_SESSION['message'] = "Error uploading photo!";
            }
            
        }

    }
    $src = $target_dir . imageName($conn, $id);
    return $src;
}
// delete image 
function deleteImage($conn, $id){
    $sql = "DELETE FROM `gallery` WHERE `id` = $id";
    $result = mysqli_query($conn, $sql);
    if ($result){
        return true;
    }else {
        return false;
    }
}
//  get image name from id
function imageName($conn, $id){
    $sql = "SELECT `image` FROM `gallery` WHERE `id` = $id";
    $result = mysqli_query($conn, $sql);
    $result1 = mysqli_fetch_row($result);
    mysqli_free_result($result);
    return $result1[0];
}
// delete feedback 
function deleteFeedback($conn, $id){
    $sql = "DELETE FROM `feedback` WHERE `id` = $id";
    $result = mysqli_query($conn, $sql);
    if ($result){
        return true;
    }else {
        return false;
    }
}

// Get user profile info
function getuserprofile($uid){
    global $conn;
    $profile = array();
    $sql = "SELECT  `id`, `username`, `email`, `image`, `active`, `type`, `joined`, `about`, `twitter`, `facebook`, `instagram`, `tiktok`  FROM `user` WHERE `id`='$uid' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 0){
        $_SESSION['message'] = "user not found!";
        return getuserprofile($_SESSION['user-id']);

    }
    else{
        $row=mysqli_fetch_array($result);
        $profile["id"] = $row["id"];
        $profile["name"] = $row["username"];
        $profile["email"] = $row["email"];
        $profile["type"] = $row["type"];
        $profile["dp"] = $row["image"];
        if(preg_match('/\S/', $row["about"])){
            $profile["about"] = $row["about"];
        }
        if(preg_match('/(https:\/\/twitter.com\/(?![a-zA-Z0-9_]+\/)([a-zA-Z0-9_]+))/i', $row["twitter"])){
            $profile["twitter"] = $row["twitter"];
        }
        if(preg_match('/(?:https?:\/\/)?(?:www\.)?(?:facebook|fb|m\.facebook)\.(?:com|me)\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-\.]+)(?:\/)?/i', $row["facebook"])){
            $profile["facebook"] = $row["facebook"];
        }
        if(preg_match('/(?:(?:http|https):\/\/)?(?:www.)?(?:instagram.com|instagr.am|instagr.com)\/(\w+)/i', $row["instagram"])){
            $profile["instagram"] = $row["instagram"];
        }
        if(preg_match('/^https?:\/\/(?:www\.)?tiktok\.com\/@((?!.*\.\.)(?!.*\.$)[^\W][\w.]{2,24})/i', $row["tiktok"])){
            $profile["tiktok"] = $row["tiktok"];
        }
        $profile["joined"] = $row["joined"];
        mysqli_free_result($result);
    }
    return $profile;
}
// Change user password 
function changeUpassword($OldPwd, $NewPwd, $ConPwd){
    global $conn;
    $Uid = $_SESSION['user-id'];
    // Get password harsh
    $password = md5($OldPwd);
    // confirm password before anithing else
    $sql = "SELECT * FROM user WHERE id='$Uid' AND password='$password' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    /* Checking if the new password and confirm password are the same. If they are, it will hash the
    password and update the database. */
    if(mysqli_num_rows($result) == 0){
        $_SESSION['message'] = "Incorrect password!!!";
    }else{
        mysqli_free_result($result);
        // Handle password change
        if($NewPwd == $ConPwd){
        $newPassword = md5($NewPwd);
        $sql = "UPDATE user SET password='$newPassword' WHERE id='$Uid'";
        $result = mysqli_query($conn, $sql);
        if($result){
            $_SESSION['success'] = "Password changed Successfully.";
        }else{
            $_SESSION['message'] = "Error changing password!!!";
        }
        }else{
        $_SESSION['message'] = "passwords don't match!!!";
        }
    }
}
// Update user information
function updateUserInfo($data){
    global $conn;
    $Uid = $_SESSION['user-id'];
    $name = $data['name'];
    $about = $data['about'];
    $email = $data['email'];
    $role = $data['role'];
    $twitter = $data['twitter'];
    $facebook = $data['facebook'];
    $instagram = $data['instagram'];
    $tiktok = $data['tiktok'];
    $sql = "UPDATE `user` SET `username`='$name',`email`='$email',`about`='$about',`twitter`='$twitter',`instagram`='$instagram',`facebook`='$facebook',`tiktok`='$tiktok',`type`='$role' WHERE `id`='$Uid'";
    $result = mysqli_query($conn, $sql);
    if($result){
        $_SESSION['success'] = "Profile updated Successfully.";
        $_SESSION["username"] = $name;
        $_SESSION["user-email"] = $email;
        $_SESSION["user-type"] = $role;
        $_SESSION["user-about"] = $about;
        $_SESSION["user-twitter"] = $twitter;
        $_SESSION["user-facebook"] = $facebook;
        $_SESSION["user-instagram"] = $instagram;
        $_SESSION["user-tiktok"] = $tiktok;
    }else{
        $_SESSION['message'] = "Error updating password!!!";
    }
}
// Delete user
function deleteUser($uid){
    global $conn;
    if($_SESSION["user-type"] == 323 || $_SESSION["user-id"] == $uid){
        // Delete posted images from db
        $sql = "SELECT `image` FROM `gallery` WHERE `user-id` = '$uid'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $Inames = mysqli_fetch_all($result);
            mysqli_free_result($result);
            foreach($Inames as $name){
                unlink("assets/images/gallery/".$name[0]);
            }
        }
        // Delete dp from db
        $sql = "SELECT `image` FROM `user` WHERE `id` = '$uid'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $dp = mysqli_fetch_all($result);
            mysqli_free_result($result);
            if($dp[0][0] != 'def.jpg'){
                unlink("assets/images/dp/".$dp[0][0]);
            }
        }
        // Delete user
        $sql = "DELETE FROM `user` WHERE `id` = $uid";
        $result = mysqli_query($conn, $sql);
        if ($result){
            session_unset(); 
            session_destroy();
            return true;
        }else {
            return false;
        }
    }else {
        return false;
    }

}
// Deactivate user
function deactivateUser($uid){
    global $conn;
    if($_SESSION["user-type"] == 323 || $_SESSION["user-id"] == $uid){
        // Deactivate user
        $sql = "UPDATE `user` SET `active`='2'WHERE `id`='$uid'";
        $result = mysqli_query($conn, $sql);
        if ($result){
            session_unset(); 
            session_destroy();
            return true;
        }else {
            return false;
        }
    }else {
        return false;
    }
}
// Request editor stats
function EDrequest($uid){
    global $conn;
    if($_SESSION["user-id"] == $uid){
        // Check if request was made 
        $sql ="SELECT * FROM `editorRequest` WHERE `user-id`= '$uid'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) == 0){
            mysqli_free_result($result);
            $sql = "INSERT INTO `editorRequest`(`user-id`) VALUES('$uid')";
            $result = mysqli_query($conn, $sql);
        }else{
            $date = date('Y-m-d H:i:s');
            $sql = "UPDATE `editorRequest` SET `date`='$date' WHERE `user-id`='$uid'";
            $result = mysqli_query($conn, $sql);
        }
        if ($result){
            return true;
        }else {
            return false;
        }
    }else {
        return false;
    }
}
// make editor
function makeEditor($uid){
    global $conn;
    if($_SESSION["user-type"] == 323){
        // Update status
        $sql = "UPDATE `user` SET `type`='222' WHERE `id`='$uid'";
        $result = mysqli_query($conn, $sql);
        if ($result){
            // Delete request
            $sql = "DELETE FROM `editorRequest` WHERE `user-id` = $uid";
            $result = mysqli_query($conn, $sql);
        }else {
            $result = false;
        }
        if ($result){
            return true;
        }else {
            return false;
        }
    }else {
        return false;
    }
}
// delete request
function deleteRequest($uid){
    global $conn;
    if($_SESSION["user-type"] == 323){
        // Delete request
        $sql = "DELETE FROM `editorRequest` WHERE `user-id` = $uid";
        $result = mysqli_query($conn, $sql);
        if ($result){
            return true;
        }else {
            return false;
        }
    }else {
        return false;
    }
}

// Check if user is following an id
function isFollowing($Uid){
    global $conn;
    $user = $_SESSION['user-id'];
    $sql ="SELECT * FROM `follows` WHERE `follower`= '$user' AND `following`= '$Uid'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) != 0){
        mysqli_free_result($result);
        return true;
    }else{
        return false;
    }
}
// Unfollow user
function followUser($Uid){
    global $conn;
    $user = $_SESSION['user-id'];
    $sql = "INSERT INTO `follows`(`follower`, `following`) VALUES('$user', '$Uid')";
    $result = mysqli_query($conn, $sql);
    if ($result){
        return true;
    }else {
        return false;
    }
}
// Unfollow user
function unfollowUser($Uid){
    global $conn;
    $user = $_SESSION['user-id'];
    $sql = "DELETE FROM `follows` WHERE `following`= '$Uid' AND `follower`='$user'";
    $result = mysqli_query($conn, $sql);
    if ($result){
        return true;
    }else {
        return false;
    }
}
// Get following count
function followingCount($uid){
    global $conn;
    $sql ="SELECT count(*) AS total FROM `follows` WHERE `follower`= '$uid'";
	$result = mysqli_query($conn, $sql);
    $data=mysqli_fetch_assoc($result);
	mysqli_free_result($result);
	return $data['total'];
}
// Get follower count
function followerCount($uid){
    global $conn;
    $sql ="SELECT count(*) AS total FROM `follows` WHERE `following`= '$uid'";
	$result = mysqli_query($conn, $sql);
    $data=mysqli_fetch_assoc($result);
	mysqli_free_result($result);
	return $data['total'];
}
// Get post count from user id
function postCount($uid){
    global $conn;
    $sql ="SELECT count(*) AS total FROM `gallery` WHERE `user-id`= '$uid'";
	$result = mysqli_query($conn, $sql);
    $data=mysqli_fetch_assoc($result);
	mysqli_free_result($result);
	return $data['total'];
}
// Get likes count from image id
function getLikes($pid){
    global $conn;
    $sql ="SELECT count(*) AS total FROM `likes` WHERE `pic-id`= '$pid'";
	$result = mysqli_query($conn, $sql);
    $data=mysqli_fetch_assoc($result);
	mysqli_free_result($result);
	return $data['total'];
}
// check if userliked post
function postliked($pid){
    global $conn;
    $user = $_SESSION['user-id'];
    $sql ="SELECT * FROM `likes` WHERE `pic-id`= '$pid' AND `user-id`='$user'";
	$result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) != 0){
        mysqli_free_result($result);
        return true;
    }else{
        return false;
    }
}
// like post
function likePost($Pid){
    global $conn;
    $user = $_SESSION['user-id'];
    $sql = "INSERT INTO `likes`(`pic-id`, `user-id`) VALUES('$Pid', '$user')";
    $result = mysqli_query($conn, $sql);
    if ($result){
        return true;
    }else {
        return false;
    }
}
// Unlike post
function unlikePost($Pid){
    global $conn;
    $user = $_SESSION['user-id'];
    $sql = "DELETE FROM `likes` WHERE `pic-id`='$Pid' AND `user-id`='$user'";
    $result = mysqli_query($conn, $sql);
    if ($result){
        return true;
    }else {
        return false;
    }
}
?>