<?php
    session_start();
    require("db/dbconnect.php");
    require("include/functions.php");

    //  Ajax call to delete image
    if(isset($_POST['delete_image'])) {
        global $conn;
        $id = test_input($_POST['pic_id']);
        $imageName = imageName($conn, $id);
        $result = deleteImage($conn, $id);
        if($result) {
            $res = ['status' => 500];
            echo json_encode($res);
            unlink("assets/images/gallery/".$imageName);
            return;
        }else {
            $_SESSION['message'] = "Error deleting photo!";
            return;
        }
    }
    //  Ajax call to edit image
    if(isset($_POST['editImage'])) {
        global $conn;
        $id = test_input($_POST['imageId']);
        $height = test_input($_POST['height']);
        $width = test_input($_POST['width']);
        $dimension = $height . "x" . $width;
        // Update dimension
        $sql = "UPDATE `gallery` SET `dimension`='$dimension' WHERE `id`=$id";
        $result  = mysqli_query($conn, $sql);
        // Update image
        if(isset($_FILES["image"]["name"])){
            $result = updateImage($id, $_FILES["image"], $conn);
        }else{
            $result = false;
        }
        if($result) {
            $res = ['status' => 500,
                    'src' => $result];
            echo json_encode($res);
            return;
        }else {
            $_SESSION['message'] = "Error deleting photo!";
            return;
        }
    }
    //  Ajax call to filter gallery stats
    if(isset($_POST['galleryFilter'])) {
        global $conn;
        $today = date('Y-m-d');
        $today .= "%";
        $thisMonth = date('Y-m');
        $thisMonth .= "%";
        $thisYear = date('Y');
        $thisYear .= "%";
        // Get gallery info 
        switch($_POST['filter']){
            case "today":
                $sql ="SELECT * FROM `gallery` WHERE `upload-date` LIKE '$today'";
                $Fname = "Today";
                break;
            case "thisMonth":
                $sql ="SELECT * FROM `gallery` WHERE `upload-date` LIKE '$thisMonth'";
                $Fname = "This Month";
                break;
            case "thisYear":
                $sql ="SELECT * FROM `gallery` WHERE `upload-date` LIKE '$thisYear'";
                $Fname = "This Year";
                break;
            default:
                $sql ="SELECT * FROM `gallery`";
                $Fname = "All";
                break;
        }
        $result = mysqli_query($conn, $sql);
        $gallery = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        if($gallery){
            $Trows = " ";
            foreach($gallery as $photo){
                if(htmlspecialchars($photo['user-id']) == $_SESSION["user-id"]){ 
                    $Rcolor = "table-success";
                }else {
                    $Rcolor = " ";
                }
                if(htmlspecialchars($photo['user-id']) == $_SESSION["user-id"] || $_SESSION["user-type"] == '323'){
                    $edit = <<<EOD
                            <button class=" btn btn-success px-2 me-2" value="{$photo['id']}" id="editPic"><span class="bx bxs-edit-alt bx-sm"></span></button>
                            <button class=" btn btn-danger px-2" value="{$photo['id']}" id="deletePic"><span class="bx bx-trash bx-sm"></span></button>
                        EOD;
                }else {
                    $edit = " ";
                }
                $Uname = getName($conn, htmlspecialchars($photo['user-id']));
                $Uemail = getEmail($conn, htmlspecialchars($photo['user-id']));
                $Pdate = explodeDate(htmlspecialchars($photo['upload-date']));
                $Ptime = getTime(htmlspecialchars($photo['upload-date']));
                $Trow = <<<EOD
                        <tr class="{$Rcolor} imageRow">
                            <td class="text-center"><img src="assets/images/gallery/{$photo['image']}" class="rounded-2 rowImage" height="50" alt="gallery" loading="lazy" ></td>
                            <td class="text-center">{$Uname}</td>
                            <td class="text-center">{$Uemail}</td>
                            <td class="text-center rowDimension">{$photo['dimension']}</td>
                            <td class="text-center">{$photo['id']}</td>
                            <td class="text-center rowDate">{$Pdate}</td>
                            <td class="text-center rowTime">{$Ptime}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-around">
                                    {$edit}
                                </div>
                            </td>
                        </tr>
                    EOD;
                    $Trows .= $Trow;
            }
            $data  = <<<EOD
                        <div class="card-body pt-0 imageInfo">
                            <h5 class="card-title">Gallery Stats <span>| {$Fname}</span></h5>
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
                                    {$Trows}
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
                EOD;
            $res = ['status' => 500,
                    'data' =>  utf8_encode($data)];
            echo json_encode($res);
            return;
        }else{
            $data  = <<<EOD
                        <div class="card-body pt-0 imageInfo">
                            <h5 class="card-title">Gallery Stats <span>| {$Fname}</span></h5>
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
                EOD;
            $res = ['status' => 500,
                    'data' =>  utf8_encode($data)];
            echo json_encode($res);
            return;
        }
    }

    //  Ajax call to filter user stats
    if(isset($_POST['userFilter'])) {
        global $conn;
        $today = date('Y-m-d');
        $today .= "%";
        $thisMonth = date('Y-m');
        $thisMonth .= "%";
        $thisYear = date('Y');
        $thisYear .= "%";
        // Get user info 
        switch($_POST['filter']){
            case "today":
                $sql ="SELECT `id`, `username`, `email`, `image`, `active`, `type`, `joined` FROM `user` WHERE `joined` LIKE '$today'";
                $Fname = "Today";
                break;
            case "thisMonth":
                $sql ="SELECT `id`, `username`, `email`, `image`, `active`, `type`, `joined` FROM `user` WHERE `joined` LIKE '$thisMonth'";
                $Fname = "This Month";
                break;
            case "thisYear":
                $sql ="SELECT `id`, `username`, `email`, `image`, `active`, `type`, `joined` FROM `user` WHERE `joined` LIKE '$thisYear'";
                $Fname = "This Year";
                break;
            default:
                $sql ="SELECT `id`, `username`, `email`, `image`, `active`, `type`, `joined` FROM `user`";
                $Fname = "All";
                break;
        }
        $result = mysqli_query($conn, $sql);
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        if($users){
            $Trows = " ";
            foreach($users as $user){
                $Pdate = explodeDate(htmlspecialchars($user['joined']));
                $Ptime = getTime(htmlspecialchars($user['joined']));
                $Trow = <<<EOD
                        <tr class=" imageRow" onclick="window.location.href = 'user-profile.php?id={$user['id']}'">
                            <td class="text-center"><img src="assets/images/dp/{$user['image']}" class="rounded-2 rowImage" height="50" alt="gallery" loading="lazy" ></td>
                            <td class="text-center">{$user['username']}</td>
                            <td class="text-center">{$user['email']}</td>
                            <td class="text-center ">{$user['type']}</td>
                            <td class="text-center">{$user['active']}</td>
                            <td class="text-center rowDate">{$Pdate}</td>
                            <td class="text-center rowTime">{$Ptime}</td>
                        </tr>
                    EOD;
                    $Trows .= $Trow;
            }
            $data  = <<<EOD
                        <div class="card-body pt-0 userInfo">
                            <h5 class="card-title">User Stats <span>| {$Fname}</span></h5>
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
                                    {$Trows}
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
                EOD;
            $res = ['status' => 500,
                    'data' =>  utf8_encode($data)];
            echo json_encode($res);
            return;
        }else{
            $data  = <<<EOD
                    <div class="card-body pt-0 userInfo">
                        <h5 class="card-title">User Stats <span>| {$Fname}</span></h5>
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
                EOD;
            $res = ['status' => 500,
                    'data' =>  utf8_encode($data)];
            echo json_encode($res);
            return;
        }
    }

    //  Ajax call to filter feedback
    if(isset($_POST['feedbackFilter'])) {
        global $conn;
        $today = date('Y-m-d');
        $today .= "%";
        $thisMonth = date('Y-m');
        $thisMonth .= "%";
        $thisYear = date('Y');
        $thisYear .= "%";
        // Get feedback info 
        switch($_POST['filter']){
            case "today":
                $sql ="SELECT * FROM `feedback` WHERE `date` LIKE '$today'";
                $Fname = "Today";
                break;
            case "thisMonth":
                $sql ="SELECT * FROM `feedback` WHERE `date` LIKE '$thisMonth'";
                $Fname = "This month";
                break;
            case "thisYear":
                $sql ="SELECT * FROM `feedback` WHERE `date` LIKE '$thisYear'";
                $Fname = "This Year";
                break;
            case "notReplied":
                $sql ="SELECT * FROM `feedback` WHERE `replied`= 0";
                $Fname = "Not Replied";
                break;
            default:
                $sql ="SELECT * FROM `feedback`";
                $Fname = "All";
                break;
        }
        $result = mysqli_query($conn, $sql);
        $feedbacks = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        if($feedbacks){
            $Trows = " ";
            foreach($feedbacks as $feedback){
                $Fdate = explodeDate(htmlspecialchars($feedback['date']));
                $Ftime = getTime(htmlspecialchars($feedback['date']));
                if(htmlspecialchars($feedback['replied']) > 0){
                    $replied =  '<span class="bx bx-check text-success bx-sm"></span> '; 
                }else{
                    $replied = '';
                }
                $Trow = <<<EOD
                            <tr class="feedbackRow">
                                <td class="text-center feedbackName">{$replied} {$feedback['name']}</td>
                                <td class="text-center feedbackEmail">{$feedback['email']}</td>
                                <td class="text-center RfeedbackComment">{$feedback['comment']}</td>
                                <td class="text-center RfeedbackRate">{$feedback['rate']}</td>
                                <td class="text-center">{$Fdate}</td>
                                <td class="text-center">{$Ftime}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-around">
                                        <button class=" btn btn-success px-2 me-2" value="{$feedback['id']}" id="replyFeedback"><span class="bx bx-send bx-sm"></span></button>
                                        <button class=" btn btn-danger px-2" value="{$feedback['id']}" id="deleteFeedback"><span class="bx bx-trash bx-sm"></span></button>
                                    </div>
                                </td>
                            </tr>
                        EOD;
                $Trows .= $Trow;
            }
            $data  = <<<EOD
                        <div class="card-body pt-0 feedback">
                        <h5 class="card-title">Feedback <span>| {$Fname}</span></h5>
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
                                {$Trows}
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
                EOD;
            $res = ['status' => 500,
                    'data' =>  utf8_encode($data)];
            echo json_encode($res);
            return;
        }else{
            $data  = <<<EOD
                        <div class="card-body pt-0 feedback">
                        <h5 class="card-title">Feedback <span>| {$Fname}</span></h5>
                        <table id="feedbackTB" class="table table-striped " style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:20%">Name</th>
                                    <th style="width:20%">Email</th>
                                    <th style="width:30%">Comment</th>
                                    <th style="width:10%">Rate</th>
                                    <th style="width:10%">Date</th>
                                    <th style="width:10%">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Comment</th>
                                    <th>Rate</th>
                                    <th>date</th>
                                    <th>Time</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                EOD;
            $res = ['status' => 500,
                    'data' =>  utf8_encode($data)];
            echo json_encode($res);
            return;
        }
    }
    //  Ajax call to mark feedback as sent
    if(isset($_POST['MailSent'])) {
        global $conn;
        $fid = test_input($_POST['fid']);
        $sql = "UPDATE `feedback` SET `replied` = `replied` + 1 WHERE `id` =$fid";
        $result  = mysqli_query($conn, $sql);
    }
    //  Ajax call to delete feedback
    if(isset($_POST['delete_feedback'])) {
        global $conn;
        $id = test_input($_POST['id']);
        $result = deleteFeedback($conn, $id);
        if($result) {
            $res = ['status' => 500];
            echo json_encode($res);
            return;
        }else {
            $_SESSION['message'] = "Error deleting feedback!";
            return;
        }
    }


    // Ajax call to delete user
    if(isset($_POST['delete_user'])) {
        $id = test_input($_POST['id']);
        $result = deleteUser($id);
        if($result) {
            $res = ['status' => 500];
            echo json_encode($res);
            return;
        }else {
            $_SESSION['message'] = "Error deleting user!";
            return;
        }
    }
    // Ajax call to deactivate user
    if(isset($_POST['deactivate_user'])) {
        $id = test_input($_POST['id']);
        $result = deactivateUser($id);
        if($result) {
            $res = ['status' => 500];
            echo json_encode($res);
            return;
        }else {
            $_SESSION['message'] = "Error deactivating user!";
            return;
        }
    }
    // Ajax call to request editor status
    if(isset($_POST['EDrequest'])) {
        $id = test_input($_POST['id']);
        EDrequest($id);
        return;
    }
    // Ajax call to make editor
    if(isset($_POST['makeEditor'])) {
        $id = test_input($_POST['id']);
        $result = makeEditor($id);
        if($result) {
            $res = ['status' => 500];
            echo json_encode($res);
            return;
        }else {
            $_SESSION['message'] = "Something went wrong!";
            return;
        }
    }
    // Ajax call to delete request
    if(isset($_POST['deleteRequest'])) {
        $id = test_input($_POST['id']);
        $result = deleteRequest($id);
        if($result) {
            $res = ['status' => 500];
            echo json_encode($res);
            return;
        }else {
            $_SESSION['message'] = "Something went wrong!";
            return;
        }
    }
    // Ajax call to Follow user
    if(isset($_POST['followuser'])) {
        $id = test_input($_POST['id']);
        $result = followUser($id);
        if($result){
            $res = ['status' => 500];
            echo json_encode($res);
        }
        return;
    }
    // Ajax call to unfollow user
    if(isset($_POST['unfollowuser'])) {
        $id = test_input($_POST['id']);
        $result = unfollowUser($id);
        if($result){
            $res = ['status' => 500];
            echo json_encode($res);
        }
        return;
    }
    // Ajax call to like post
    if(isset($_POST['likePost'])) {
        $id = test_input($_POST['id']);
        if(postliked($id)){
            $result = true;
        }else{
            $result = likePost($id);
        }
        if($result){
            $res = ['status' => 500];
            echo json_encode($res);
        }
        return;
    }
    // Ajax call to unlike post
    if(isset($_POST['unlikePost'])) {
        $id = test_input($_POST['id']);
        if(postliked($id)){
            $result = unlikePost($id);
        }else{
            $result = true;
        }
        if($result){
            $res = ['status' => 500];
            echo json_encode($res);
        }
        return;
    }

?>