<?php 
	// Send feedbak
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["feedback"])) {
    $username = test_input($_POST['username']);
		$email = test_input($_POST['email']);
		$comment = test_input($_POST['comment']);
		$rate = test_input($_POST['rate']);
		// Escape chars
		$username = mysqli_real_escape_string($conn, $username);
		$email = mysqli_real_escape_string($conn, $email);
		$comment = mysqli_real_escape_string($conn, $comment);
		$rate = mysqli_real_escape_string($conn, $rate);

		// // create sql
		$sql = "INSERT INTO feedback(name,email,comment,rate) VALUES('$username', '$email', '$comment', '$rate')";
		$result = mysqli_query($conn, $sql);
		if($result){
			$_SESSION['success'] = "Thank You! Feedback has been sent.";
		}else{
      $_SESSION['message'] = "Error sending feedback!!!";
		}

    // Profile settings
  }else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["profileSettings"])){
    $username = test_input($_POST['username']);
		$email = test_input($_POST['email']);
		$password = test_input($_POST['password']);
    $Uid = $_SESSION['user-id'];
		// Escape chars
		$username = mysqli_real_escape_string($conn, $username);
		$email = mysqli_real_escape_string($conn, $email);
		$password = mysqli_real_escape_string($conn, $password);
    // Get password harsh
		$password = md5($password);
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
      if($_POST['newPassword']){
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

      }
      // Update profile picture
      if($_FILES["dp"]["name"]){
        uploadDP($_FILES["dp"], $conn);
      }
      // Update user info
      $sql = "UPDATE user SET username='$username', email='$email' WHERE id='$Uid'";
      $result = mysqli_query($conn, $sql);
      if($result){
        $_SESSION['success'] = "Profile updated Successfully.";
        $_SESSION["username"] = $username;
        $_SESSION["user-email"] = $email;
      }else{
        $_SESSION['message'] = "Error updating profile!!!";
      }    
    }


  }
		
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <!-- Container wrapper -->
  <div class="container-fluid d-flex ">
    <!-- Navbar brand -->
    <a class="navbar-brand mt-2 mt-lg-0 me-auto me-lg-4"  href="index.php">
        <img src="assets/images/logo-white.png" width="30" height="30" alt="logo" class="mx-2" loading="lazy"><span>MAgPIE  </span> <span style="font-family: zapfino; font-size: 12px;"> Gallery</span>
    </a>
    <!-- Toggle button -->
    <?php if(basename($_SERVER['PHP_SELF']) == "dashboard.php" || basename($_SERVER['PHP_SELF']) == "user-profile.php"  || basename($_SERVER['PHP_SELF']) == "uploadPics.php"):?>
      <i class="d-none d-lg-block fa fa-bars toggle-sidebar-btn" onclick="toggleSidebar()" style="font-size:large; color:darkgray;"></i>
      <i class="d-block d-lg-none fa fa-bars toggle-sidebar-btn px-2" onclick="toggleSidebar()" style="font-size:large; color:darkgray;"></i>
    <?php else: ?>
      <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa fa-bars"></i>
      </button>
    <?php endif?>

    <!-- Collapsible wrapper -->
    <div class="collapse navbar-collapse navbarH" id="navbarSupportedContent">
      <!-- Left links -->
      <ul class="navbar-nav  ms-auto mb-2 mb-lg-0">
          <?php if(isset($_SESSION['user-id'])): ?>
            <!-- Notifications dropdown -->
            <!-- <li class="nav-item dropdown me-3 me-lg-1">
              <a class="nav-link dropdown-toggle hidden-arrow" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" data-mdb-auto-close="outside" aria-expanded="false">
                <i class="fa fa-bell-o fa-lg bx-tada-hover"></i>
                <span class="badge rounded-pill badge-notification bg-danger p-1"> </span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink" style="width:250px;">
                <li>
                  <a href="#">
                    <div class="p-2 message-item">
                      <div class="d-flex justify-content-between">
                        <h4>Lorem Ipsum <i class="fa fa-info-circle text-primary "></i></h4>
                        <button type="button" class="btn-close notificationClose" onclick="hideMessage(this)" aria-label="Close"></button>
                      </div>
                      <p class="text-truncate"  style="max-width: 222px;">Quae dolorem earum veritatis oditseno
                        <br>
                        30 min. ago
                      </p>
                    </div>
                  </a>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a href="#">
                    <div class="p-2 message-item">
                      <div class="d-flex justify-content-between">
                        <h4>Lorem Ipsum <i class="fa fa-info-circle text-primary "></i></h4>
                        <button type="button" class="btn-close notificationClose" onclick="hideMessage(this)" aria-label="Close"></button>
                      </div>
                      <p class="text-truncate"  style="max-width: 222px;">Quae dolorem earum veritatis oditseno
                        <br>
                        30 min. ago
                      </p>
                    </div>
                  </a>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a href="#">
                    <div class="p-2 message-item">
                      <div class="d-flex justify-content-between">
                        <h4>Lorem Ipsum <i class="fa fa-info-circle text-primary "></i></h4>
                        <button type="button" class="btn-close notificationClose" onclick="hideMessage(this)" aria-label="Close"></button>
                      </div>
                      <p class="text-truncate"  style="max-width: 222px;">Quae dolorem earum veritatis oditseno
                        <br>
                        30 min. ago
                      </p>
                    </div>
                  </a>
                  <hr class="dropdown-divider">
                </li>
                <li class="dropdown-footer d-flex justify-content-center pb-3">
                  <a href="#" class="text-primary" style="text-decoration: underline;">Show all notifications</a>
                </li>
              </ul>
            </li> -->
            <!-- Avatar -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false" >
                <img src="assets/images/dp/<?php echo $_SESSION["user-dp"]; ?>" class="rounded-circle" height="22" alt="DP" loading="lazy" >
                <strong class="d-none d-sm-block px-2"><?php echo $_SESSION["username"]; ?></strong>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                <?php if($_SESSION["user-type"] == 222 || $_SESSION["user-type"] == 323): ?>
                  <li>
                    <a class="dropdown-item" href="dashboard.php">Dashboard</a>
                  </li>
                <?php else: ?>
                  <li>
                    <a class="dropdown-item" href="uploadPics.php">Upload Photo</a>
                  </li>
                <?php endif ?>
                <li>
                  <a href="" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#profile">My profile</a>
                </li>
                <li>
                  <a href="" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#feedback">feedback</a>
                </li>
                <li>
                  <a class="dropdown-item" href="logout.php">Logout</a>
                </li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item ">
              <a class="nav-link" href="login.php"></span> Login</a>
            </li>
            <li class="nav-item ">
              <a class="nav-link" href="signup.php">Sign up</a>
            </li>
            <li class="nav-item ">
              <a href="" class="nav-link" data-bs-toggle="modal" data-bs-target="#feedback">feedback</a>
            </li>

          <?php endif ?>
      </ul>
      <!-- Left links -->
    </div>
    <!-- Collapsible wrapper -->
  </div>
  <!-- Container wrapper -->
