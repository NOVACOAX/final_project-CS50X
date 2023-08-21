<?php
    session_start();
    if(!isset($_SESSION["user-type"])){
        $_SESSION['message'] = "Please log in first.";
        header('Location: login.php');
    }
    // else if($_SESSION["user-type"] != 222 && $_SESSION["user-type"] != 323){
    //     $_SESSION['message'] = "Access denied!";
    //     header('Location: index.php');
    // }
    include("db/dbconnect.php");
    include("include/header.php");
    include("include/functions.php");


    // Get selected user info else display loged in user info
    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])){
      $userinfo = getuserprofile($_GET["id"]);
    }else{
      $userinfo = getuserprofile($_SESSION["user-id"]);
    }
    $Uid = $userinfo['id'];

    // changing the password
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["changePassword"])){
      // test inputs
      $password = test_input($_POST['password']);
      $newPassword = test_input($_POST['newPassword']);
      $confirmPassword = test_input($_POST['confirmPassword']);
      // Escape chars
      $password = mysqli_real_escape_string($conn, $password);
      $newPassword = mysqli_real_escape_string($conn, $newPassword);
      $confirmPassword = mysqli_real_escape_string($conn, $confirmPassword);
      // Call function to change Password
      changeUpassword($password, $newPassword, $confirmPassword);

    } elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["saveUserinfo"])) {
      $userData = array();
      // Test inputs
      $name = test_input($_POST['name']);
      $about = test_input($_POST['about']);
      $email = test_input($_POST['email']);
      $twitter = test_input($_POST['twitter']);
      $facebook = test_input($_POST['facebook']);
      $instagram = test_input($_POST['instagram']);
      $tiktok = test_input($_POST['tiktok']);
      // Escape chars
      $userData['name'] = mysqli_real_escape_string($conn, $name);
      $userData['about'] = mysqli_real_escape_string($conn, $about);
      $userData['email'] = mysqli_real_escape_string($conn, $email);
      $userData['twitter'] = mysqli_real_escape_string($conn, $twitter);
      $userData['facebook'] = mysqli_real_escape_string($conn, $facebook);
      $userData['instagram'] = mysqli_real_escape_string($conn, $instagram);
      $userData['tiktok'] = mysqli_real_escape_string($conn, $tiktok);
      if($_SESSION['user-type'] == 323){
        $role = test_input($_POST['role']);
        $role = mysqli_real_escape_string($conn, $role);
        switch($role){
          case 'admin':
            $userData['role'] = 323;
            break;
          case 'editor':
            $userData['role'] = 222;
            break;
          default;
            $userData['role'] = 111;
        }
      }else{
        $userData['role'] = $_SESSION['user-type'];
      }
      // Call function to update values
      updateUserInfo($userData);
      // Update profile picture
      if($_FILES["dp"]["name"]){
        uploadDP($_FILES["dp"], $conn);
      }
    }elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ChangeSettings"])) {
      $Uid = $_POST['user-id'];
      if(isset($_POST['changesMade'])) {
        $changesMade = 1;
      }else {
        $changesMade = 0;
      }
      if(isset($_POST['newProducts'])) {
        $newProducts = 1;
      }else {
        $newProducts = 0;
      }
      if(isset($_POST['sensitive'])) {
        $sensitive = 1;
      }else {
        $sensitive = 0;
      }
      if(isset($_POST['privacy'])) {
        $privacy = test_input($_POST['privacy']);
        $privacy = mysqli_real_escape_string($conn, $privacy);
      }else {
        $privacy = 0;
      }
      $sql ="SELECT * FROM `user-settings` WHERE `user-id`= '$Uid'";
      $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result) == 0){
        $sql = "INSERT INTO `user-settings`(`user-id`, `privacy`,`EonChange`,`EonProducts`, `sensitiveC`) VALUES('$Uid', '$privacy', '$changesMade', '$newProducts', '$sensitive')";
        $result2 = mysqli_query($conn, $sql);
        if($result2) {
          $_SESSION['success'] = 'Settings changed.';
        }else {
          $_SESSION['message'] = 'Error changing settings!!';
        }
      }else{
        mysqli_free_result($result);
        $sql = "UPDATE `user-settings` SET `privacy`='$privacy',`EonChange`='$changesMade',`EonProducts`='$newProducts', `sensitiveC`='$sensitive' WHERE `user-id`='$Uid' ";
        $result3 = mysqli_query($conn, $sql);
        if($result3) {
          $_SESSION['success'] = 'Settings changed.';
        }else {
          $_SESSION['message'] = 'Error changing settings!!';
        }
      }
    }

    // make a list of following ids
    $sql ="SELECT `follower` FROM `follows` WHERE `following`= '$Uid'  ORDER BY `datestamp`DESC";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) != 0){
      $followingUser = mysqli_fetch_all($result, MYSQLI_ASSOC);
      mysqli_free_result($result);
    }else{
      $followingUser = array();
    }
    // make a list of followed ids
    $sql ="SELECT `following` FROM `follows` WHERE `follower`= '$Uid' ORDER BY `datestamp`DESC";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) != 0){
      $followedUser = mysqli_fetch_all($result, MYSQLI_ASSOC);
      mysqli_free_result($result);
    }else{
      $followedUser = array();
    }
    // make a list of users posts
    $sql ="SELECT `image`, `id`, `upload-date` FROM `gallery` WHERE `user-id`= '$Uid'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) != 0){
      $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
      mysqli_free_result($result);
    }else{
      $posts = array();
    }
    // make a list of users posts
    $sql ="SELECT P.image, P.id, P.`upload-date` FROM `gallery` AS P LEFT JOIN `likes` AS L ON P.id = L.`pic-id` WHERE L.`user-id` = '$Uid' ORDER BY L.datestamp DESC";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) != 0){
      $likedPosts = mysqli_fetch_all($result, MYSQLI_ASSOC);
      mysqli_free_result($result);
    }else{
      $likedPosts = array();
    }
    // make a list of users settings
    $sql ="SELECT * FROM `user-settings` WHERE `user-id`= '$Uid'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) != 0){
      $settings = mysqli_fetch_all($result, MYSQLI_ASSOC);
      $settings = $settings[0];
      mysqli_free_result($result);
    }else{
      $settings = array();
    }
    include("include/nav-bar.php");
