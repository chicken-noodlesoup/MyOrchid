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
    require 'admin_navbar.php'; // Include your navbar
    require 'admin_sidebar.php';
    ?>
    
    <!-- Main Content Area -->
    <div id="main">

      <div class="container" style="margin-top: 100px; ">

      

          <!-- Table Title and Add Button -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="fw-bold py-3 mb-4">
              <h4>Orchid Information</h4>
            </div>

            <!-- Button to trigger the modal 
            <div class="col-12 mb-4 ">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrchidModal">Add Orchid</button>
            </div>
            -->

          </div>


          <div class="row">
            <div class="col-12 mb-5">
                <table class="table table-striped table-bordered" id="example" style="width:100%;">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Orchid Name</th>
                            <th>Scientific Name</th>
                            <th>Image</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php
                            require 'config/db_conn.php';

                            $query = "SELECT * FROM `orchid-name`";

                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                              $counter = 1;   
                              foreach($query_run as $orchid)
                                {
                                    ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= $orchid['id_orchid'] ?></td>
                                        <td><?= $orchid['common_names'] ?></td>
                                        <td><?= $orchid['specific_names'] ?></td>
                                        <td>
                                            <img src="data:image/jpeg;base64,<?= base64_encode($orchid['image']) ?>" alt="Orchid Image" style="max-width:100px; max-height:100px;">
                                        </td>
                                        <td><?= $orchid['description'] ?></td>
                                        <td>
                                            <button type="button" value="<?= $orchid['specific_names']; ?>" class="editOrchidBtn btn btn-success btn-sm">Edit</button>
                                            <button type="button" value="<?= $orchid['specific_names']; ?>" class="deleteOrchidBtn btn btn-danger btn-sm">Delete</button>
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


        <!-- Add Modal -->
        <div class="modal fade" id="addOrchidModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel4">Add Orchid Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="saveOrchid">
                        <div class="modal-body">
                            <div id="errorMessage" class="alert alert-warning d-none"></div>
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
                                <label for="inputfile" class="form-label">Upload Image</label>
                                <input type="file" class="form-control" name="inputfile" id="inputfile" aria-describedby="inputfile" accept="image/*" />
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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Orchid</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
            
        <!-- Edit Modal -->
        <div class="modal fade" id="orchidEditModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel4">Edit Orchid Information</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form id="updateOrchid" >
                      <div class="modal-body">
                          <div id="errorMessage" class="alert alert-warning d-none"></div>
                          <div class="row mb-3">
                              <div class="col">
                                  <label for="common_names" class="form-label">Orchid Name</label>
                                  <input type="text" name="common_names" id="common_names" class="form-control" placeholder="Enter Orchid Name" />
                              </div>
                              <div class="col">
                                  <label for="specific_names" class="form-label">Scientific Name</label>
                                  <input type="text" name="specific_names"id="specific_names" class="form-control" placeholder="Enter Scientific Name" />
                              </div>
                          </div>
                          <div class="mb-3">
                                <label for="inputfile" class="form-label">Upload Image</label>
                                <input type="file" class="form-control" name="inputfile" id="inputfile" aria-describedby="inputfile" accept="image/*" />
                            </div>
                            <!-- Image Preview Box -->
                            <div id="imagePreviewedit" class="mb-3">
                                <img id="previewImageedit" src="#" alt="Image Preview" style="max-width: 100%; max-height: 200px; display: none;" />
                            </div>
                          <div class="row mb-3">
                              <div class="col">
                                  <label for="description" class="form-label">Description</label>
                                  <textarea name="description" id="description"class="form-control" placeholder="Enter Description"></textarea>
                              </div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Update Orchid</button>
                      </div>
                  </form>
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
    
    $(document).on('submit', '#saveOrchid', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("save_Orchid", true);

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
                    $('#addOrchidModal').modal('hide');
                    $('#saveOrchid')[0].reset();

                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success(res.message);

                    $('#example').load(location.href + " #example");

                } else if (res.status == 500) {
                    alert(res.message);
                }
            }
        });

    });

    $(document).on('click', '.editOrchidBtn', function () {
        var specific_names = $(this).val();

        $.ajax({
            type: "GET",
            url: "code.php?specific_names=" + specific_names,
            success: function (response) {
                if (response.trim() === "") {
                    console.error('Empty response received');
                    return;
                }

                try {
                    var res = JSON.parse(response);
                    if (res.status == 404) {
                        alert(res.message);
                    } else if (res.status == 200) {
                        $('#common_names').val(res.data.common_names);
                        $('#specific_names').val(res.data.specific_names);
                        $('#description').val(res.data.description);

                        // Add this line to set the value for the update operation
                        $('#updateOrchid').attr('data-specific-names', res.data.specific_names);
                        $('#updateOrchid').attr('data-has-image', res.data.has_image);

                        // Set the image preview
                        if (res.data.image) {
                            $('#previewImageedit').attr('src', 'data:image/jpeg;base64,' + res.data.image);
                            $('#previewImageedit').css('display', 'block');
                        } else {
                            // If no image, hide the preview
                            $('#previewImageedit').css('display', 'none');
                        }

                        $('#orchidEditModal').modal('show');
                    }
                } catch (error) {
                    console.error('Error parsing JSON response:', error);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    });


    $(document).on('submit', '#updateOrchid', function (e) {
    e.preventDefault();

      // Assuming you have added data-specific-names attribute to your form
      var specific_names = $(this).data('specific-names');
      var hasImage = $(this).data('has-image');

      var formData = new FormData(this);
      formData.append("update_Orchid", true);
      formData.append("specific_names", specific_names); 

      // Check if a new file is being uploaded
      if ($('#inputfile')[0].files.length > 0) {
          formData.append("new_image", $('#inputfile')[0].files[0]);
      }

      // Include the has_image attribute in FormData
      formData.append("has_image", hasImage);


      $.ajax({
          type: "POST",
          url: "code.php",
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
              var res = jQuery.parseJSON(response);
              if (res.status == 422) {
                  $('#errorMessageUpdate').removeClass('d-none');
                  $('#errorMessageUpdate').text(res.message);
              } else if (res.status == 200) {
                  $('#errorMessageUpdate').addClass('d-none');
                  alertify.set('notifier', 'position', 'top-right');
                  alertify.success(res.message);
                  $('#orchidEditModal').modal('hide');
                  $('#updateOrchid')[0].reset();
                  $('#example').load(location.href + " #example");
              } else if (res.status == 500) {
                  alert(res.message);
              }
          }
      });
    });


    $(document).on('click', '.deleteOrchidBtn', function (e) {
    e.preventDefault();

      if (confirm('Are you sure you want to delete this data?')) {
          var specific_names = $(this).val(); // Change to specific_names
          $.ajax({
              type: "POST",
              url: "code.php",
              data: {
                  'delete_Orchid': true,
                  'specific_names': specific_names // Change to specific_names
              },
              success: function (response) {
                  var res = jQuery.parseJSON(response);
                  if (res.status == 500) {
                      alert(res.message);
                  } else {
                      alertify.set('notifier', 'position', 'top-right');
                      alertify.success(res.message);
                      $('#example').load(location.href + " #example");
                  }
              }
          });
      }
    });

    </script>






  </body>
</html>
