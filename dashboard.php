<?php
    session_start();
    if(!isset($_SESSION["user-type"])){
        $_SESSION['message'] = "Please log in first.";
        header('Location: login.php');
    }else if($_SESSION["user-type"] != '222' && $_SESSION["user-type"] != '323'){
        $_SESSION['message'] = "Access denied!";
        header('Location: index.php');
    }
    include("db/dbconnect.php");
    include("include/functions.php");
    include("include/header.php");

    if($_SESSION['user-type'] == 323){
        // Get feedback
        $sql ='SELECT * FROM feedback';
        $result = mysqli_query($conn, $sql);
        $feedbacks = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        
        // Get editor requests 
        $sql ='SELECT * FROM editorRequest';
        $result = mysqli_query($conn, $sql);
        $requests = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    }

    // Get gallery info 
    $sql ='SELECT * FROM gallery';
	$result = mysqli_query($conn, $sql);
	$gallery = mysqli_fetch_all($result, MYSQLI_ASSOC);
	mysqli_free_result($result);


    // Get user info
    $Uid = $_SESSION['user-id'];
    $sql ="SELECT `id`, `username`, `email`, `image`, `active`, `type`, `joined` FROM `user`";
	$result = mysqli_query($conn, $sql);
	$userInfos = mysqli_fetch_all($result, MYSQLI_ASSOC);
	mysqli_free_result($result);

    // Get 10 randome users
    $Uid = $_SESSION['user-id'];
    $sql ="SELECT `id` FROM `user` WHERE NOT `id`= '$Uid' ORDER BY RAND() LIMIT 10";
	$result = mysqli_query($conn, $sql);
	$FeaturedUsers = mysqli_fetch_all($result, MYSQLI_ASSOC);
	mysqli_free_result($result);

    // Get number of users
    $sql ='SELECT count(*) AS total FROM `user`';
	$result = mysqli_query($conn, $sql);
    $data=mysqli_fetch_assoc($result);
	$userCount = $data['total'];
	mysqli_free_result($result);

    // Get number of images
    $sql ='SELECT count(*) AS total FROM `gallery`';
	$result = mysqli_query($conn, $sql);
    $data=mysqli_fetch_assoc($result);
	$imageCount = $data['total'];
	mysqli_free_result($result);

    // use Instagram\User\Insights;

    // $config = array( // instantiation config params
    //     'user_id' => $userId,
    //     'access_token' => $accessToken,
    // );

    // // instantiate insights for use
    // $insights = new Insights( $config );

    // // initial response
    // $userInsights = $insights->getSelf();
        
    include("include/nav-bar.php");
    // echo $userInsights;
?>

<!-- ======= Sidebar ======= -->
<?php include("include/sidebar.php") ?>

<main id="main" class=" dashboard main">

<div class="pagetitle">
    <h1>Dashboard<?php if($_SESSION['user-type'] == 323){ echo ' | The Bo$$';}else{echo ' | Editor';} ?></h1>
</div><!-- End Page Title -->

