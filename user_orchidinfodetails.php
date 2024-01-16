<?php
include('config/db_conn.php');
session_start();

// Check if the user is logged in
if (isset($_SESSION['user']) && isset($_SESSION['role'])) {
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
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

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

    <title>Your Page Title</title>
</head>

<body>

    <?php
    require 'navbar.php';
    require 'user_sidebar.php';
    ?>

    <!-- Main Content Area -->
    <div id="main">
        <div class="container" style="margin-top: 80px;padding-left: 25px; padding-right: 25px; ">
        <div class="container-xxl flex-grow-1 container-p-y">
    <!-- Content -->
    
    <?php
    // Assuming you already fetched orchid details
    if (isset($_GET['id_orchid'])) {
        $id_orchid = $_GET['id_orchid'];

        // Query to retrieve orchid details
        $queryOrchid = "SELECT o.*, d.*
                        FROM `orchid-name` o 
                        JOIN `orchid_care_details` d ON o.id_orchid = d.id_orchid
                        WHERE o.id_orchid = ?";

        // Use prepared statement to prevent SQL injection
        $stmtOrchid = $conn->prepare($queryOrchid);
        $stmtOrchid->bind_param("i", $id_orchid);

        // Execute the statement
        $stmtOrchid->execute();

        $resultOrchid = $stmtOrchid->get_result();

        if ($resultOrchid->num_rows > 0) {
            $row = $resultOrchid->fetch_assoc();

            // Fetch orchid details
            $id_orchid = $row['id_orchid'];
            $specific_names = $row['specific_names'];
            $common_names = $row['common_names'];
            $image = $row['image'];
            $description = $row['description'];
            $lighting = $row['lighting'];
            $fertilize = $row['fertilize'];
            $watering = $row['watering'];
            $soil_mixture = $row['soil_mixture'];
            $difficulty = $row['difficulty'];
            $suggested_location = $row['suggested_location'];

            // Close the first statement
            $stmtOrchid->close();

            // Query to retrieve associated pests
            $queryPests = "SELECT p.*
                            FROM `orchid_withpests` op
                            JOIN `orchidpests` p ON op.pest_id = p.pest_id
                            WHERE op.id_orchid = ?";

            // Use prepared statement to prevent SQL injection
            $stmtPests = $conn->prepare($queryPests);
            $stmtPests->bind_param("i", $id_orchid);

            // Execute the statement
            $stmtPests->execute();

            $resultPests = $stmtPests->get_result()->fetch_all(MYSQLI_ASSOC);

            // Close the second statement
            $stmtPests->close();
        } else {
            echo "Orchid details not found.";
        }

        // Close the database connection
        $conn->close();
    }
    ?>

    <!-- Display the fetched information -->
    <h4 class="pt-3 mb-0">
        <span class="text-muted fw-light">Orchid Details /</span> <?= $specific_names ?>
    </h4>

    <div class="card g-3 mt-5">
        <div class="card-body row g-3">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-2 gap-1">
                    <div class="me-1">
                        <h5 class="mb-1"><?= $common_names ?></h5>
                        <p class="mb-1">Specific Names: <span class="fw-medium"><?= $specific_names ?></span></p>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bx bx-bookmarks bx-sm"></i>
                    </div>
                </div>

                <div class="card academy-content shadow-none border">
                <div class="p-2">
                    <div class="image-container mb-4 clickable" data-bs-toggle="modal" data-bs-target="#imageModal" data-src="data:image/jpeg;base64,<?= base64_encode($image) ?>">
                        <img src="data:image/jpeg;base64,<?= base64_encode($image) ?>" alt="Orchid Image" class="img-fluid" style="width: 400px; height: 300px; object-fit: cover;">
                    </div>
                </div>


                    <div class="card-body">
                        <h5 class="mb-2">Description </h5>
                        <p class="mb-0 pt-1"><?php echo nl2br ($description) ?></p>
                        <hr class="my-4">
                        <h5>Orchid Care Details</h5>
                        <div class="d-flex flex-wrap">
                            <div class="me-5">
                                <p class="text-nowrap"><i class="iconify bx-sm me-2" data-icon="bx:sun"></i>Lighting : <?= $lighting ?></p>
                                <p class="text-nowrap"><i class="iconify bx-sm me-2" data-icon="mdi:watering-can-outline"></i>Watering : <?= $watering ?></p>
                                <p class="text-nowrap"><i class="iconify bx-sm me-2" data-icon="game-icons:fertilizer-bag"></i>Fertilize : <?= $fertilize ?></p>
                            </div>
                            <div>
                                <p class="text-nowrap"><i class="iconify bx-sm me-2" data-icon="ph:chart-bar"></i>Difficulty : <?= $difficulty ?></p>
                                <p class="text-nowrap "><i class="iconify bx-sm me-2" data-icon="akar-icons:location"></i>Suggested Location : <?= $suggested_location ?></p>
                                <p class="text-nowrap "><i class="iconify bx-sm me-2" data-icon="akar-icons:plant"></i>Soil Mixture : <?= $soil_mixture ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
            <div class="accordion stick-top accordion-bordered" id="courseContent">
                    <div class="accordion-item shadow-none border border-bottom-0 active mb-0">
                      <div class="accordion-header" id="headingOne">
                        <button type="button" class="accordion-button bg-lighter rounded-0" data-bs-toggle="collapse" data-bs-target="#chapterOne" aria-expanded="true" aria-controls="chapterOne">
                          <span class="d-flex flex-column">
                            <span class="h5 mb-1">Pest and Diseases</span>
                          </span>
                        </button>
                      </div>
                      <div id="chapterOne" class="accordion-collapse collapse show" data-bs-parent="#courseContent">
                        <div class="accordion-body py-3 border-top">
                        
                        <?php
                        if (!empty($resultPests)) {
                            $count = 1; // Initialize a counter

                            foreach ($resultPests as $pest) {
                                echo '
                                <div class="mb-3">
                                    <a href="user_orchidpest_details.php?pest_id=' . $pest['pest_id'] . '">
                                        <img src="data:image/jpeg;base64,' . base64_encode($pest['pest_image']) . '" alt="Pest" class="" width="40" height="40">
                                        <span class="mb-0 h6">' . $count . '. ' . $pest['pest_name'] . '</span>
                                    </a>
                                </div>
                                ';
                                
                                $count++; // Increment the counter
                            }
                        } else {
                            echo '<p>No pests found for this orchid.</p>';
                        }
                        ?>

                        
                        </div>
                      </div>
                    </div>
                    
                    <div class="accordion-item shadow-none border mb-0">
                      <div class="accordion-header" id="headingTwo">
                        <button type="button" class="bg-lighter rounded-0 accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#chapterTwo" aria-expanded="false" aria-controls="chapterTwo">
                          <span class="d-flex flex-column">
                            <span class="h5 mb-1">Home Remedies tips</span>
                          </span>
                        </button>
                      </div>
                      <div id="chapterTwo" class="accordion-collapse collapse" data-bs-parent="#courseContent">
                        <div class="accordion-body py-3 border-top">
                          <div class="form-check d-flex align-items-center mb-3">
                            <input class="form-check-input" type="checkbox" id="defCheck1" checked="" />
                            <label for="defCheck1" class="form-check-label ms-3">
                              <span class="mb-0 h6">1. How to use Pages in Figma</span>
                              <span class="text-muted d-block">8:31 min</span>
                            </label>
                          </div>
                          <div class="form-check d-flex align-items-center mb-3">
                            <input class="form-check-input" type="checkbox" id="defCheck2" />
                            <label for="defCheck2" class="form-check-label ms-3">
                              <span class="mb-0 h6">2. What is Lo Fi Wireframe</span>
                              <span class="text-muted d-block">2 min</span>
                            </label>
                          </div>
                          <div class="form-check d-flex align-items-center mb-3">
                            <input class="form-check-input" type="checkbox" id="defCheck3" />
                            <label for="defCheck3" class="form-check-label ms-3">
                              <span class="mb-0 h6">3. How to use color in Figma</span>
                              <span class="text-muted d-block">5.9 min</span>
                            </label>
                          </div>
                          <div class="form-check d-flex align-items-center">
                            <input class="form-check-input" type="checkbox" id="defCheck4" />
                            <label for="defCheck4" class="form-check-label ms-3">
                              <span class="mb-0 h6">4. Frames vs Groups in Figma</span>
                              <span class="text-muted d-block">3.6 min</span>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    
                    </div>
                    
            </div>
        </div>
    </div>
</div>

        </div>

        <?php
        require 'footer.php';
        ?>

    </div>

    <!-- Main Content Area -->

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
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>

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
