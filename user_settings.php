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
      <div class="container" style="margin-top: 80px;">
          <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings</span></h4>

          <!-- Profile Details -->
          <div class="card mb-4" id="update_UserInfo">
              <h5 class="card-header">Profile Details</h5>

              <!-- Account -->
              <div class="card-body">

                  <?php
                  require_once "config/db_conn.php";

                  $username = $_SESSION['user'];
                  $sql = "SELECT Username, email, profilepic FROM user WHERE Username = '$username'";
                  $result = mysqli_query($conn, $sql);

                  if ($result) {
                      $userData = mysqli_fetch_assoc($result);
                      $profilePicturePath = ($userData['profilepic']) ? 'data:image/jpeg;base64,' . base64_encode($userData['profilepic']) : 'img/noprofilepic.jpg';
                  } else {
                      $profilePicturePath = 'img/noprofilepic.jpg';
                  }
                  ?>
                    <!-- Account Form -->
                    <div id="userCardContainer">
                        <div class="card-body">
                            <form id="updateForm" enctype="multipart/form-data">
                                <div class="d-flex align-items-start align-items-sm-center gap-4">
                                    <img src="<?php echo $profilePicturePath; ?>" 
                                        alt="user-avatar" 
                                        class="d-block rounded" 
                                        height="100" 
                                        width="100" 
                                        id="uploadedAvatar" 
                                        name="uploadedAvatar" 
                                    />

                                    <div class="button-wrapper">
                                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                            <span class="d-none d-sm-block">Upload new photo</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                            <input type="file" 
                                                id="upload" 
                                                class="account-file-input" 
                                                hidden 
                                                accept="image/png, image/jpeg" 
                                            />
                                        </label>

                                        <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                            <i class="bx bx-reset d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Reset</span>
                                        </button>

                                        <p class="text-muted mb-0">Allowed JPG or PNG. Max size of 800K</p>
                                    </div>
                                </div>

                                <hr class="mt-2 my-0" />

                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="Username" class="form-label">Username</label>
                                        <input class="form-control" type="text" id="Username" name="Username" value="<?php echo $userData['Username']; ?>" />
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input class="form-control" type="text" id="email" name="email" value="<?php echo $userData['email']; ?>" disabled />
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <button type="button" class="btn btn-primary me-2" onclick="updateUserInfo()">Save changes</button>
                                    <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /Account Form -->


          </div>
          <!-- /Profile Details -->
                  
      </div>

      <!-- Delete Account Section -->
      <div class="container">
          <div class="card">
              <h5 class="card-header">Delete Account</h5>
              <div class="card-body">
                  <div class="mb-3 col-12 mb-0">
                      <div class="alert alert-warning">
                          <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?</h6>
                          <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                      </div>
                  </div>
                  <form id="formAccountDeactivation">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation" />
                            <label class="form-check-label" for="accountActivation">I confirm my account deactivation</label>
                        </div>
                        <button type="button" class="btn btn-danger deactivate-account" name="delete-account">Deactivate Account</button>
                    </form>

              </div>
          </div>

          <?php
      require 'footer.php';
      ?>
      </div>
      <!-- /Delete Account Section -->

      
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
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/themes/default.min.css" />
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>


    <script src="js/script.js"></script>
    <script src="js/script2.js"></script>




<script>

    document.addEventListener('DOMContentLoaded', function () {
        const deactivateAcc = document.querySelector('#formAccountDeactivation');

        // Update/reset user image of account page
        let accountUserImage = document.getElementById('uploadedAvatar');
        const fileInput = document.querySelector('.account-file-input'),
        resetFileInput = document.querySelector('.account-image-reset');

        if (accountUserImage && fileInput && resetFileInput) {
            const resetImage = accountUserImage.src;

            fileInput.addEventListener('change', () => {
                if (fileInput.files[0]) {
                    accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
                }
            });

            resetFileInput.addEventListener('click', () => {
                fileInput.value = '';
                accountUserImage.src = resetImage;
            });
        }
    });


    function updateUserInfo() {

        var formData = new FormData(document.getElementById('updateForm'));
        formData.append("update_UserInfo", true);
        formData.append("uploadedAvatar", document.querySelector('#upload')?.files[0]); 


        $.ajax({
            type: "POST",
            url: "code.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('AJAX success:', response);
                
                if (response.status === 422) {
                    $('#errorMessageUpdate').removeClass('d-none');
                    $('#errorMessageUpdate').text(response.message);
                } else if (response.status === 200) {
                    $('#errorMessageUpdate').addClass('d-none');
                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success(response.message);
                    
                    
                } else if (response.status === 500) {
                    console.error(response.message);
                    alert('Internal Server Error. Please try again later.');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('An error occurred while processing your request.');
            }
        });
    }

    $(document).ready(function () {
    $(".deactivate-account").click(function () {
        
        var user_id = "<?php echo $currentUserId; ?>";

        
        $.ajax({
            type: "POST",
            url: "code.php",
            data: { delete_account: true, user_id: user_id },
            
            success: function (response) {
                console.log(response); 

                if (response.status === 422) {
                    $('#errorMessageUpdate').removeClass('d-none');
                    $('#errorMessageUpdate').text(response.message);

                } else if (response.status === 200) {
                    $('#errorMessageUpdate').addClass('d-none');
                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success(response.message);
                    
                    console.log("Redirecting to index.php");
                    window.location.href = "index.php";
                    
                } else if (response.status === 500) {
                    console.error(response.message);
                    alert('Internal Server Error. Please try again later.');
                }
            },
            error: function () {
                alert("An error occurred during the request.");
            }
        });
    });
});





</script>











</body>
</html>