</nav>
<!-- Navbar -->

<!-- Messages -->
<!-- Error Message -->
<?php if(isset($_SESSION["message"])): ?>
  <div class="alert alert-danger  alert-dismissible fade show d-flex alert_message sticky-top" style="z-index: 6000;" role="alert" id="allertMessage">
    <h6 class="text-danger flex-grow-1"><strong>Huh! </strong><?php echo $_SESSION["message"] ?></h6>
    <span class="close-alert border  px-2 rounded-circle border-dark text-dark" onclick="document.getElementById('allertMessage').remove()">&times;</span>
  </div>
<?php 
  endif;
  unset($_SESSION["message"]);
?>
<!-- Success Masage -->
<?php if(isset($_SESSION["success"])): ?>
  <div class="alert alert-success  alert-dismissible fade show d-flex alert_message sticky-top" style="z-index: 6000;" role="alert" id="successMessage">
    <h6 class="text-success flex-grow-1"><strong>Yaay! </strong><?php echo $_SESSION["success"] ?></h6>
    <span class="close-alert border  px-2 rounded-circle border-dark text-dark" onclick="document.getElementById('successMessage').remove()">&times;</span>
  </div>
<?php 
  endif;
  unset($_SESSION["success"]);
?>
<!-- Feedback container -->
<div class="modal fade modal-dialog-scrollable modal-fullscreen-md-down" id="feedback" tabindex="-1" aria-labelledby="feedback" aria-hidden="true">
  <div class="modal-dialog  modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Let us know what you think</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo test_input($_SERVER["PHP_SELF"]);?>" method="post">
          <div class="modal-body">
              <h5>How satisfied were you with our Service?</h5>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="rate" id="flexRadioDefault1" value="excellent">
                <label class="form-check-label" for="flexRadioDefault1">excellent</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="rate" id="flexRadioDefault2"  value="good">
                <label class="form-check-label" for="flexRadioDefault2">good</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="rate" id="flexRadioDefault3"  value="neutral">
                <label class="form-check-label" for="flexRadioDefault3">neutral</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="rate" id="flexRadioDefault4"  value="poor">
                <label class="form-check-label" for="flexRadioDefault4">poor</label>
              </div>
              <hr>
              <h5>If you have specific feedback, please write to us...</h5>
              <div class="mb-3">
                <textarea class="form-control" id="exampleFormControlTextarea1" name="comment" rows="3"placeholder="Additional comments" required></textarea>
              </div>
              <div class="row g-2">
                <div class="col-md">
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="name" placeholder="Your Name (optional)" name="username" value="<?php if(isset($_SESSION['username'])){ echo htmlspecialchars($_SESSION['username']); }?>">
                      <label for="name">Name</label>
                    </div>
                </div>
                <div class="col-md">
                  <div class="form-floating mb-3">
                    <input type="email" class="form-control"  placeholder="Your Email (optional)" name="email" required value="<?php if(isset($_SESSION['user-email'])){ echo htmlspecialchars($_SESSION['user-email']); }?>">
                    <label for="email">Email address</label>
                  </div>
                </div>
              </div>
          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button type="submit" name="feedback" value="feedback" class="btn btn-secondary">Submit</button>
          </div>
      </form>
    </div>
  </div>