<section class="section dashboard">
    <div class="row" style="width:100%;">
        <!-- Left side columns -->
        <div class="col-lg-8">
        <div class="row">

            <!-- Visits Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card pt-3">
                    <div class="card-body pt-0">
                        <h5 class="card-title">visits</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="fa fa-eye"></i>
                            </div>
                            <div class="ps-3">
                                <h6>145</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Visits Card -->

            <!-- Images Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card revenue-card pt-3">
                    <div class="card-body pt-0">
                        <h5 class="card-title">Photos </h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bxs-image bx-md"></i>
                            </div>
                            <div class="ps-3">
                                <h6><?php echo $imageCount ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Images Card -->

            <!-- users Card -->
            <div class="col-xxl-4 col-xl-12">
                <div class="card info-card customers-card pt-3">
                    <div class="card-body pt-0">
                        <h5 class="card-title">Users</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="ps-3">
                                <h6><?php echo $userCount ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Uses Card -->

            <?php if($_SESSION['user-type'] == 323): ?>
                <!-- Feedback  list-->
                <div class="col-12">
                    <div class="card recent-sales overflow-auto">
                        <div class="filter d-flex ms-auto px-2 pt-2 ">
                            <a class="icon" href="#" data-mdb-toggle="dropdown"><span class=" bx bx-filter-alt"></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start"><h6>Filter</h6></li>
                                <li><a class="dropdown-item filterFeedback filterActive" id="all">All</a></li>
                                <li><a class="dropdown-item filterFeedback" id="today">Today</a></li>
                                <li><a class="dropdown-item filterFeedback" id="thisMonth">This Month</a></li>
                                <li><a class="dropdown-item filterFeedback" id="thisYear">This Year</a></li>
                                <li><a class="dropdown-item filterFeedback" id="notReplied">Not Replied</a></li>
                            </ul>
                        </div>
                        <div class="card-body pt-0 feedback">
                            <h5 class="card-title">Feedback <span>| All</span></h5>
                            <table id="feedbackTB" class="table table-striped " style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:20%" class="text-center">Name</th>
                                        <th style="width:20%" class="text-center">Email</th>
                                        <th style="width:30%" class="text-center">Comment</th>
                                        <th style="width:10%" class="text-center">Rate</th>
                                        <th style="width:10%" class="text-center">Date</th>
                                        <th style="width:10%" class="text-center">Time</th>
                                        <th style="width:10%" class="text-center"><span class="bx bx-cog bx-sm"></span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($feedbacks as $feedback): ?>
                                        <tr class="feedbackRow">
                                            <td class="text-center feedbackName"><?php if(htmlspecialchars($feedback['replied']) > 0){ echo '<span class="bx bx-check text-success bx-sm"></span> '; }echo htmlspecialchars($feedback['name']) ?></td>
                                            <td class="text-center feedbackEmail"><?php echo htmlspecialchars($feedback['email']) ?></td>
                                            <td class="text-center RfeedbackComment"><?php echo htmlspecialchars($feedback['comment']) ?></td>
                                            <td class="text-center RfeedbackRate"><?php echo htmlspecialchars($feedback['rate']) ?></td>
                                            <td class="text-center"><?php echo explodeDate(htmlspecialchars($feedback['date'])) ?></td>
                                            <td class="text-center"><?php echo getTime(htmlspecialchars($feedback['date'])) ?></td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-around">
                                                    <button class=" btn btn-success px-2 me-2" value="<?php echo htmlspecialchars($feedback['id']) ?>" id="replyFeedback"><span class="bx bx-send bx-sm"></span></button>
                                                    <button class=" btn btn-danger px-2" value="<?php echo htmlspecialchars($feedback['id']) ?>" id="deleteFeedback"><span class="bx bx-trash bx-sm"></span></button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Comment</th>
                                        <th class="text-center">Rate</th>
                                        <th class="text-center">date</th>
                                        <th class="text-center">Time</th>
                                        <th class="text-center"><span class="bx bx-cog bx-sm"></span></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div><!-- End Feedback lis -->
            <?php endif ?>

            <!-- Image info-->
            <div class="col-12">
                <div class="card overflow-auto">
                    <div class="filter d-flex ms-auto px-2 pt-2 ">
                        <a class="icon" href="#" data-mdb-toggle="dropdown" ><span class=" bx bx-filter-alt"></span></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start"><h6>Filter</h6></li>
                            <li><a class="dropdown-item filterImage filterActive" id="all">All</a></li>
                            <li><a class="dropdown-item filterImage" id="today">Today</a></li>
                            <li><a class="dropdown-item filterImage" id="thisMonth">This Month</a></li>
                            <li><a class="dropdown-item filterImage" id="thisYear">This Year</a></li>
                        </ul>
                    </div>
                    <div class="card-body pt-0 imageInfo">
                        <h5 class="card-title">Gallery Stats <span>| All</span></h5>
                        <table id="gallery" class="table table-striped " style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:10%"class="text-center">Image</th>
                                    <th style="width:15%"class="text-center">Uploaded By</th>
                                    <th style="width:15%"class="text-center">Email</th>
                                    <th style="width:10%"class="text-center">Dimension (HxW)</th>
                                    <th style="width:10%"class="text-center">Likes</th>
                                    <th style="width:10%"class="text-center">Date</th>
                                    <th style="width:10%"class="text-center">Time</th>
                                    <th style="width:10%" class="text-center"><span class="bx bx-cog bx-sm"></span></th>
                                </tr>
                            </thead>
                            <tbody id="galleryTBody">
                                <?php foreach($gallery as $photo): ?>
                                    <tr class="<?php if(htmlspecialchars($photo['user-id']) == $_SESSION["user-id"]){ echo'table-success';} ?> imageRow">
                                        <td class="text-center"><img src="assets/images/gallery/<?php echo htmlspecialchars($photo['image']) ?>" class="rounded-2 rowImage" height="50" alt="gallery" loading="lazy" ></td>
                                        <td class="text-center"><?php echo getName($conn, htmlspecialchars($photo['user-id'])) ?></td>
                                        <td class="text-center"><?php echo getEmail($conn, htmlspecialchars($photo['user-id'])) ?></td>
                                        <td class="text-center rowDimension"><?php echo htmlspecialchars($photo['dimension']) ?></td>
                                        <td class="text-center"><?php echo getLikes(htmlspecialchars($photo['id'])) ?></td>
                                        <td class="text-center rowDate"><?php echo explodeDate(htmlspecialchars($photo['upload-date'])) ?></td>
                                        <td class="text-center rowTime"><?php echo getTime(htmlspecialchars($photo['upload-date'])) ?></td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-around">
                                                <?php if(htmlspecialchars($photo['user-id']) == $_SESSION["user-id"] || $_SESSION["user-type"] == '323'): ?>
                                                    <button class=" btn btn-success px-2 me-2" value="<?php echo htmlspecialchars($photo['id']) ?>" id="editPic"><span class="bx bxs-edit-alt bx-sm"></span></button>
                                                    <button class=" btn btn-danger px-2" value="<?php echo htmlspecialchars($photo['id']) ?>" id="deletePic"><span class="bx bx-trash bx-sm"></span></button>
                                                <?php endif ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Uploaded By</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Dimension (HxW)</th>
                                    <th class="text-center">Likes</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Time</th>
                                    <th class="text-center"><span class="bx bx-cog bx-sm"></span></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div><!-- End Image info -->

            <!-- users info-->
            <div class="col-12">
                <div class="card overflow-auto">
                    <div class="filter d-flex ms-auto px-2 pt-2 ">
                        <a class="icon" href="#" data-mdb-toggle="dropdown" ><span class=" bx bx-filter-alt"></span></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start"><h6>Filter</h6></li>
                            <li><a class="dropdown-item filterUser filterActive" id="all">All</a></li>
                            <li><a class="dropdown-item filterUser" id="today">Today</a></li>
                            <li><a class="dropdown-item filterUser" id="thisMonth">This Month</a></li>
                            <li><a class="dropdown-item filterUser" id="thisYear">This Year</a></li>
                        </ul>
                    </div>
                    <div class="card-body pt-0 userInfo">
                        <h5 class="card-title">User Stats <span>| All</span></h5>
                        <table id="users" class="table table-striped " style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:8%"class="text-center">Image</th>
                                    <th style="width:15%"class="text-center">Username</th>
                                    <th style="width:13%"class="text-center">Email</th>
                                    <th style="width:10%"class="text-center">Role</th>
                                    <th style="width:10%"class="text-center">State</th>
                                    <th style="width:14%"class="text-center">Date</th>
                                    <th style="width:10%"class="text-center">Time</th>
                                </tr>
                            </thead>
                            <tbody id="usersTBody">
                                <?php foreach($userInfos as $userInfo): ?>
                                    <tr class=" imageRow" onclick="window.location.href = 'user-profile.php?id=<?php echo htmlspecialchars($userInfo['id'])?>'" style="cursor: pointer;">
                                        <td class="text-center"><img src="assets/images/dp/<?php echo htmlspecialchars($userInfo['image']) ?>" class="rounded-2 rowImage" height="50" alt="dp" loading="lazy" ></td>
                                        <td class="text-center"><?php echo htmlspecialchars($userInfo['username']) ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($userInfo['email']) ?></td>
                                        <td class="text-center"><?php if(htmlspecialchars($userInfo['type']) == 323){echo 'Admin';}elseif(htmlspecialchars($userInfo['type']) == 222){echo 'Editor';}else{echo 'User';} ?></td>
                                        <td class="text-center"><?php if(htmlspecialchars($userInfo['active'])== 1){echo "Active";}else{echo "Inactive";}  ?></td>
                                        <td class="text-center rowDate"><?php echo explodeDate(htmlspecialchars($userInfo['joined'])) ?></td>
                                        <td class="text-center rowTime"><?php echo getTime(htmlspecialchars($userInfo['joined'])) ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">State</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Time</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div><!-- End Image info -->

        </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">
            <div class="row">
                <!-- Featured users -->
                <div class="card pb-3 mb-3" >
                    <div class="card-body pt-3">
                        <h4>Featured Today</h4>
                        <hr>
                        <div class="row  g-2">
                            <?php foreach($FeaturedUsers as $FeaturedUser): $FeaturedUser = getuserprofile($FeaturedUser['id']);?>
                                <div class="card border border-dark me-2" style="max-width: 150px; min-width: 150px;">
                                <div class="card-body text-dark text-center px-2">
                                    <div onclick="window.location.href = 'user-profile.php?id=<?php echo htmlspecialchars($FeaturedUser['id'])?>'" style="cursor: pointer;">
                                        <img src="assets/images/dp/<?php echo $FeaturedUser["dp"]; ?>" alt="Profile" class="rounded-circle " height="70">
                                        <h6 class="card-title mt-1"><?php echo $FeaturedUser["name"]; ?></h6>
                                    </div>
                                    <div class="social-links mt-1 col-12 d-flex justify-content-around">
                                        <?php if(isset($FeaturedUser["twitter"])): ?>
                                        <a href="<?php echo $FeaturedUser["twitter"]; ?>" class="twitter"><i class="bx bxl-twitter bx-sm bx-tada-hover"></i></a>
                                        <?php endif ?>
                                        <?php if(isset($FeaturedUser["facebook"])): ?>
                                        <a  href="<?php echo $FeaturedUser["facebook"]; ?>" class="facebook"><i class="bx bxl-facebook-circle bx-sm"></i></a>
                                        <?php endif ?>
                                        <?php if(isset($FeaturedUser["instagram"])): ?>
                                        <a href="<?php echo $FeaturedUser["instagram"]; ?>" class="instagram"><i class="bx bxl-instagram bx-sm"></i></a>
                                        <?php endif ?>
                                        <?php if(isset($FeaturedUser["tiktok"])): ?>
                                        <a href="<?php echo $FeaturedUser["tiktok"]; ?>" class="tiktok"><i class="bx bxl-tiktok bx-sm"></i><i class="bx bxl-tiktok bx-sm"></i></a>
                                        <?php endif ?>
                                    </div>
                                </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
                <?php if($_SESSION['user-type'] == 323): ?>
                    <!-- Editor requests -->
                    <div class="card pb-3 mb-3" >
                        <div class="card-body pt-3">
                            <h4>Editor Requests</h4>
                            <hr>
                            <div class="row  g-2">
                                <?php foreach($requests as $request): $request = getuserprofile($request['user-id']);?>
                                    <div class="card border border-dark me-2" style="max-width: 150px; min-width: 150px;">
                                    <div class="card-body text-dark text-center px-2">
                                        <div onclick="window.location.href = 'user-profile.php?id=<?php echo htmlspecialchars($request['id'])?>'" style="cursor: pointer;">
                                            <img src="assets/images/dp/<?php echo $request["dp"]; ?>" alt="Profile" class="rounded-circle " height="70">
                                            <h6 class="card-title mt-1"><?php echo $request["name"]; ?></h6>
                                        </div>
                                        <div class=" mt-1 col-12 d-flex justify-content-around">
                                            <button class="btn btn-success px-2 py-1" id="makeEditor" value="<?php echo $request["id"]; ?>"><span class="bx bx-plus bx-sm "></span></button>
                                            <button class="btn btn-danger px-2 py-1" id="deleteRequest" value="<?php echo $request["id"]; ?>"><span class="bx bx-trash bx-sm "></span></button>
                                        </div>
                                    </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                    <!-- Feedback editing -->
                    <div class="card editFeedback pb-3 mb-3" style="display: none;">
                        <form method="post" enctype="multipart/form-data" id="editFeedbackForm">
                            <div class="card-body pt-3">
                                <h4> Reply To Feedback</h4>
                                <hr>
                                <div class=" d- justify-content-center mb-4">
                                    <div class="d-flex">
                                        <h6 class="col-5">To : <span class="text-dark feedbackName"></span></h6>
                                        <h6 class="col-7">Email : <span class="text-dark feedbackEmail"></span></h6>
                                    </div>
                                    <div class="border border-info rounded d-flex mb-1 p-1">
                                        <h6>Rate :</h6>
                                        <h6 class="feedbackRate text-dark ps-1">good</h6>
                                    </div>
                                    <div class="border border-info rounded p-1">
                                        <h6>Feedback :</h6>
                                        <span class="feedbackComment">some text here</span>
                                    </div>
                                </div>
                                <h4> Compose Email</h4>
                                <hr>
                                <input type="number" name="feedbackId"  style="display: none;" class="feedbackId">
                                <div >
                                    <div class=" mb-4">
                                        <label for="subject" class="form-label">Subject</label>
                                        <input type="text" id="subject" class="form-control" name="subject" required value="Thank you for your feedback.">
                                    </div>
                                    <div class=" mb-4">
                                        <label for="content" class="form-label">Content</label>
                                        <textarea class="form-control " id="content" name="content" rows="3"required></textarea>
                                    </div>
                                </div>
                                <button type="submit" id="sendEmail" class="btn btn-primary float-end mt-3 ">Send</button>
                                <button id="cancelFeedbackEdit" class="btn btn-light float-end mt-3 me-2">cancel</button>
                            </div>
                        </form>
                    </div>
                <?php endif ?>
                <!-- Image editing -->
                <div class="card editImage pb-3" style="display: none;">
                    <form method="post" enctype="multipart/form-data" id="editPicForm">
                        <div class="card-body pt-3">
                            <h4> Edit Photo</h4>
                            <hr>
                            <div class="image d-flex justify-content-center mb-4">
                                <div class="edit-pic-div form-outline">
                                    <img src="assets/images/landscaped.jpg" alt="photo" id="uploadPhoto" loading="lazy">
                                    <input type="file" id="uploadFile" name="image" accept=".jpg, .jpeg, .png">
                                    <!-- <label for="uploadFile" id="uploadPicBtn">Choose Photo</label> -->
                                </div>
                            </div>
                            <h4> Dimensions (hxw)</h4>
                            <hr>
                            <input type="number" name="imageId"  style="display: none;" class="imageId">
                            <input type="name" style="display: none;" name="editImage" value="editImage">
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
                            <button type="submit" id="savePicEdits" class="btn btn-primary float-end mt-3 ">Save</button>
                            <button id="cancelPicEdits" class="btn btn-light float-end mt-3 me-2">cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- End Right side columns -->

    </div>
</section>

</main><!-- End #main -->
<?php
    include("include/footer.php");
?>