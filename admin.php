<?php
include('config/db_conn.php');
session_start();

// Check if the user is logged in and has the 'admin' role
if (isset($_SESSION['user']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $username = $_SESSION['user'];
    $role = $_SESSION['role'];

    // Fetch the user ID from the database based on the username
    $userQuery = "SELECT user_id FROM user WHERE Username = '$username'";
    $userResult = $conn->query($userQuery);

    if ($userResult && $userResult->num_rows > 0) {
        $userData = $userResult->fetch_assoc();
        $currentUserId = $userData['user_id'];
        
    } else {
        // Handle the case where the user ID is not found
        $currentUserId = 0;
    }

} else {
    // Redirect to the home page or an error page
    header('index.php'); // Adjust the path as needed
    exit();
}
?>


<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style2.css" />

  </head>
  <body>
  <?php
    require 'admin_navbar.php'; // Include your navbar
    require 'admin_sidebar.php';
    ?>
    <?php 
    
    // SQL query to get the total number of items in orchid-name table
    $orchidCountQuery = "SELECT COUNT(*) AS total FROM `orchid-name`";
    $orchidCountResult = $conn->query($orchidCountQuery);

    // Check if the count query for orchid-name was successful
    if ($orchidCountResult) {
        $orchidRow = $orchidCountResult->fetch_assoc();
        $totalOrchids = $orchidRow['total'];
    } else {
        $totalOrchids = 0;
    }

    // SQL query to get the total number of items in user table
    $userCountQuery = "SELECT COUNT(*) AS total FROM user";
    $userCountResult = $conn->query($userCountQuery);

    // Check if the count query for user was successful
    if ($userCountResult) {
        $userRow = $userCountResult->fetch_assoc();
        $totalUsers = $userRow['total'];
    } else {
        $totalUsers = 0;
    }

    $sql = "SELECT * FROM  message ";
    $result = $conn->query($sql);


    ?>
    <div id="main">
      <div class="container" style="margin-top: 100px;">
      <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <div class="col-lg-8 mb-4 order-0">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary">Good day , <?=$username?> 🎉</h5>
                          <p class="mb-4">
                            Welcome!Your journey towards achievement begins here. 
                            Explore your profile to unveil the rewards of your hard work. 
                          </p>
                        </div>
                      </div>
                      <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                          <img
                            src="img\img1.png"
                            height="140"
                            alt="View Badge User"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 order-1">
                  <div class="row">
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                      <div class="card">
                        <div class="card-body" style = "height-100">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            
                          </div>
                          <span class="fw-semibold d-block mb-1">Orchid Data</span>
                          <h3 class="card-title mb-2"><?=$totalOrchids?></h3>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                          </div>
                          <span class="fw-semibold d-block mb-1">User registered</span>
                          <h3 class="card-title mb-2"><?=$totalUsers?></h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>  
              <div class="row">
                <div class="col-lg-8 mb-4 order-0">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col">
                        <div class="card-body">
                          <h5 class="card-title text-primary"> Message for admin </h5>
                          <?php
                            while ($row = $result->fetch_assoc()) {
                              // Display each message
                              $isChecked = !empty($row['reply']); // Check if reply column is not empty
                          
                              echo '<div class="card mb-3">';
                              echo '<div class="card-body">';
                              echo '<div class="form-check d-flex align-items-center mb-3">';
                              echo '<input class="form-check-input" type="checkbox" id="defCheck1" ' . ($isChecked ? 'checked' : '') . ' />';
                              echo '<label for="defCheck1" class="form-check-label ms-3">';
                              echo '<span class="mb-0 h6">' . $row['subject'] . '</span>';
                              echo '<span class="text-muted d-block">' . $row['email'] . '</span>';
                              echo '</label>';
                              echo '</div>';
                              echo '<p class="card-text">' . $row['note'] . '</p>';
                              echo '</div>';
                              echo '</div>';
                          }
                            ?>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                </div></div>
    </div>
    

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/isotope/isotope.pkgd.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>

    <script src="js/script.js"></script>
  </body>
</html>
