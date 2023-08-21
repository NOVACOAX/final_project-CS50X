<?php
    session_start();
    include("db/dbconnect.php");
    include("include/header.php");
    include("include/functions.php");

    // Create new user
	if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["register"]) {
        $password = test_input($_POST['password']);
		$confirm_password = test_input($_POST['confirm_password']);
        if ($password !=$confirm_password){
            $_SESSION["message"] = "Passwords don't match";
        }else{
            $username = test_input($_POST['username']);
            $email = test_input($_POST['email']);
            // Escape chars
            $username = mysqli_real_escape_string($conn, $username);
            $email = mysqli_real_escape_string($conn, $email);
            $password = mysqli_real_escape_string($conn, $password);
            // Get password harsh
            $password = md5($password);
            // create sql
            $sql = "INSERT INTO `user`(`username`,`email`,`password`, `active`) VALUES('$username', '$email', '$password', '1')";
            $id = mysqli_query($conn, $sql);

            // save to db and check
            if($id){
                // success
                setSession($conn);
                $_SESSION["success"] = "Thank you for creating a MAgPIE account";
                header('Location: index.php');
            } else {
                $_SESSION["message"] = "Error creating acount!!!";
            }

        }
        
	}
    

?>

<section class="vh-100 bg-image" style="background-image: url('assets/images/pexels-george.jpg');">
    <div class="mask d-flex align-items-center h-100 gradient-custom-3">
        <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-9 col-lg-7 col-xl-6">
            <div class="card" style="border-radius: 15px;">
            <?php if(isset($_SESSION["message"])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h6><strong>Huh! </strong><?php echo $_SESSION["message"]?></h6>
                    </div>
            <?php 
                endif;
                unset($_SESSION["message"]);
            ?>
                <div class="card-body p-5">
                <h2 class="text-uppercase text-center mb-5">Create an account</h2>

                <form action="<?php echo test_input($_SERVER["PHP_SELF"]);?>" method="post">


                    <div class="form-outline mb-4">
                    <input type="text" id="form3Example1cg" class="form-control form-control-lg" name="username" autofocus required value="<?php if(isset($_POST['username'])){ echo htmlspecialchars($_POST['username']); }?>">
                    <label class="form-label" for="form3Example1cg">Username</label>
                    </div>

                    <div class="form-outline mb-4">
                    <input type="email" id="form3Example3cg" class="form-control form-control-lg" name="email" required value="<?php if(isset($_POST['email'])){ echo htmlspecialchars($_POST['email']); }?>">
                    <label class="form-label" for="form3Example3cg">Your Email</label>
                    </div>

                    <div class="form-outline mb-4">
                    <input type="password" id="form3Example4cg" class="form-control form-control-lg" name="password" required value="<?php if(isset($_POST['password'])){ echo htmlspecialchars($_POST['password']); }?>">
                    <label class="form-label" for="form3Example4cg">Password</label>
                    </div>

                    <div class="form-outline mb-4">
                    <input type="password" id="form3Example4cdg" class="form-control form-control-lg" name="confirm_password" required value="<?php if(isset($_POST['confirm_password'])){ echo htmlspecialchars($_POST['confirm_password']); }?>">
                    <label class="form-label" for="form3Example4cdg">Repeat your password</label>
                    </div>

                    <div class="form-check d-flex justify-content-center mb-5">
                    <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3cg" required>
                    <label class="form-check-label" for="form2Example3g">
                        I agree all statements in <a href="#!" class="text-body"><u>Terms of service</u></a>
                    </label>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button class="btn btn-success btn-block btn-lg gradient-custom-4 text-body border-0" type="submit" name="register" value="register">Register</button>
                    </div>

                    <p class="text-center text-muted mt-5 mb-0">Have already an account? <a href="login.php" class="fw-bold text-body"><u>Login here</u></a></p>

                </form>

                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</section>

<?php
    include("include/footer.php");
?>