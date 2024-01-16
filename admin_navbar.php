<?php


// Check if the user is logged in and has the 'admin' role
if (isset($_SESSION['user']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $username = $_SESSION['user'];
    $role = $_SESSION['role'];

    // Fetch the profile picture BLOB data from the database based on the username
    require_once "config/db_conn.php"; // Adjust the path as needed

    $sql = "SELECT profilepic FROM user WHERE Username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $userData = mysqli_fetch_assoc($result);
        $profilePictureBlob = $userData['profilepic'];

        // Convert the BLOB data to base64 encoding
        $profilePicturePath = 'data:image/jpeg;base64,' . base64_encode($profilePictureBlob);
    } else {
        // Handle the database query error
        $profilePicturePath = 'default_profilepic.jpg'; // Provide a default image path
    }

} else {
    // Redirect to the home page or an error page
    header('Location: home.php'); // Adjust the path as needed
    exit();
}
?>


<!-- navbar -->
<nav class="navbar">
        <div class="logo_item">
          <i class="bx bx-menu" id="sidebarOpen"></i>
          <img src="img\logo.png" alt="">My Orchid
        </div>

        <div class="navbar_content">
          <i class='bx bx-sun' id="darkLight"></i>
          
          <!-- User Dropdown -->
        <div class="dropdown">
            <div class="navbar-dropdown-toggle hide-arrow" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar avatar-online">
                    <img src="<?php echo $profilePicturePath; ?>" alt="" class="w-px-30 h-auto rounded-circle" />
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="#">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    <img src="<?php echo $profilePicturePath; ?>" alt="" class="w-px-40 h-auto rounded-circle" />
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block">Welcome, <?php echo $username; ?>!</span>
                                <small class="text-muted"><?php echo $role; ?></small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>

                <li>
                    <a class="dropdown-item" href="<?php echo ($role === 'user') ? 'user_settings.php' : 'admin_settings.php' ; ?>">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Settings</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="logout.php">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                    </a>
                </li>
            </ul>
        </div>
        <!--/ User Dropdown -->
        </div>
      </nav>