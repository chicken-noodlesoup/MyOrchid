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

    <?php
        // Assuming you have the pest_id
        if (isset($_GET['pest_id'])) {
            $pest_id = $_GET['pest_id'];

            // Query to retrieve pest details from orchidpests
            $queryPest = "SELECT * FROM `orchidpests` WHERE pest_id = ?";

            // Use prepared statement to prevent SQL injection
            $stmtPest = $conn->prepare($queryPest);
            $stmtPest->bind_param("i", $pest_id);

            // Execute the statement
            $stmtPest->execute();

            $resultPest = $stmtPest->get_result();

            if ($resultPest->num_rows > 0) {
                $pest = $resultPest->fetch_assoc();

                // Fetch pest details
                $pest_id = $pest['pest_id'];
                $pest_name = $pest['pest_name'];
                $pest_description = $pest['pest_description'];
                $pest_image = $pest['pest_image'];
                $pest_symptom = $pest['pest_symptom'];
                $pest_cause = $pest['pest_cause'];
                $pest_preventions = $pest['pest_preventions'];
                $pest_solutions = $pest['pest_solutions'];

                // Close the statement
                $stmtPest->close();
            } else {
                echo "Pest details not found.";
            }

            // Close the database connection
            $conn->close();
        }
    ?>

    <!-- Display the fetched information -->
    <h4 class="pt-3 mb-0">
        <span class="text-muted fw-light">Pest Details /</span> <?= $pest_name ?>
    </h4>
    <div class="card g-3 mt-5">
        <div class="card-body row g-3">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-2 gap-1">
                        <div class="me-1">
                            <h5 class="mb-1"><?= $pest_name ?></h5>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bx bx-bookmarks bx-sm"></i>
                        </div>
                </div>
            <div class="card academy-content shadow-none border">
                <div class="p-2">
                    <div class="image-container mb-4 clickable" data-bs-toggle="modal" data-bs-target="#imageModal" data-src="data:image/jpeg;base64,<?= base64_encode($pest_image) ?>">
                        <img src="data:image/jpeg;base64,<?= base64_encode($pest_image) ?>" alt="Orchid Image" class="img-fluid" style="width: 400px; height: 300px; object-fit: cover;">
                    </div>
                </div>
            </div>
            <div class="accordion" id="pest_details_accordian">

                <!-- Description Accordion -->
                <?php if (!empty($pest_description)) : ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne">
                                Description
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <p class="mb-0 pt-1"><?php echo nl2br($pest_description) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Symptom Accordion -->
                <?php if (!empty($pest_symptom)) : ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo">
                                Symptom Analysis
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p class="mb-0 pt-1"><?php echo nl2br($pest_symptom) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Cause Accordion -->
                <?php if (!empty($pest_cause)) : ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree">
                                Cause
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p class="mb-0 pt-1"><?php echo nl2br($pest_cause) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Prevention Accordion -->
                <?php if (!empty($pest_preventions)) : ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour">
                                Prevention
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p class="mb-0 pt-1"><?php echo nl2br($pest_preventions) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Solution Accordion -->
                <?php if (!empty($pest_solutions)) : ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive">
                                Solution
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <p class="mb-0 pt-1"><?php echo nl2br($pest_solutions) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            
            </div>
        </div>
    </div>





    <?php
    require 'footer.php';
    ?>

</div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img src="" alt="Enlarged Image" class="img-fluid" id="enlargedImage" style="width: 400px; height: 400px; margin: auto; display: block;">
            </div>
        </div>
    </div>
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

    <script>

    $('.clickable').click(function () {
            var src = $(this).data('src');
            $('#enlargedImage').attr('src', src);
    });

    </script>

  </body>
</html>
