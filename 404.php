<?php

    session_start();
    include("include/header.php");
?>
<!-- page 404 -->
<main>
	<div class="container">
		<section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
			<h1>404</h1>
			<h2>The page you are looking for doesn't exist.</h2>
			<a class="btn" href="index.php">Back to home</a>
			<img src="assets/images/not-found.svg" class="img-fluid py-5" alt="Page Not Found">
			<div class="credits">
				Designed by <a href="https://www.instagram.com/novacoax/">NOVACOAX</a>
			</div>
		</section>
    </div>
</main><!-- End #main -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<!-- end page 404 -->
<?php
    include("include/footer.php");
?>