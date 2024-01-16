<?php
  include('config/db_conn.php');
  session_start();

  // Check if the user is logged in
  if (isset($_SESSION['user']) && isset($_SESSION['role'])) {
  $username = $_SESSION['user'];
  $role = $_SESSION['role'];
  } else {
  // Redirect to the login page if the user is not logged in
  header('Location: login.php');
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
    <link href="img\logo.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/jquery-3.7.0.js" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" />


    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style2.css" />

  </head>
  <body>
  <?php
    require 'navbar.php'; // Include your navbar
    require 'user_sidebar.php';
    ?>




   <!-- Main Content Area -->
<div id="main">

<div class="container" style="margin-top: 100px; padding-left: 55px; padding-right: 55px; ">

    <!-- Grid Card -->
    <nav class="orchidcard">
        <h3 class="pb-1 mb-4 ">Orchid Information</h3>
        

        <div class="row row-cols-1 row-cols-md-4 g-4 mb-5 " >
            
          <?php

            // Number of items per page (adjust as needed)
            $itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 12;

            // Current page (adjust as needed)
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $itemsPerPage;

            // SQL query to get the total number of items
            $countQuery = "SELECT COUNT(*) AS total FROM `orchid-name`";
            $countResult = $conn->query($countQuery);

            // Check if the count query was successful
            if ($countResult) {
                $row = $countResult->fetch_assoc();
                $totalItems = $row['total'];
            } else {
                $totalItems = 0;
            }

            // SQL query with LIMIT and OFFSET
            $sql = "SELECT * FROM `orchid-name` LIMIT $itemsPerPage OFFSET $offset";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    $specific_names = $row["specific_names"];
                    $imageData = $row["image"];
                    $description = $row["description"];

                    // Extracting the first sentence from the description
                    $first_sentence = strtok($description, ".");

                    // Convert blob data to base64 for embedding in HTML
                    $imageBase64 = base64_encode($imageData);

                // Output the card HTML
                echo '<div class="col">
                      <a href="user_orchidinfodetails.php?id_orchid=' . $row['id_orchid'] . '">
                      <div class="card h-100 "style= "background-color:#E8DACD;">
                              <div class="orchid-item">
                                  <div class="square-container" style="height: 300px;">
                                      <img class="card-img-top card-image" src="data:image/jpeg;base64,' . $imageBase64 . '" alt="Card orchid image" style="object-fit: cover; width: 100%; height: 100%;" />
                                  </div>    
                                  <div class="card-body" >
                                      <h5 class="card-title">' . $specific_names . '</h5>
                                      <p class="card-text">' . $first_sentence . '</p>
                                  </div>
                              </div>
                          </div>
                          </a>
                      </div>';

                    }
            } else {
                echo "0 results";
            }

            $conn->close();
          ?>

        </div>
    </nav>

  </div>

    <!-- Basic Pagination -->
    <div class="card-body">
        <div class="row">
          <div class="col">
            <div class="demo-inline-spacing">
            <ul class="pagination justify-content-end">
            
            <?php

              $totalPages = ceil($totalItems / $itemsPerPage);

              echo '<div class="pagination">';
                  if ($page > 1) {
                      echo '<li class="page-item first">
                            <a class="page-link" href="?page=1&itemsPerPage= '. $itemsPerPage .'"
                            ><i class="tf-icon bx bx-chevrons-left"></i
                            ></a>
                            </li>';
                      echo '<li class="page-item prev">
                            <a class="page-link" href="?page=' . ($page - 1) . '&itemsPerPage=' . $itemsPerPage . '"
                            ><i class="tf-icon bx bx-chevron-left"></i
                            ></a>
                            </li>';
                  }
                  for ($i = 1; $i <= $totalPages; $i++) {
                      echo '<li class="page-item">
                            <a class="page-link"href="?page=' . $i . '&itemsPerPage=' . $itemsPerPage . '" ' . ($page == $i ? 'class="active"' : '') . '>' . $i . '</a></li>';
                  }
                  if ($page < $totalPages) {
                      echo '<li class="page-item next">
                            <a class="page-link" href="?page=' . ($page + 1) . '&itemsPerPage=' . $itemsPerPage . '"
                          ><i class="tf-icon bx bx-chevron-right"></i
                            ></a>
                          </li>';
                      echo '<li class="page-item last">
                            <a class="page-link" href="?page=' . $totalPages . '&itemsPerPage=' . $itemsPerPage . '"
                            ><i class="tf-icon bx bx-chevrons-right"></i
                            ></a>
                            </li>';
                  }
                  echo '</div>';
              ?>
            </ul>
            
    </div>
    <!--/ Basic Pagination -->

  </div>
          

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/isotope/isotope.pkgd.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>

    <script src="js/script.js"></script>
    <script src="js/script2.js"></script>

  </body>
</html>
