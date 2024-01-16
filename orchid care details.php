<?php
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
    require 'admin_navbar.php'; // Include your navbar
    require 'admin_sidebar.php';
    ?>
    

    <!-- Main Content Area -->
    <div id="main">

      <div class="container" style="margin-top: 80px; ">

      

          <!-- Table Title and Add Button -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="fw-bold py-3 mb-4">
              <h4>Orchid Care Details</h4>
            </div>

            <!-- Button to trigger the modal 
            <div class="col-12 mb-4 ">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddOrchidCareModal">Add Orchid</button>
            </div>
            -->


          

          </div>


          <div class="row">
            <div class="col-12 mb-5">
                <table class="table table-striped table-bordered" id="example" style="width:100%;">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Orchid Name</th>
                            <th>Lighting</th>
                            <th>Fertilize</th>
                            <th>Watering</th>
                            <th>Soil Mixture</th>
                            <th>Difficulty</th>
                            <th>Suggested Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require 'config/db_conn.php';

                        $query = "SELECT * FROM `orchid_care_details`";

                        $query_run = mysqli_query($conn, $query);

                        if(mysqli_num_rows($query_run) > 0)
                        {
                            foreach($query_run as $orchidcare)
                            {
                                ?>
                                <tr>
                                    <td><?= $orchidcare['id_orchid'] ?></td>
                                    <td><?= $orchidcare['specific_names'] ?></td>
                                    <td><?= $orchidcare['lighting'] ?></td>
                                    <td><?= $orchidcare['fertilize'] ?></td>
                                    <td><?= $orchidcare['watering'] ?></td>
                                    <td><?= $orchidcare['soil_mixture'] ?></td>
                                    <td><?= $orchidcare['difficulty'] ?></td>
                                    <td><?= $orchidcare['suggested_location'] ?></td>
                                    <td>
                                        <button type="button" value="<?= $orchidcare['specific_names']; ?>" class="editOrchidCareBtn btn btn-success btn-sm">Edit</button>
                                        <button type="button" value="<?= $orchidcare['specific_names']; ?>" class="deleteOrchidCareBtn btn btn-danger btn-sm">Delete</button>
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

       


      <!-- Edit Orchid Care Modal -->
      <div class="modal fade" id="EditOrchidCareModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel4">Edit Orchid Care Information</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form id="updateOrchidCare">
                      <div class="modal-body">
                          <div id="errorMessage" class="alert alert-warning d-none"></div>
                          <div class="row mb-3">
                              <div class="col">
                                  <label for="specific_names" class="form-label">Orchid Name</label>
                                  <input type="text" name="specific_names" id="specific_names" class="form-control" placeholder="Enter Orchid Name" />
                              </div>
                          </div>
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
                                      <option value="Full sun to light ">Full sun to light</option>
                                      <option value="Bright, filtered">Bright, filtered</option>
                                      <option value="Bright indirect ">Bright indirect</option>
                                  </select>
                              </div>
                              <div class="col">
                                  <label for="soil_mixture" class="form-label">Soil Mixture</label>
                                  <select name="soil_mixture" id="soil_mixture" class="form-select">
                                      <option value=""></option>
                                      <option value="Well-draining mix ">Well-Draining Mix</option>
                                      <option value="Orchid mix">Orchid Mix</option>
                                      <option value="Fast-draining mix ">Fast Draining Mix</option>
                                      <option value="Fine bark mix ">Fine Bark Mix</option>
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
                          <div class="row mb-3">
                              <div class="col">
                                  <label for="suggested_location" class="form-label">Suggested Location</label>
                                  <input type="text" name="suggested_location" id="suggested_location" class="form-control" placeholder="Enter Suggested Location Information" />
                              </div>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Update Orchid Care</button>
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

    

    $(document).on('click', '.editOrchidCareBtn', function () {
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
                        $('#specific_names').val(res.data.specific_names);
                        $('#watering').val(res.data.watering);
                        $('#fertilize').val(res.data.fertilize);
                        $('#lighting').val(res.data.lighting);
                        $('#soil_mixture').val(res.data.soil_mixture);
                        $('#difficulty').val(res.data.difficulty);
                        $('#suggested_location').val(res.data.suggested_location);

                        $('#EditOrchidCareModal').modal('show');
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

    $(document).on('submit', '#updateOrchidCare', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append("update_OrchidCare", true);

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
                    $('#EditOrchidCareModal').modal('hide');
                    $('#updateOrchidCare')[0].reset();
                    $('#example').load(location.href + " #example");
                } else if (res.status == 500) {
                    alert(res.message);
                }
            }
        });
    });



    $(document).on('click', '.deleteOrchidCareBtn', function (e) {
    e.preventDefault();

      if (confirm('Are you sure you want to delete this data?')) {
          var specific_names = $(this).val(); 
          $.ajax({
              type: "POST",
              url: "code.php",
              data: {
                  'delete_Orchid_care': true,
                  'specific_names': specific_names 
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