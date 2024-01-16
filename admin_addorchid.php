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
  <<head>
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
    <link rel="stylesheet" href="lib/dropzone/dropzone.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/themes/default.min.css" />
    <link href="lib/select2/select2.min.css" rel="stylesheet" />


    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style3.css" />
    <link rel="stylesheet" href="css/style2.css" />

  </head>
  <body>
    
  <?php
    require 'admin_navbar.php'; // Include your navbar
    require 'admin_sidebar.php';
    ?>
    
<!-- Main Content Area -->
<div id="main">
    <div class="container" style="margin-top: 100px;">

        <h3 class="pb-1 mb-4">New Orchid</h3>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-2 mt-2">Add new orchid</h5>
                    </div>
                    <div class="card-body">
                        <form id="AddOrchidForm" enctype="multipart/form-data">
                            <!-- Orchid Name Fields -->
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="common_names" class="form-label">Orchid Name</label>
                                    <input type="text" name="common_names" class="form-control" placeholder="Enter Orchid Name" />
                                </div>
                                <div class="col">
                                    <label for="specific_names" class="form-label">Scientific Name</label>
                                    <input type="text" name="specific_names" class="form-control" placeholder="Enter Scientific Name" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Upload Image</label>
                                <input type="file" class="form-control" name="image" id="image"  />
                            </div>

                            <!-- Image Preview Box -->
                            <div id="imagePreview" class="mb-3">
                                <img id="previewImage" src="#" alt="Image Preview" style="max-width: 100%; max-height: 200px; display: none;" />
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" class="form-control" placeholder="Enter Description"></textarea>
                                </div>
                            </div>

                            <div class="divider mb-5 mt-5">
                                <hr class="my-4">
                            </div>

                            <!-- Orchid Care Details Fields -->
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="watering" class="form-label">Watering</label>
                                    <input type="text" name="watering" id="watering" class="form-control" placeholder="Enter Watering Information" />
                                </div>
                                <div class="col">
                                    <label for="fertilize" class="form-label">Fertilize</label>
                                    <input type="text" name="fertilize" id="fertilize" class="form-control" placeholder="Enter Fertilize Information" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label for="lighting" class="form-label">Lighting</label>
                                    <select name="lighting" id="lighting" class="form-select">
                                        <option value=""></option>
                                        <option value="Shade to partial">Shade to partial</option>
                                        <option value="Low to medium">Low to medium</option>
                                        <option value="Full sun to light">Full sun to light</option>
                                        <option value="Bright, filtered">Bright, filtered</option>
                                        <option value="Bright indirect">Bright indirect</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="soil_mixture" class="form-label">Soil Mixture</label>
                                    <select name="soil_mixture" id="soil_mixture" class="form-select">
                                        <option value=""></option>
                                        <option value="Well-draining mix">Well-Draining Mix</option>
                                        <option value="Orchid mix">Orchid Mix</option>
                                        <option value="Fast-draining mix">Fast Draining Mix</option>
                                        <option value="Fine bark mix">Fine Bark Mix</option>
                                        <option value="Coarse bark mix">Coarse Bark Mix</option>
                                        <option value="Well-aerated mix">Well-Aerated Mix</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="difficulty" class="form-label">Difficulty</label>
                                    <select name="difficulty" id="difficulty" class="form-select">
                                        <option value=""></option>
                                        <option value="easy">Easy</option>
                                        <option value="moderate">Moderate</option>
                                        <option value="challenging">Challenging</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col">
                                    <label for="suggested_location" class="form-label">Suggested Location</label>
                                    <input type="text" name="suggested_location" id="suggested_location" class="form-control" placeholder="Enter Suggested Location Information" />
                                </div>
                            </div>

                            <div class="mb-3 d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary me-2" onclick="resetForm()">Discard</button>
                                <button type="submit" class="btn btn-primary">Create Orchid</button>
                            </div>

                        </form>
                    </div>
                </div>
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
    <script src="lib/dropzone/dropzone.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
    <script src="lib/select2/select2.min.js"></script>

    <script src="js/script.js"></script>
    <script src="js/script2.js"></script>




    <script>
    
    //imagepreview function start//
      $(document).ready(function () {
          // Image preview
          $("#inputfile").change(function () {
              readURL(this);
          });
      });
      
      function readURL(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();

              reader.onload = function (e) {
                  $('#previewImage').attr('src', e.target.result);
                  $('#previewImage').css('display', 'block');
              };

              reader.readAsDataURL(input.files[0]); // convert to base64 string
          }
      }
    //imagepreview function end //

    function resetForm() {
        document.getElementById('AddOrchidForm').reset();
        // Optionally, reset the image preview
        document.getElementById('previewImage').style.display = 'none';
    }


    
    $(document).on('submit', '#AddOrchidForm', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("Add_Orchid", true);

        $.ajax({
            type: "POST",
            url: "code.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {

                var res = jQuery.parseJSON(response);
                if (res.status == 422) {
                    $('#errorMessage').removeClass('d-none');
                    $('#errorMessage').text(res.message);

                } else if (res.status == 200) {

                    $('#errorMessage').addClass('d-none');
                    $('#AddOrchidForm')[0].reset();

                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success(res.message);

                    $('#example').load(location.href + " #example");

                } else if (res.status == 500) {
                    alert(res.message);
                }
            }
        });

    });



    </script>






  </body>
</html>

<?php


?>
