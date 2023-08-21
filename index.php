<?php
    session_start();
    include("db/dbconnect.php");
    include("include/header.php");
    include("include/functions.php");
    include("include/nav-bar.php");

    // Get gallery info 
    $sql ='SELECT * FROM gallery ORDER BY RAND()';
	$result1 = mysqli_query($conn, $sql);
	$gallery = mysqli_fetch_all($result1, MYSQLI_ASSOC);
	mysqli_free_result($result1);

?>

<h1 class="d-flex justify-content-center">Gallery</h1>
<div class="Gallery container">
    <?php foreach($gallery as $photo): ?>
        <div class="image-container <?php echo dimension(htmlspecialchars($photo['dimension'])) ?>">
            <div class="gallery-item">
            <div class="image">
            <a href="assets/images/gallery/<?php echo htmlspecialchars($photo['image']) ?>" data-lightbox="gallery" data-title="Uploaded by: <?php echo getName($conn ,htmlspecialchars($photo['user-id'])) ?>"><img src="assets/images/gallery/<?php echo htmlspecialchars($photo['image']) ?>" alt="image"></a>
            </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<?php
    include("include/footerinfo.php");
    include("include/footer.php");
?>