
<?php
session_start();

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
    require 'admin_navbar.php';
    require 'admin_sidebar.php';
    ?>

    
    
    <!-- Main Content Area -->
    <div id="main">

      <div class="container" style="margin-top: 80px; ">
      <div class="container-xxl flex-grow-1 container-p-y">
            <div class="fw-bold py-3 mb-4">
              <h4> User Information</h4>
            </div>

            <div class="row">
    <div class="col-12 mb-5">
        <table class="table table-striped table-bordered" id="userTable" style="width:100%;">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Profile Picture</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require 'config/db_conn.php';

                $query = "SELECT * FROM `user`";

                $query_run = mysqli_query($conn, $query);

                if(mysqli_num_rows($query_run) > 0)
                {
                    foreach($query_run as $user)
                    {
                        ?>
                        <tr>
                            <td><?= $user['user_id'] ?></td>
                            <td><?= $user['Username'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td>
                                <?php
                                if (!empty($user['profilepic'])) {
                                    $profilePic = base64_encode($user['profilepic']);
                                    echo '<img src="data:image/jpeg;base64,' . $profilePic . '" alt="Profile Picture" style="max-width:100px; max-height:100px;">';
                                } else {
                                    echo 'No Picture';
                                }
                                ?>
                            </td>
                            <td><?= $user['role'] ?></td>
                            <td> <button type="button" 
                                  value="<?= $user['role']; ?>" 
                                  data-userid="<?= $user['user_id']; ?>" 
                                  class="editUserBtn btn btn-success btn-sm"
                                  data-bs-toggle="modal" 
                                  data-bs-target="#EditUserModal">
                                  Edit
                          </button> 
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

      </div>

    </div>
      
<!-- Edit Modal -->
<div class="modal fade" id="EditUserModal" tabindex="-1" aria-labelledby="EditUserModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-simple" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Edit User Role</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="EditUserForm" enctype="multipart/form-data">
               <input type="hidden" id="EditUserId" name="user_id">
               <div class="mb-3">
                  <label for="EditUserName" class="form-label">Name</label>
                  <input type="text" class="form-control" id="EditUserName" name="user_name" placeholder="Enter user name" required disabled>
               </div>
               <div class="mb-3">
                  <label for="EditUserRole" class="form-label">User Role</label>
                  <select class="form-select" id="EditUserRole" name="user_role" required>
                     <option value="admin">Admin</option>
                     <option value="user">User</option>
                  </select>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="updateUser()">Save Changes</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>




                    

<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
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
$(document).ready(function() {
    $('.editUserBtn').click(function() {
        var userId = $(this).data('userid');
        var userName = $(this).closest('tr').find('td:eq(1)').text();
        var userEmail = $(this).closest('tr').find('td:eq(2)').text();
        var userRole = $(this).closest('tr').find('td:eq(5)').text();

        $('#EditUserId').val(userId);
        $('#EditUserName').val(userName);
        $('#EEditUserEmail').val(userEmail); // Corrected ID here
        $('#EditUserRole').val(userRole);
    });

    // AJAX request when Save Changes button is clicked
    $('#EditUserForm').on('submit', function(e) {
        e.preventDefault();

        // Get form data
        var formData = $(this).serialize();

        // Perform AJAX request
        $.ajax({
            url: 'code.php', // Change this to the actual path of your code.php file
            type: 'POST',
            data: formData,
            success: function(response) {
                // Handle the response, e.g., show a success message
                alertify.success('User role updated successfully');
                
                // Close the modal
                $('#EditUserModal').modal('hide');
            },
            error: function(error) {
                // Handle the error, e.g., show an error message
                alertify.error('Error updating user role');
            }
        });
    });
});




</script>