</div>
<!-- Profile container -->
<div class="modal fade modal-dialog-scrollable modal-fullscreen-md-down" id="profile" tabindex="-1" aria-labelledby="profile" aria-hidden="true">
  <div class="modal-dialog  modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <ul class="nav nav-tabs" id="profile" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="user-tab" data-bs-toggle="tab" data-bs-target="#user" type="button" role="tab" aria-controls="user" aria-selected="true">Profile</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Settings</button>
          </li>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="tab-content" id="profileContent">
        <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
          <div class="modal-body text-center">
            <img src="assets/images/dp/<?php echo $_SESSION["user-dp"]; ?>" class="rounded-circle mb-3 pt-1" height="150" alt="DP" loading="lazy" onclick="window.location.href = 'user-profile.php?id=<?php echo $_SESSION['user-id'];?>'" style="cursor: pointer;">
            <h5 class="d-none d-sm-block px-2 text-uppercase text-dark"><?php echo $_SESSION["username"]; ?></h5>
            <h6 class="d-none d-sm-block px-2 text-dark"><?php echo $_SESSION["user-email"]; ?></h6>
            <div class="social-links mt-3 col-12 d-flex justify-content-around">
              <?php if(isset($_SESSION["user-twitter"])): ?>
                <a href="<?php echo $_SESSION["user-twitter"]; ?>" class="twitter"><i class="bx bxl-twitter bx-lg bx-tada-hover"></i></a>
              <?php endif ?>
              <?php if(isset($_SESSION["user-facebook"])): ?>
                <a  href="<?php echo $_SESSION["user-facebook"]; ?>" class="facebook"><i class="bx bxl-facebook-circle bx-lg"></i></a>
              <?php endif ?>
              <?php if(isset($_SESSION["user-instagram"])): ?>
                <a href="<?php echo $_SESSION["user-instagram"]; ?>" class="instagram"><i class="bx bxl-instagram bx-lg"></i></a>
              <?php endif ?>
              <?php if(isset($_SESSION["user-tiktok"])): ?>
                <a href="<?php echo $_SESSION["user-tiktok"]; ?>" class="tiktok"><i class="bx bxl-tiktok bx-lg"></i><i class="bx bxl-tiktok bx-lg"  style="top:2px;left:2px;"></i></a>
              <?php endif ?>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
          <form action="<?php echo test_input($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
              <div class="modal-body">
                    <div class=" d-flex justify-content-center">
                      <div class="profile-pic-div form-outline mb-5">
                        <img src="assets/images/dp/<?php echo $_SESSION["user-dp"]; ?>" class="rounded-circle" height="150" width="150" alt="DP" loading="lazy" id="photo">
                        <input type="file" id="file" name="dp" style="display: none;" accept=".jpg, .jpeg, .png">
                        <label for="file" id="uploadBtn">Choose Photo</label>
                      </div>
                    </div>
                    <div class="form-outline mb-4">
                      <input type="text" id="username" class="form-control form-control-lg" name="username" required value="<?php if(isset($_SESSION["username"])){ echo htmlspecialchars($_SESSION["username"]); }?>">
                      <label class="form-label" for="username">Username</label>
                    </div>
                    <div class="form-outline mb-4">
                      <input type="email"  class="form-control form-control-lg" name="email" required value="<?php if(isset($_SESSION["user-email"])){ echo htmlspecialchars($_SESSION["user-email"]); }?>">
                      <label class="form-label" for="email">Your Email</label>
                    </div>
                    <div class="form-outline mb-4">
                      <input type="password" id="password" class="form-control form-control-lg" name="password" required>
                      <label class="form-label" for="password">Password*</label>
                    </div>
                    <hr>
                    <h5>Change password</h5>
                    <div class="form-outline mb-4">
                      <input type="password" id="newPassword" class="form-control form-control-lg" name="newPassword" >
                      <label class="form-label" for="newPassword">New Password</label>
                    </div><div class="form-outline mb-4">
                      <input type="password" id="confirmPassword" class="form-control form-control-lg" name="confirmPassword" >
                      <label class="form-label" for="confirmPassword"> Confirm Password</label>
                    </div>
              </div>
              <div class="modal-footer d-flex justify-content-end">
                <button type="submit" name="profileSettings" value="profileSettings" class="btn btn-secondary">Submit</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

