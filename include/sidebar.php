<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <?php if($_SESSION["user-type"] == 222 || $_SESSION["user-type"] == 323): ?>
        <li class="nav-item">
        <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) != "dashboard.php"){?>collapsed<?php } ?>" href="dashboard.php">
            <i class="bx bxs-grid-alt bx-sm"></i>
            <span>Dashboard</span>
        </a>
        </li>
        <?php endif ?>
        <!-- Upload image -->
        <li class="nav-item">
        <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) != "uploadPics.php"){?>collapsed<?php } ?>" href="uploadPics.php">
            <i class="bx bxs-image-add bx-sm"></i>
            <span>Upload Photo</span>
        </a>

        <li class="nav-item">
            <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) != "user-profile.php"){?>collapsed<?php } ?>" href="user-profile.php">
                <i class="fa fa-user bx-sm"></i>
                <span>Profile</span>
            </a>
        </li><!-- End Profile Page Nav -->
        
        <li class="nav-heading">Pages</li>

        <li class="nav-item">
        <a class="nav-link collapsed" href="signup.php">
            <i class="fa fa-user-plus bx-sm"></i>
            <span>Register</span>
        </a>
        </li><!-- End Register Page Nav -->

        <li class="nav-item">
        <a class="nav-link collapsed" href="login.php">
            <i class="bx bxs-log-in-circle bx-sm"></i>
            <span>Login</span>
        </a>
        
        <li class="nav-item">
            <a class="nav-link collapsed" href="404.php">
                <i class="fa fa-exclamation-circle bx-sm"></i>
                <span>Error 404</span>
            </a>
        </li><!-- End Error 404 Page Nav -->
        </li><!-- End Login Page Nav -->
        <li class="nav-item">
        <a class="nav-link collapsed" href="logout.php">
            <i class="bx bxs-log-out-circle bx-sm"></i>
            <span>Logout</span>
        </a>
        </li><!-- End Login Page Nav -->
    </ul>
</aside><!-- End Sidebar-->