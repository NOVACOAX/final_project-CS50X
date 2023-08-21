$(document).ready(function () {
    $('#feedbackTB').DataTable();
    $('#gallery').DataTable();
    $('#users').DataTable();

    // Remove image row
    $(document).on('click', '.remove-btn', function () {
        $(this).closest('.main-form').remove();
    });
    
    /* This is a jQuery function. It is used to add a new form when the user clicks on the add more
    button. */
    $(document).on('click', '.add-more-form', function () {
        $('.paste-new-forms').append('<div class="main-form mt-3 border-bottom">\
                        <div class="row">\
                            <div class="col-6 col-sm-3">\
                                <div class="form-group mb-2">\
                                    <div class="ms-3 mt-2 form-outline formPic">\
                                        <img src="assets/images/square.jpg" class="rounded-1" style="max-height: 70px; max-width: 80px;" alt="photo" id="ListPhoto" loading="lazy">\
                                        <input type="file" id="uploadFile" name="image[]"  accept=".jpg, .jpeg, .png"  >\
                                    </div>\
                                </div>\
                            </div>\
                            <div class="col-6 col-sm-4">\
                                <div class="form-group mb-2 mt-2">\
                                    <label for="">Dimension (HxW)</label>\
                                    <input type="text" name="dimension[]" id="dimension" class="form-control border-0" value="1x1" readonly>\
                                </div>\
                            </div>\
                            <div class="col-sm-5">\
                                <div class="form-group mb-2">\
                                    <br>\
                                    <button type="button" class="remove-btn btn btn-danger me-3"><span class="bx bx-trash bx-xs"></span></button>\
                                    <button type="button" class="edit-btn btn btn-success"><span class="bx bx-edit-alt bx-xs"></span></button>\
                                </div>\
                            </div>\
                        </div>\
                    </div>');
    });

    // Edit image row
    $(document).on('click', '.edit-btn', function () {
        const editor = document.querySelector('.editImage');
        // Get values 
        const form = $(this).closest('.main-form');
        const image = form.find('#uploadFile');
        const dimension = form.find('#dimension').val().split("x");
        const h = dimension[0];
        const w = dimension[1];

        // add a class to remember the form being edited 
        if(document.querySelector('.editing')){
            $(".main-form").removeClass("editing");
        }
        form.addClass("editing");

        // laod dimension
        editor.querySelectorAll('.form-check-input').checked = false;;
        switch(h) {
            case '2':
                editor.querySelector('#h2').checked = true;
                break;
            case '3':
                editor.querySelector('#h3').checked = true;
                break;
            case '4':
                editor.querySelector('#h4').checked = true;
                break;
            case '5':
                editor.querySelector('#h5').checked = true;
                break;
            default:
                editor.querySelector('#h1').checked = true;
        }
        switch(w) {
            case '2':
                editor.querySelector('#w2').checked = true;
                break;
            case '3':
                editor.querySelector('#w3').checked = true;
                break;
            case '4':
                editor.querySelector('#w4').checked = true;
                break;
            case '5':
                editor.querySelector('#w5').checked = true;
                break;
            default:
                editor.querySelector('#w1').checked = true;
        }

        // load image
        const pic = editor.querySelector('#uploadPhoto');
        const picDiv = editor.querySelector('.upload-pic-div');
        const picFile = editor.querySelector('#uploadFile');
        picFile.remove();
        $(image).clone().prependTo(picDiv);
        const picFile2 = editor.querySelector('#uploadFile');
        const choosedFile = picFile2.files[0];
        if (choosedFile) {
            const reader = new FileReader(); //FileReader is a predefined function of JS
            reader.addEventListener('load', function(){
                pic.setAttribute('src', reader.result);
            });
            reader.readAsDataURL(choosedFile);
        }else {
                pic.setAttribute('src', 'assets/images/landscaped.jpg');
        }
        //when you choose a image to upload
        picFile2.addEventListener('change', function(){
            //this refers to file
            const choosedFile = this.files[0];
            if (choosedFile) {
                const reader = new FileReader(); //FileReader is a predefined function of JS
                reader.addEventListener('load', function(){
                    pic.setAttribute('src', reader.result);
                });
                reader.readAsDataURL(choosedFile);
            } 
        });
    });

    // save edited values
    $(document).on('click', '.saveEdits', function () {
        // check if there is any image being edited
        if (document.querySelector('.editing')){
            // const editor = document.querySelector('.editImage');
            // Get form elements
            const form = document.querySelector('.editing');
            const image = form.querySelector('#uploadFile');
            const display = form.querySelector('#ListPhoto');
            const formPic = form.querySelector('.formPic')
            const dimension = form.querySelector('#dimension')
            
            // get edited values
            const editor = document.querySelector('.editImage');
            const picFile = editor.querySelector('#uploadFile');
            const eh = $("input[type='radio'][name='height']:checked").val();
            const ew = $("input[type='radio'][name='width']:checked").val();
            
            // change the dimension 
            const newD = `${eh}x${ew}`;
            $(dimension).val(newD);
    
            // change the imgae and input
            image.remove();
            $(picFile).clone().appendTo(formPic);
            const picFile2 = form.querySelector('#uploadFile');
            const choosedFile = picFile2.files[0];
            if(choosedFile) {
                const reader = new FileReader(); //FileReader is a predefined function of JS
                reader.addEventListener('load', function(){
                    display.setAttribute('src', reader.result);
                });
                reader.readAsDataURL(choosedFile);
            }
        }
    });

    // Display selected profile picture
    if(document.querySelector('.profile-pic-div')){
        const imgDiv = document.querySelector('.profile-pic-div');
        const img = document.querySelector('#photo');
        const file = document.querySelector('#file');
        const uploadBtn = document.querySelector('#uploadBtn');
        //if you hover on img div 
        imgDiv.addEventListener('mouseenter', function(){
            uploadBtn.style.display = "block";
        });
        //if you hover out from img div
        imgDiv.addEventListener('mouseleave', function(){
            uploadBtn.style.display = "none";
        });
        //when you choose a image to upload
        file.addEventListener('change', function(){
            //this refers to file
            const choosedFile = this.files[0];
            if (choosedFile) {
                const reader = new FileReader(); //FileReader is a predefined function of JS
                reader.addEventListener('load', function(){
                    img.setAttribute('src', reader.result);
                });
                reader.readAsDataURL(choosedFile);
            }
        });
    }
    
    // Delete pic
    $(document).on('click', '#deletePic', function (e) {
        e.preventDefault();
        if(confirm('Are you sure you want to delete this image?')) {
            var picId = $(this).val();
            var picRow = $(this).closest('.imageRow');
            $.ajax({
                url: "ajaxCalls.php",
                type: "POST",
                data: {
                    'delete_image': true,
                    'pic_id': picId
                },
                success: function (response) {
                    var res = jQuery.parseJSON(response);
                    if(res.status == 500) {
                        picRow.remove();
                    }
                }
            });
        }
    });
    // Edit pic from dashboard
    $(document).on('click', '#editPic', function (e) {
        e.preventDefault();
        const editor = document.querySelector('.editImage');
        var picRow = $(this).closest('.imageRow');
        var image = picRow.find('.rowImage');
        var dimension = picRow.find('.rowDimension').html().split("x");
        const h = dimension[0];
        const w = dimension[1];
        var id = $(this).val();
        var imageId = $(editor).find('.imageId');

        // Display edit div
        editor.style.display = "block";
        // add id to id input 
        $(imageId).val(id);
        // add a class to remember the form being edited 
        if(document.querySelector('.editing')){
            $(".imageRow").removeClass("editing");
        }
        picRow.addClass("editing");

        // laod dimension
        editor.querySelectorAll('.form-check-input').checked = false;;
        switch(h) {
            case '2':
                editor.querySelector('#h2').checked = true;
                break;
            case '3':
                editor.querySelector('#h3').checked = true;
                break;
            case '4':
                editor.querySelector('#h4').checked = true;
                break;
            case '5':
                editor.querySelector('#h5').checked = true;
                break;
            default:
                editor.querySelector('#h1').checked = true;
        }
        switch(w) {
            case '2':
                editor.querySelector('#w2').checked = true;
                break;
            case '3':
                editor.querySelector('#w3').checked = true;
                break;
            case '4':
                editor.querySelector('#w4').checked = true;
                break;
            case '5':
                editor.querySelector('#w5').checked = true;
                break;
            default:
                editor.querySelector('#w1').checked = true;
        }

        // load image
        const pic = editor.querySelector('#uploadPhoto');
        const picFile = editor.querySelector('#uploadFile');
        pic.setAttribute('src', image.attr('src'));
        //when you choose a image to upload
        picFile.addEventListener('change', function(){
            //this refers to file
            const choosedFile = this.files[0];
            if (choosedFile) {
                const reader = new FileReader(); //FileReader is a predefined function of JS
                reader.addEventListener('load', function(){
                    pic.setAttribute('src', reader.result);
                });
                reader.readAsDataURL(choosedFile);
            } 
        });

    });
    // clear dasshboard pic editor
    $(document).on('click', '#cancelPicEdits', function (e) {
        e.preventDefault();
        $("#editPicForm").load('dashboard.php' + " #editPicForm");
        const editor = document.querySelector('.editImage');
        // Hide edit div
        editor.style.display = "none";
        if(document.querySelector('.editing')){
            $(".imageRow").removeClass("editing");
        }
    });
    // save edited values from dashboard
    $(document).on('click', '#savePicEdits', function (e) {
        e.preventDefault();
        // check if there is any image being edited
        if (document.querySelector('.editing')){
            const picRow = document.querySelector('.editing');
            var dimension = $(picRow).find('.rowDimension');
            var image = $(picRow).find('.rowImage');
            // var date = $(picRow).find('.rowDate');
            // var time = $(picRow).find('.rowTime');
            const editor = document.querySelector('.editImage');
            
            // get edited values
            var form_data = new FormData(document.getElementById("editPicForm"));
            const eh = $("input[type='radio'][name='height']:checked").val();
            const ew = $("input[type='radio'][name='width']:checked").val();
            // change the dimension 
            const newD = `${eh}x${ew}`;
            $(dimension).text(newD);
            // Change date and time
            // $(date).text(formatDate(Date()))
            // $(time).text(formatTime(Date()))
            // save changes and return image name
            $.ajax({
                url: "ajaxCalls.php",
                type: "POST",
                data: form_data,
                contentType: false,
                cache: false,
                processData:false,
                success: function (response) {
                    var res = jQuery.parseJSON(response);
                    if(res.status == 500) {
                        $(image).attr("src", res.src);
                    }
                    $(".editImage").load('dashboard.php' + " #editPicForm");
                    editor.style.display = "none";
                }
            });
            if(document.querySelector('.editing')){
                $(".imageRow").removeClass("editing");
            }
        }
    });
    // Filter gallery stats
    $(document).on('click', '.filterImage', function (e) {
        e.preventDefault();
        var Gtable = document.querySelector('.imageInfo');
        var filter = $(this).attr('id');
        var btns = document.querySelectorAll('.filterImage');
        $(btns).removeClass("filterActive");
        var btn = $(this);
        $.ajax({
            url: "ajaxCalls.php",
            type: "POST",
            data: {'galleryFilter': true,
                    'filter': filter},
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if(res.status == 500) {
                    $(btn).addClass("filterActive");
                    $(Gtable).replaceWith(res.data);
                    $('#gallery').DataTable();
                }
            }
        });
    });


    // Reply to feedback
    $(document).on('click', '#replyFeedback', function (e) {
        e.preventDefault();
        const editor = document.querySelector('.editFeedback');
        var FeedbackRow = $(this).closest('.feedbackRow');
        var feedbackId = $(this).val();
        var Fname = FeedbackRow.find(".feedbackName").text();
        var Femail = FeedbackRow.find(".feedbackEmail").html();
        var Frate = FeedbackRow.find(".RfeedbackRate").html();
        var Fcomment = FeedbackRow.find(".RfeedbackComment").html();
        var Ename = editor.querySelector(".feedbackName");
        var Eemail = editor.querySelector(".feedbackEmail");
        var Erate = editor.querySelector(".feedbackRate");
        var Ecomment = editor.querySelector(".feedbackComment");
        var fId = editor.querySelector(".feedbackId");
        // Display edit div
        editor.style.display = "block";
        // add a class to remember the form being edited 
        if(document.querySelector('.replying')){
            $(".feedbackRow").removeClass("replying");
        }
        FeedbackRow.addClass("replying");
        // set feedbackid
        $(fId).val(feedbackId);
        // display name, email, comment, rate
        $(Ename).text(Fname);
        $(Eemail).text(Femail);
        $(Erate).text(Frate);
        $(Ecomment).text(Fcomment);

    });
    // submit reply for feedback
    $(document).on('click', '#sendEmail', function (e) {
        e.preventDefault();
        // check if there is any image being edited
        if (document.querySelector('.replying')){
            const editor = document.querySelector('.editFeedback');
            var Ename = $(editor).find(".feedbackName").html();
            var Eemail = $(editor).find(".feedbackEmail").html();
            var subject = $(editor).find("#subject").val();
            var content = $(editor).find("#content").val();
            var fid = $(editor).find(".feedbackId").val();

            $.ajax({
                url: "sendmail.php",
                type: "POST",
                data: {
                    'sendMail': true,
                    'name': Ename,
                    'email': Eemail,
                    'subject': subject,
                    'body': content },
                success: function (response) {
                    var res = jQuery.parseJSON(response);
                    if(res.status == 500) {
                        $.ajax({
                            url: "ajaxCalls.php",
                            type: "POST",
                            data: {
                                'MailSent': true,
                                'fid': fid }
                        });
                    }
                    $(".editFeedback").load('dashboard.php' + " #editFeedbackForm");
                    editor.style.display = "none";
                }
            });
            if(document.querySelector('.replying')){
                $(".replying > .feedbackName").html('<span class="bx bx-check text-success bx-sm"></span> ' + Ename);
                $(".feedbackRow").removeClass("replying");
            }
        }
    });
    // Delete feedback
    $(document).on('click', '#deleteFeedback', function (e) {
        e.preventDefault();
        // if(confirm('Are you sure you want to delete this feedback?')) {
            var id = $(this).val();
            var feedbackRow = $(this).closest('.feedbackRow');
            $.ajax({
                url: "ajaxCalls.php",
                type: "POST",
                data: {
                    'delete_feedback': true,
                    'id': id
                },
                success: function (response) {
                    var res = jQuery.parseJSON(response);
                    if(res.status == 500) {
                        feedbackRow.remove();
                    }
                }
            });
        // }
    });
    // cancel reply to feedback
    $(document).on('click', '#cancelFeedbackEdit', function (e) {
        e.preventDefault();
        $("#editFeedbackForm").load('dashboard.php' + " #editFeedbackForm");
        const editor = document.querySelector('.editFeedback');
        // Hide edit div
        editor.style.display = "none";
        if(document.querySelector('.replying')){
            $(".feedbackRow").removeClass("replying");
        }
    });
    // Filter feedback
    $(document).on('click', '.filterFeedback', function (e) {
        e.preventDefault();
        var Ftable = document.querySelector('.feedback');
        var filter = $(this).attr('id');
        var btns = document.querySelectorAll('.filterFeedback');
        $(btns).removeClass("filterActive");
        var btn = $(this);
        $.ajax({
            url: "ajaxCalls.php",
            type: "POST",
            data: {'feedbackFilter': true,
                    'filter': filter},
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if(res.status == 500) {
                    $(btn).addClass("filterActive");
                    $(Ftable).replaceWith(res.data);
                    $('#feedbackTB').DataTable();
                }
            }
        });
    });

    // Filter users stats
    $(document).on('click', '.filterUser', function (e) {
        e.preventDefault();
        var Gtable = document.querySelector('.userInfo');
        var filter = $(this).attr('id');
        var btns = document.querySelectorAll('.filterUser');
        $(btns).removeClass("filterActive");
        var btn = $(this);
        $.ajax({
            url: "ajaxCalls.php",
            type: "POST",
            data: {'userFilter': true,
                    'filter': filter},
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if(res.status == 500) {
                    $(btn).addClass("filterActive");
                    $(Gtable).replaceWith(res.data);
                    $('#users').DataTable();
                }
            }
        });
    });

    // Toggle settings tab
    $(document).on('click', '#UPSettingsToggle', function (e) {
        e.preventDefault();
        $("#UPSettingsTab").toggle();
    });
    // Display DP on change from user-profile.php
    // Display selected profile picture
    if(document.querySelector('#settingsPhoto')){
        const spic = document.querySelector('#settingsPhoto');
        const spicFile = document.querySelector('#settingsFile');
        spicFile.addEventListener('change', function(){
            //this refers to file
            const choosedFile = this.files[0];
            if (choosedFile) {
                const reader = new FileReader(); //FileReader is a predefined function of JS
                reader.addEventListener('load', function(){
                    spic.setAttribute('src', reader.result);
                });
                reader.readAsDataURL(choosedFile);
            } 
        });
    }
    //  Follow user
    $(document).on('click', '#followUser', function (e) {
        e.preventDefault();
        var count = document.querySelector('.followersCount');
        count = $(count).html();
        var btn = document.querySelector('#followUser');
        var id = $(this).val();
        $.ajax({
            url: "ajaxCalls.php",
            type: "POST",
            data: {
                'followuser': true,
                'id': id
            },
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if(res.status == 500) {
                    count++;
                    $(".followersCount").html(count);
                    $(btn).html('Unfollow');
                    $(btn).attr('id', 'unfollowUser');
                }
            }
        });
    });
    //  Unfollow user
    $(document).on('click', '#unfollowUser', function (e) {
        e.preventDefault();
        var count = document.querySelector('.followersCount');
        count = $(count).html();
        var btn = document.querySelector('#unfollowUser');
        var id = $(this).val();
        $.ajax({
            url: "ajaxCalls.php",
            type: "POST",
            data: {
                'unfollowuser': true,
                'id': id
            },
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if(res.status == 500) {
                    count--;
                    $(".followersCount").html(count);
                    $(btn).html('Follow');
                    $(btn).attr('id', 'followUser');
                }
            }
        });
    });
    // Delete user
    $(document).on('click', '#deleteUser', function (e) {
        e.preventDefault();
        if(confirm('Are you sure you want to delete this account?')) {
            if(confirm('This action cannot be undone . All your data will be lost?')) {
                var id = document.querySelector('#userId');
                var id = $(id).val();
                $.ajax({
                    url: "ajaxCalls.php",
                    type: "POST",
                    data: {
                        'delete_user': true,
                        'id': id
                    },
                    success: function (response) {
                        var res = jQuery.parseJSON(response);
                        if(res.status == 500) {
                            location.replace("index.php");
                        }
                    }
                });
            }
        }
    });
    // Request editor status
    $(document).on('click', '#RequestED', function (e) {
        e.preventDefault();
        if(confirm('A request will be sent to the admin.')) {
                var id = document.querySelector('#userId');
                var id = $(id).val();
                $.ajax({
                    url: "ajaxCalls.php",
                    type: "POST",
                    data: {
                        'EDrequest': true,
                        'id': id
                    }
                });
        }
    });
    // Accept editor request
    $(document).on('click', '#makeEditor', function (e) {
        e.preventDefault();
        var card = $(this).closest('.card');
        var id = $(this).attr('value');
        $.ajax({
            url: "ajaxCalls.php",
            type: "POST",
            data: {
                'makeEditor': true,
                'id': id
            },
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if(res.status == 500) {
                    $(card).remove();
                }
            }
        });
    });
    // Accept delete request
    $(document).on('click', '#deleteRequest', function (e) {
        e.preventDefault();
        var card = $(this).closest('.card');
        var id = $(this).attr('value');
        $.ajax({
            url: "ajaxCalls.php",
            type: "POST",
            data: {
                'deleteRequest': true,
                'id': id
            },
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if(res.status == 500) {
                    $(card).remove();
                }
            }
        });
    });
    // Deactivate account
    $(document).on('click', '#Deactivate', function (e) {
        e.preventDefault();
        if(confirm('Are you sure you want to deactivate this account?')) {
            if(confirm("Your posts or acount won't be visible until you reactivate your acount")) {
                var id = document.querySelector('#userId');
                var id = $(id).val();
                $.ajax({
                    url: "ajaxCalls.php",
                    type: "POST",
                    data: {
                        'deactivate_user': true,
                        'id': id
                    },
                    success: function (response) {
                        var res = jQuery.parseJSON(response);
                        if(res.status == 500) {
                            location.replace("index.php");
                        }
                    }
                });
            }
        }
    });
    //  Like post
    $(document).on('click', '#unliked', function (e) {
        e.preventDefault();
        var p = $(this);
        count = $(this).text();
        var id = $(this).attr('value');
        $.ajax({
            url: "ajaxCalls.php",
            type: "POST",
            data: {
                'likePost': true,
                'id': id
            },
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if(res.status == 500) {
                    count++;
                    $(p).replaceWith('<p class="my-1" id="liked" value="'+ id +'"><i class="bx bxs-heart bx-tada-hover text-danger"></i>'+ count +'</p>');
                }
            }
        });
    });
    //  Unlike post
    $(document).on('click', '#liked', function (e) {
        e.preventDefault();
        var p = $(this);
        count = $(this).text();
        var id = $(this).attr('value');
        $.ajax({
            url: "ajaxCalls.php",
            type: "POST",
            data: {
                'unlikePost': true,
                'id': id
            },
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if(res.status == 500) {
                    count--;
                    $(p).replaceWith('<p class="my-1" id="unliked" value="'+ id +'"><i class="bx bx-heart bx-tada-hover text-danger"></i>'+ count +'</p>');
                }
            }
        });
    });
});

// Get current date yyyy-mm-dd 
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();
    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;
    return [year, month, day].join('-');
}

// Get current time hh:mm:ss 
function formatTime(date) {
    var date = new Date(date),
        hour = '' + date.getHours(),
        min = '' + date.getMinutes(),
        sec = '' + date.getSeconds();
    if (hour.length < 2) 
        hour = '0' + hour;
    if (min.length < 2) 
        min = '0' + min;
    if (sec.length < 2) 
        sec = '0' + sec;
    return [hour, min ,sec].join(':');
}

// Sidebar toggle
function toggleSidebar(){
    var element = document.querySelector('body');
    element.classList.toggle("toggle-sidebar");
}

// hide notification
function hideMessage(Element){
    Element.closest('li').remove();
}
