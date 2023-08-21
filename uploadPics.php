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
    
    // Save images to db
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveImages']))
    {
        // get the dimensions array to use it for the foreach loop
        $dimension = $_POST['dimension'];
        // iterrate through each image
        foreach($dimension as $index => $value)
        {
            $image = array();
            $image['name'] = $_FILES["image"]["name"][$index];
            $image['size'] = $_FILES["image"]["size"][$index];
            $image['tmp_name'] = $_FILES["image"]["tmp_name"][$index];
            uploadImage($image, $value, $conn);
        }
        
    }
    include("include/nav-bar.php");
?>

<!-- ======= Sidebar ======= -->
<?php include("include/sidebar.php") ?>

<main id="main" class="main">

<div class="pagetitle">
    <h1>Upload Photo</h1>
</div><!-- End Page Title -->
<section class="section profile">
    <div class="row"  style="width: 100%;">
        <!-- Left column -->
        <div class="col-xl-4">
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Photo List
                        <a href="javascript:void(0)" class="add-more-form float-end btn btn-primary"><span class="bx bx-plus bx-xs"></span></a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo test_input($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
                        <div class="paste-new-forms mb-3">
                            <div class="main-form mt-3 border-bottom">
                                <div class="row">
                                    <div class="col-5 col-sm-3">
                                        <div class="form-group mb-2">
                                            <div class="ms-3 mt-2 form-outline formPic">
                                                <img src="assets/images/square.jpg" class="rounded-1" style="max-height: 70px; max-width: 80px;" alt="photo" id="ListPhoto" loading="lazy">
                                                <input type="file" id="uploadFile" name="image[]"  accept=".jpg, .jpeg, .png"  >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-5 col-sm-4">
                                        <div class="form-group mb-2 mt-2">
                                            <label for="">Dimension (HxW)</label>
                                            <input type="text" name="dimension[]" class="form-control border-0"  id="dimension" value="1x1" readonly >
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group mb-2">
                                            <br>
                                            <button type="button" class="edit-btn btn btn-success"><span class="bx bx-edit-alt bx-xs"></span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="saveImages" class="btn btn-primary float-end">Save Multiple Data</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Right column -->
        <div class="col-xl-8">
            <div class="card editImage">
                <div class="card-body pt-3">
                    <h4> Edit Photo</h4>
                    <hr>
                    <div class="image d-flex justify-content-center mb-4">
                        <div class="upload-pic-div form-outline">
                            <img src="assets/images/landscaped.jpg" alt="photo" id="uploadPhoto" loading="lazy">
                            <input type="file" id="uploadFile" name="image[]" accept=".jpg, .jpeg, .png">
                            <!-- <label for="uploadFile" id="uploadPicBtn">Choose Photo</label> -->
                        </div>
                    </div>
                    <h4> Dimensions (hxw)</h4>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <h5>Height</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="height" id="h1" value="1">
                                <label class="form-check-label" for="h1">1</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="height" id="h2" value="2">
                                <label class="form-check-label" for="h2">2</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="height" id="h3" value="3">
                                <label class="form-check-label" for="h3">3</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="height" id="h4" value="4">
                                <label class="form-check-label" for="h4">4</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="height" id="h5" value="5">
                                <label class="form-check-label" for="h5">5</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5>width</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="width" id="w1" value="1">
                                <label class="form-check-label" for="w1">1</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="width" id="w2" value="2">
                                <label class="form-check-label" for="w2">2</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="width" id="w3" value="3">
                                <label class="form-check-label" for="w3">3</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="width" id="w4" value="4">
                                <label class="form-check-label" for="w4">4</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="width" id="w5" value="5">
                                <label class="form-check-label" for="w5">5</label>
                            </div>
                        </div>
                    </div>
                    <button name="save" class="btn btn-primary float-end mt-3 saveEdits">Save</button>
                </div>
            </div>
        </div>
    </div>
</section>
</main><!-- End #main -->
<?php
    include("include/footer.php");
?>