<?php
    session_start();
	include("db/dbconnect.php");
    include("include/header.php");
    include("include/functions.php");

	// Create new user
	if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["login"]) {
		$password = test_input($_POST['password']);
		$username = test_input($_POST['username']);
		// Escape chars
		$username = mysqli_real_escape_string($conn, $username);
		$password = mysqli_real_escape_string($conn, $password);
		// Get password harsh
		$password = md5($password);
		// create sql
		$sql = "SELECT * FROM user WHERE username='$username' AND password='$password' LIMIT 1";
		$result = mysqli_query($conn, $sql);
		
		if(mysqli_num_rows($result) == 0){
			$_SESSION['message'] = "Login Failed, Invalid Input!";
		}
		else{
			$row=mysqli_fetch_array($result);
			loginSession($row);
			mysqli_free_result($result);
			header('Location: index.php');
		}

		}
		

?>
<section class="vh-100 bg-image" style="background-image: url('assets/images/pexels-trinity.jpg');">
	<?php if(isset($_SESSION['message'])): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<h6><strong>Huh! </strong><?php echo $_SESSION['message'] ?></h6>
		</div>
	<?php 
		endif;
		unset($_SESSION['message']);
	?>
    <div class="mask d-flex align-items-center h-100 gradient-custom-3">
		<div class="container h-100">
			<div class="d-flex justify-content-center h-100">
				<div class="user_card">
					<div class="d-flex justify-content-center">
						<div class="brand_logo_container">
							<a href="index.php"><img src="assets/images/logo-purple.png" class="brand_logo" alt="Logo"></a>
						</div>
					</div>
					<div class="d-flex justify-content-center form_container">
						<form action="<?php echo test_input($_SERVER["PHP_SELF"]);?>" method="post">
							<div class="input-group mb-3">
								<div class="input-group-append">
									<span class="input-group-text"><i class="fa fa-user"></i></span>
								</div>
								<input type="text" name="username" class="form-control input_user border-start-0" value="<?php if(isset($_POST['username'])){ echo htmlspecialchars($_POST['username']); }?>" placeholder="username" autofocus required>
							</div>
							<div class="input-group mb-2">
								<div class="input-group-append">
									<span class="input-group-text"><i class="fa fa-key"></i></span>
								</div>
								<input type="password" name="password" class="form-control input_pass border-start-0" value="<?php if(isset($_POST['password'])){ echo htmlspecialchars($_POST['password']); }?>" placeholder="password" required>
							</div>
							<div class="form-group">
								<!-- <div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="customControlInline">
									<label class="custom-control-label" for="customControlInline">Remember me</label>
								</div> -->
							</div>
								<div class="d-flex justify-content-center mt-3 login_container">
								<button type="submit" name="login" value="login" class="btn login_btn">Login</button>
							</div>
						</form>
					</div>
			
					<div class="mt-4">
						<div class="d-flex justify-content-center links">
							Don't have an account? <a href="signup.php" class="ml-2">Sign Up</a>
						</div>
						<div class="d-flex justify-content-center links">
							<a href="#">Forgot your password?</a>
						</div>
					</div>
				</div>
			</div>
		</div>
</section>

<?php
    include("include/footer.php");
?>