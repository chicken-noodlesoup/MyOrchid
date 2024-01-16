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
    header('Location: home.php'); // Adjust the path as needed
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
        <h3 class="pb-1 mb-4">New Topic </h3>
        <!-- Basic Layout -->
        <div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-2 mt-2">Add new topic</h5>
            </div>
            <div class="card-body">
                <form id="AddTopicForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="topicTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="topicTitle" name="topicTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="topicContent" class="form-label">Content</label>
                        <textarea class="form-control" id="topicContent" name="topicContent" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="topicTags" class="form-label">Select Tags</label>
                        <select class="js-example-placeholder-multiple my-tags-dropdown" id = "topicTags[]"name="topicTags[]" multiple="multiple">
                            <option value="Care Tips">Care Tips</option>
                            <option value="Species Identification">Species Identification</option>
                            <option value="Blooming Cycles">Blooming Cycles</option>
                            <option value="Orchid Species">Orchid Species</option>
                            <option value="Pests And Diseases">Pests and Diseases</option>
                            <option value="Growing Mediums">Growing Mediums</option>
                            <option value="Propagation Techniques">Propagation Techniques</option>
                            <option value="Orchid Artistry">Orchid Artistry</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="topicPicture" class="form-label">Upload Picture</label>
                        <input type="file" class="form-control" id="topicPicture" name="topicPicture">
                    </div>
                    <div class="mb3">
                        <button type="submit" class="btn btn-primary">Create Topic</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

            <!-- / Content -->
    </div>


         
    



    
    
    
    
    <?php
        require 'footer.php'; 
    ?>
    
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

    $(document).ready(function() {
        $(".js-example-placeholder-multiple").select2({
        placeholder: "Maximum of 3 tags"
    });
    });

    $(document).on('submit', '#AddTopicForm', function (e) {
    e.preventDefault();

        var formData = new FormData(this);
        formData.append("Add_Topic", true);

        // Show loading spinner or any other visual indication of submission
        $('#loadingSpinner').removeClass('d-none');

        $.ajax({
            type: "POST",
            url: "code.php",
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {

                console.log('AJAX success:', response);
                
                if (response.status == 422) {
                    console.log("1");
                    $('#errorMessage').removeClass('d-none');
                    $('#errorMessage').text(response.message);
                    
                } else if (response.status == 200) {
                    $('#errorMessage').addClass('d-none');
                    console.log("2");
                    
                    $('#AddTopicForm')[0].reset();

                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success(response.message);

                    // Reload the content or update as needed
                    $('#example').load(location.href + " #example");
                    
                } else if (response.status == 500) {
                    console.log("3");
                    alert(response.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Hide loading spinner on error
                console.log('AJAX error:', errorThrown);
                $('#loadingSpinner').addClass('d-none');
                alert('An error occurred during the submission.');
            }
        });
    });

    </script>




</body>
</html>