?>

<!-- ======= Sidebar ======= -->
<?php include("include/sidebar.php") ?>

<main id="main" class="main">

<div class="pagetitle">
    <h1>Profile</h1>
</div><!-- End Page Title -->

<section class="section profile">
  <div class="row"  style="width: 100%;">
      <!-- Left card -->
      <div class="col-xl-4">
        <div class="card mb-3">
          <div class="text-end pe-1 pt-3" style="margin-bottom: -10px;">
            <a id="UPSettingsToggle" style="z-index: 2000; cursor: pointer;"><span class="bx bx-dots-vertical-rounded bx-sm" ></span></a>
          </div>
            <div class="card-body profile-card pt-0 d-flex flex-column align-items-center " >
              <img src="assets/images/dp/<?php echo $userinfo["dp"]; ?>" alt="Profile" class="rounded-circle" height="250">
              <h2 class="mt-3"><?php echo $userinfo["name"]; ?></h2>
              <div class="d-flex justify-content-around col-12">
                <div>
                  <h4>Followers</h4>
                  <h5 class="text-center text-dark followersCount"><?php echo followerCount($userinfo['id']) ?></h5>
                </div>
                <div>
                  <h4>Following</h4>
                  <h5 class="text-center text-dark followingCount"><?php echo followingCount($userinfo['id']) ?></h5>
                </div>
                <div>
                  <h4>Posts</h4>
                  <h5 class="text-center text-dark"><?php echo postCount($userinfo['id']) ?></h5>
                </div>
              </div>

              <?php if($userinfo['id']  != $_SESSION['user-id']): ?>
                <?php if(isFollowing($userinfo['id'])): ?>
                  <button  value="<?php echo $userinfo['id'] ?>" id="unfollowUser" class="btn btn-secondary">Unfollow</button>
                <?php else: ?>
                  <button  value="<?php echo $userinfo['id'] ?>" id="followUser" class="btn btn-secondary">Follow</button>
                <?php endif ?>
              <?php endif ?>

              <div class="social-links mt-3 col-8 d-flex justify-content-around">
                <?php if(isset($userinfo["twitter"])): ?>
                  <a href="<?php echo $userinfo["twitter"]; ?>" class="twitter"><i class="bx bxl-twitter bx-lg bx-tada-hover"></i></a>
                <?php endif ?>
                <?php if(isset($userinfo["facebook"])): ?>
                  <a  href="<?php echo $userinfo["facebook"]; ?>" class="facebook"><i class="bx bxl-facebook-circle bx-lg"></i></a>
                <?php endif ?>
                <?php if(isset($userinfo["instagram"])): ?>
                  <a href="<?php echo $userinfo["instagram"]; ?>" class="instagram"><i class="bx bxl-instagram bx-lg"></i></a>
                <?php endif ?>
                <?php if(isset($userinfo["tiktok"])): ?>
                  <a href="<?php echo $userinfo["tiktok"]; ?>" class="tiktok"><i class="bx bxl-tiktok bx-lg"></i><i class="bx bxl-tiktok bx-lg"  style="top:2px;left:2px;"></i></a>
                <?php endif ?>
              </div>
            </div>
        </div>

      </div>
      <!-- Right cards -->
      <div class="col-xl-8">
        <!-- Settings card -->
        <div class="card mb-3" id="UPSettingsTab" style="display: none;"  >
          <div class="card-body pt-3">
            <!-- Bordered Tabs -->
            <ul class="nav nav-tabs nav-tabs-bordered">

              <li class="nav-item">
                <button class="nav-link active" data-mdb-toggle="tab" data-mdb-target="#profile-overview">Overview</button>
              </li>

              <?php if($userinfo["id"] == $_SESSION["user-id"]): ?>
                <li class="nav-item">
                  <button class="nav-link" data-mdb-toggle="tab" data-mdb-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-mdb-toggle="tab" data-mdb-target="#profile-change-password">Change Password</button>
                </li>
              <?php endif ?>
              <?php if($userinfo["id"] == $_SESSION["user-id"] || $_SESSION["user-type"] == '323'): ?>
                <li class="nav-item">
                  <button class="nav-link" data-mdb-toggle="tab" data-mdb-target="#profile-settings">Settings</button>
                </li>
              <?php endif ?>

            </ul>
            <div class="tab-content pt-2">

              <div class="tab-pane fade show active profile-overview" id="profile-overview">
                <?php if(isset($userinfo['about'])): ?>
                <h5 class="card-title">About</h5>
                  <p class=" "><?php echo $userinfo["about"]; ?></p>
                <?php endif ?>
                <h5 class="card-title">Profile Details</h5>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label text-dark">Full Name :</div>
                  <div class="col-lg-9 col-md-8"><?php echo $userinfo["name"]; ?></div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label text-dark">Email :</div>
                  <div class="col-lg-9 col-md-8"><?php echo $userinfo["email"]; ?></div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label text-dark">Role :</div>
                  <div class="col-lg-9 col-md-8"><?php if($userinfo["type"] == 323){echo'Admin';}elseif($userinfo["type"] == 222){echo'Editor';}else{echo'User';} ?></div>
                </div>

                <?php if(isset($userinfo["twitter"])): ?>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label text-dark">Twitter :</div>
                    <div class="col-lg-9 col-md-8"><a href="<?php echo $userinfo["twitter"]; ?>" class="text-secondary"><?php echo $userinfo["twitter"]; ?></a></div>
                  </div>
                <?php endif ?>

                <?php if(isset($userinfo["facebook"])): ?>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label text-dark">Facebook :</div>
                    <div class="col-lg-9 col-md-8"><a href="<?php echo $userinfo["facebook"]; ?>" class="text-secondary"><?php echo $userinfo["facebook"]; ?></a></div>
                  </div>
                <?php endif ?>

                <?php if(isset($userinfo["instagram"])): ?>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label text-dark">Instagram :</div>
                    <div class="col-lg-9 col-md-8"><a href="<?php echo $userinfo["instagram"]; ?>" class="text-secondary"><?php echo $userinfo["instagram"]; ?></a></div>
                  </div>
                <?php endif ?>

                <?php if(isset($userinfo["tiktok"])): ?>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label text-dark">TikTok :</div>
                    <div class="col-lg-9 col-md-8"><a href="<?php echo $userinfo["tiktok"]; ?>" class="text-secondary"><?php echo $userinfo["tiktok"]; ?></a></div>
                  </div>
                <?php endif ?>
              </div>

              <?php if($userinfo["id"] == $_SESSION["user-id"]): ?>
                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form method="POST" action="<?php echo test_input($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="profile-pic-settings form-outline mb-1">
                            <img src="assets/images/dp/<?php echo $userinfo["dp"]; ?>" class="rounded" height="100" width="100" alt="DP" loading="lazy" id="settingsPhoto">
                            <input type="file" id="settingsFile" name="dp" accept=".jpg, .jpeg, .png">
                          </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="name" type="text" class="form-control" id="fullName" value="<?php echo $userinfo["name"]; ?>">
                      </div>
                    </div>

                    <?php if($_SESSION['user-type'] == 323): ?>
                      <div class="row mb-3">
                        <label for="role" class="col-md-4 col-lg-3 col-form-label">Role</label>
                        <div class="col-md-8 col-lg-9 row ps-4">
                          <div class="form-check col-4">
                            <label class="form-check-label" for="Admin">Admin</label>
                            <input class="form-check-input" type="radio" name="role" id="Admin" value="admin" <?php if($userinfo['type'] == 323){echo 'checked';} ?>>
                          </div>
                          <div class="form-check col-4">
                            <label class="form-check-label" for="Editor">Editor</label>
                            <input class="form-check-input" type="radio" name="role" id="Editor" value="editor" <?php if($userinfo['type'] == 222){echo 'checked';} ?>>
                          </div>
                          <div class="form-check col-4">
                            <label class="form-check-label" for="User">User</label>
                            <input class="form-check-input" type="radio" name="role" id="User" value="user" <?php if($userinfo['type'] == 111){echo 'checked';} ?>>
                          </div>
                        </div>
                      </div>
                    <?php  endif ?>

                    <div class="row mb-3">
                      <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="about" type="text" class="form-control" id="about" value="<?php if(isset($userinfo['about'])){echo $userinfo['about'];} ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="Email" value="<?php echo $_SESSION["user-email"]; ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Twitter Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="twitter" type="text" class="form-control" id="Twitter" value="<?php if(isset($userinfo['twitter'])){echo $userinfo['twitter'];}else{echo 'https://twitter.com/#';} ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="facebook" type="text" class="form-control" id="Facebook" value="<?php if(isset($userinfo['facebook'])){echo $userinfo['facebook'];}else{echo 'https://facebook.com/#';} ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="instagram" type="text" class="form-control" id="Instagram" value="<?php if(isset($userinfo['instagram'])){echo $userinfo['instagram'];}else{echo 'https://instagram.com/#';} ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">TikTok Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="tiktok" type="text" class="form-control" id="Linkedin" value="<?php if(isset($userinfo['tiktok'])){echo $userinfo['tiktok'];}else{echo 'https://tiktok.com/#';} ?>">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" value="<?php echo $userinfo['id'] ?>" name="saveUserinfo" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form method="POST" action="<?php echo test_input($_SERVER["PHP_SELF"]);?>">

                    <div class="row mb-3">
                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="password" type="password" class="form-control" id="currentPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="newPassword2" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newPassword" type="password" class="form-control" id="newPassword2">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="confirmPassword2" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="confirmPassword" type="password" class="form-control" id="confirmPassword2">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" name="changePassword" class="btn btn-primary">Change Password</button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>
              <?php endif ?>

              <?php if($userinfo["id"] == $_SESSION["user-id"] || $_SESSION["user-type"] == 323): ?>
                <div class="tab-pane fade pt-3" id="profile-settings">

                  <!-- Settings Form -->
                  <form method="POST" action="<?php echo test_input($_SERVER["PHP_SELF"]);?>">
                    
                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="changesMade" id="changesMade" <?php if(isset($settings['EonChange']) && $settings['EonChange'] == 1){echo 'checked';} ?> >
                          <label class="form-check-label" for="changesMade">
                            Changes made to your account
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="newProducts" id="newProducts" <?php if(isset($settings['EonProducts']) && $settings['EonProducts'] == 1){echo 'checked';} ?>>
                          <label class="form-check-label" for="newProducts">
                            Information on new products and services
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                          <label class="form-check-label" for="securityNotify">
                            Security alerts
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Account  privacy</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" id="PonlyFollowers" name="privacy" value="1" <?php if(isset($settings['privacy']) && $settings['privacy'] == 1){echo 'checked';} ?>>
                          <label class="form-check-label" for="PonlyFollowers">
                            Only followers can view posts
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" id="Peveryone" name="privacy" value="0" <?php if(isset($settings['privacy']) && $settings['privacy'] == 0){echo 'checked';} ?>>
                          <label class="form-check-label" for="Peveryone">
                            everyone can view post
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Account </label>
                      <div class="col-md-8 col-lg-9">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="sensitive" id="sensitive" <?php if(isset($settings['sensitiveC']) && $settings['sensitiveC'] == 1){echo 'checked';} ?>>
                          <label class="form-check-label" for="sensitive">
                            View sensitive content
                          </label>
                        </div>
                        <div class="form-check my-3 d-flex">
                          <?php if($userinfo["type"] == 111 && $userinfo["id"] == $_SESSION["user-id"]): ?><button type="button" id="RequestED" class="btn btn-outline-secondary me-5" data-mdb-ripple-color="dark">Request Editor Status</button><?php endif ?>
                          <button type="button" id="Deactivate" class="btn btn-outline-danger" data-mdb-ripple-color="dark">Deactivate Account</button>
                        </div>
                      </div>
                    </div>

                    <input type="number" name="user-id" id="userId" value="<?php echo $userinfo["id"]; ?>" style="display: none;" >
                    <div class="d-flex justify-content-end">
                      <div class="text-center me-5">
                        <a href="#" id="deleteUser" class="text-danger">Delete User</a>
                        </div>
                        <div class="text-center">
                          <button type="submit" name="ChangeSettings" class="btn btn-primary">Save Changes</button>
                      </div>
                    </div>
                  </form><!-- End settings Form -->

                </div>
              <?php endif ?>

            </div><!-- End Bordered Tabs -->
          </div>
        </div>
        <!-- follows and posts card  -->
        <div class="card  mb-3">
          <div class="card-body pt-3">
            <!-- Bordered Tabs -->
            <ul class="nav nav-tabs nav-tabs-bordered">
  
              <li class="nav-item">
                <button class="nav-link" data-mdb-toggle="tab" data-mdb-target="#AFollowers">Followers</button>
              </li>
  
              <li class="nav-item">
                <button class="nav-link" data-mdb-toggle="tab" data-mdb-target="#AFollowing">Following</button>
              </li>
  
              <li class="nav-item">
                <button class="nav-link active" data-mdb-toggle="tab" data-mdb-target="#APosts">Posts</button>
              </li>
  
              <li class="nav-item">
                <button class="nav-link" data-mdb-toggle="tab" data-mdb-target="#likedPosts">Liked Posts</button>
              </li>
  
            </ul>
            <div class="tab-content pt-2">
  
              <div class="tab-pane fade AFollowers pt-3" id="AFollowers">
                <div class="row  g-2">
                  <?php foreach($followingUser as $follower): $follower = getuserprofile($follower["follower"]);?>
                    <div class="card border border-dark me-2" style="max-width: 150px; min-width: 150px;">
                      <div class="card-body text-dark text-center px-2">
                        <div onclick="window.location.href = 'user-profile.php?id=<?php echo htmlspecialchars($follower['id'])?>'" style="cursor: pointer;">
                          <img src="assets/images/dp/<?php echo $follower["dp"]; ?>" alt="Profile" class="rounded-circle " height="70">
                          <h6 class="card-title mt-1"><?php echo $follower["name"]; ?></h6>
                        </div>
                        <div class="social-links mt-1 col-12 d-flex justify-content-around">
                            <?php if(isset($follower["twitter"])): ?>
                              <a href="<?php echo $follower["twitter"]; ?>" class="twitter"><i class="bx bxl-twitter bx-sm bx-tada-hover"></i></a>
                            <?php endif ?>
                            <?php if(isset($follower["facebook"])): ?>
                              <a  href="<?php echo $follower["facebook"]; ?>" class="facebook"><i class="bx bxl-facebook-circle bx-sm"></i></a>
                            <?php endif ?>
                            <?php if(isset($follower["instagram"])): ?>
                              <a href="<?php echo $follower["instagram"]; ?>" class="instagram"><i class="bx bxl-instagram bx-sm"></i></a>
                            <?php endif ?>
                            <?php if(isset($follower["tiktok"])): ?>
                              <a href="<?php echo $follower["tiktok"]; ?>" class="tiktok"><i class="bx bxl-tiktok bx-sm"></i><i class="bx bxl-tiktok bx-sm"></i></a>
                            <?php endif ?>
                        </div>
                      </div>
                    </div>
                  <?php endforeach ?>
                </div>
              </div>
  
              <div class="tab-pane fade AFollowing pt-3" id="AFollowing">
                <div class="row  g-2">
                  
                  <?php foreach($followedUser as $followed): $followed = getuserprofile($followed["following"]);?>
                    <div class="card border border-dark me-2" style="max-width: 150px; min-width: 150px;">
                      <div class="card-body text-dark text-center px-2">
                        <div onclick="window.location.href = 'user-profile.php?id=<?php echo htmlspecialchars($followed['id'])?>'" style="cursor: pointer;">
                          <img src="assets/images/dp/<?php echo $followed["dp"]; ?>" alt="Profile" class="rounded-circle " height="70">
                          <h6 class="card-title mt-1"><?php echo $followed["name"]; ?></h6>
                        </div>
                        <div class="social-links mt-1 col-12 d-flex justify-content-around">
                            <?php if(isset($followed["twitter"])): ?>
                              <a href="<?php echo $followed["twitter"]; ?>" class="twitter"><i class="bx bxl-twitter bx-sm bx-tada-hover"></i></a>
                            <?php endif ?>
                            <?php if(isset($followed["facebook"])): ?>
                              <a  href="<?php echo $followed["facebook"]; ?>" class="facebook"><i class="bx bxl-facebook-circle bx-sm"></i></a>
                            <?php endif ?>
                            <?php if(isset($followed["instagram"])): ?>
                              <a href="<?php echo $followed["instagram"]; ?>" class="instagram"><i class="bx bxl-instagram bx-sm"></i></a>
                            <?php endif ?>
                            <?php if(isset($followed["tiktok"])): ?>
                              <a href="<?php echo $followed["tiktok"]; ?>" class="tiktok"><i class="bx bxl-tiktok bx-sm"></i><i class="bx bxl-tiktok bx-sm"></i></a>
                            <?php endif ?>
                        </div>
                      </div>
                    </div>
                  <?php endforeach ?>
  
                </div>
              </div>
  
              <div class="tab-pane fade show active pt-3" id="APosts">
                <div class="row  g-2">
                  <?php foreach($posts as $post): ?>
                    <div class="card me-2 p-0" style="max-width: 150px; min-width: 150px;">
                      <div style="max-height: 100px; overflow:hidden;" class="d-flex align-items-center rounded-top">
                      <a href="assets/images/gallery/<?php echo htmlspecialchars($post['image']) ?>" data-lightbox="posts" data-title="Uploaded by: <?php echo getName($conn ,htmlspecialchars($photo['user-id'])) ?>"><img src="assets/images/gallery/<?php echo htmlspecialchars($post['image']) ?>" class="card-img-top" alt="post"></a>
                      </div>
                      <div class="card-body text-dark d-flex justify-content-between p-0 px-1">
                        <p class="my-1" id="<?php if(postliked($post['id'])){echo 'liked';}else{echo 'unliked';} ?>" value="<?php echo $post['id']; ?>"><i class='bx bx<?php if(postliked($post['id'])){echo 's';}?>-heart bx-tada-hover text-danger'></i><?php echo getLikes(htmlspecialchars($post['id'])) ?></p>
                        <p class="my-1"><?php echo explodeDate(htmlspecialchars($post['upload-date'])) ?></p>
                      </div>
                    </div>
                  <?php endforeach ?>
                </div>
              </div>
  
              <div class="tab-pane fade pt-3" id="likedPosts">
                <div class="row  g-2">
                  <?php foreach($likedPosts as $post): ?>
                    <div class="card me-2 p-0" style="max-width: 150px; min-width: 150px;">
                      <div style="max-height: 100px; overflow:hidden;" class="d-flex align-items-center rounded-top">
                      <a href="assets/images/gallery/<?php echo htmlspecialchars($post['image']) ?>" data-lightbox="posts"><img src="assets/images/gallery/<?php echo htmlspecialchars($post['image']) ?>" class="card-img-top" alt="post"></a>
                      </div>
                      <div class="card-body text-dark d-flex justify-content-between p-0 px-1">
                        <p class="my-1" id="<?php if(postliked($post['id'])){echo 'liked';}else{echo 'unliked';} ?>" value="<?php echo $post['id']; ?>"><i class='bx bx<?php if(postliked($post['id'])){echo 's';}?>-heart bx-tada-hover text-danger'></i><?php echo getLikes(htmlspecialchars($post['id'])) ?></p>
                        <p class="my-1"><?php echo explodeDate(htmlspecialchars($post['upload-date'])) ?></p>
                      </div>
                    </div>
                  <?php endforeach ?>
                </div>
              </div>
  
            </div><!-- End Bordered Tabs -->
  
          </div>
        </div>
      </div>
  </div>
</section>

</main><!-- End #main -->


<?php
    include("include/footer.php");
?>