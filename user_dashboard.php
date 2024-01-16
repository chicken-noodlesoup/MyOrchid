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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <link rel="stylesheet" href="https://code.jquery.com/jquery-3.7.0.js" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="lib/dropzone/dropzone.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/themes/default.min.css" />
    

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style3.css" />
    <link rel="stylesheet" href="css/style2.css" />
    <link rel="stylesheet" href="css/app-calendar.css" />
    

  </head>
  <body>

    <?php
    require 'navbar.php'; // Include your navbar
    require 'user_sidebar.php';
    ?>
    <?php
        
    ?>

<?php 
    
    
    $myplantCountQuery = "SELECT COUNT(*) AS total FROM myplants WHERE user_id = $currentUserId";
    $myplantCountResult = $conn->query($myplantCountQuery);

    
    if ($myplantCountResult) {
        $myplantRow = $myplantCountResult->fetch_assoc();
        $totalmyplant = $myplantRow['total'];
    } else {
        $totalmyplant = 0;
    }

    // SQL query to get the total number of items in user table
    $topicCountQuery = "SELECT COUNT(*) AS total FROM discussion_topic WHERE user_id = $currentUserId";
    $topicCountResult = $conn->query($topicCountQuery);

    // Check if the count query for user was successful
    if ($topicCountResult) {
        $topicRow = $topicCountResult->fetch_assoc();
        $totaltopic = $topicRow['total'];
    } else {
        $totaltopic = 0;
    }



    ?>

<!-- Main Content Area -->
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
                          <span class="fw-semibold d-block mb-1">My Plant</span>
                          <h3 class="card-title mb-2"><?=$totalmyplant?></h3>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                          </div>
                          <span class="fw-semibold d-block mb-1">My Topic</span>
                          <h3 class="card-title mb-2"><?=$totaltopic?></h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>  

            <div class = "row">
              <div class="col-xl-12 mb-4 mb-xl-0">
                <div class="card">
                    <h5 class="card-header"> Upcoming Event </h5>
                    <div class="card-body">
                    <div class="row">
              <div class="col-md-4 mb-4">
                  <div class="card">
                      <h5 class="card-header">Watering</h5>
                      <div class="card-body">
                          <?php
                            $wateringEventsQuery = "SELECT event_description, event_date FROM event
                                                    WHERE event_type = 'Watering'
                                                    AND event_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
                            $stmtWateringEvents = $conn->prepare($wateringEventsQuery);
                            $stmtWateringEvents->execute();
                            $stmtWateringEvents->bind_result($wateringDescription, $wateringDate);
                            
                            $wateringEvents = [];
                            while ($stmtWateringEvents->fetch()) {
                                $wateringEvents[] = ['description' => $wateringDescription, 'date' => $wateringDate];
                            }
                            $stmtWateringEvents->close();

                            // Display 'Watering' events if available
                            if (!empty($wateringEvents)) {
                              foreach ($wateringEvents as $event) {
                                $formattedDate = date('j F Y', strtotime($event['date']));
                                echo '<div class="event-item border mb-3 p-2">';
                                echo '<p>' . $event['description'] . '</p>';
                                echo '<p class="text-muted small">' . $formattedDate . '</p>';
                                echo '</div>';
                            }
                            } else {
                                echo '<p>No watering events for this week.</p>';
                            }
    
                          ?>

                      </div>
                  </div>
              </div>
              <div class="col-md-4 mb-4">
                  <div class="card">
                      <h5 class="card-header">Fertilizing</h5>
                      <div class="card-body">
                      <?php
                            // Fetch events for the 'Fertilizing' type within the upcoming week
                            $fertilizingEventsQuery = "SELECT event_description, event_date FROM event
                            WHERE event_type = 'Fertilizing'
                            AND event_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
                            $stmtFertilizingEvents = $conn->prepare($fertilizingEventsQuery);
                            $stmtFertilizingEvents->execute();
                            $stmtFertilizingEvents->bind_result($fertilizingDescription, $fertilizingDate);

                            $fertilizingEvents = [];
                            while ($stmtFertilizingEvents->fetch()) {
                            $fertilizingEvents[] = ['description' => $fertilizingDescription, 'date' => $fertilizingDate];
                            }
                            $stmtFertilizingEvents->close();

                            // Display 'Fertilizing' events if available
                            if (!empty($fertilizingEvents)) {
                                foreach ($fertilizingEvents as $event) {
                                  $formattedDate = date('j F Y', strtotime($event['date']));
                                  echo '<div class="event-item border mb-3 p-2">';
                                  echo '<p>' . $event['description'] . '</p>';
                                  echo '<p class="text-muted small">' . $formattedDate . '</p>';
                                  echo '</div>';
                                }
                            } else {
                                echo '<p>No fertilizing events for this week.</p>';
                            }
    
                          ?>
                      </div>
                  </div>
              </div>
              <div class="col-md-4 mb-4">
                  <div class="card">
                      <h5 class="card-header">Others</h5>
                      <div class="card-body">
                      <?php
                        // Fetch events for the 'Others' type within the upcoming week
                        $OthersEventsQuery = "SELECT event_description, event_date FROM event
                        WHERE event_type = 'Others'
                        AND event_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
                        $stmtOthersEvents = $conn->prepare($OthersEventsQuery);
                        $stmtOthersEvents->execute();
                        $stmtOthersEvents->bind_result($OthersDescription, $OthersDate);

                        $OthersEvents = [];
                        while ($stmtOthersEvents->fetch()) {
                        $OthersEvents[] = ['description' => $OthersDescription, 'date' => $OthersDate];
                        }
                        $stmtOthersEvents->close();

                        // Display 'Others' events if available
                        if (!empty($OthersEvents)) {
                            foreach ($OthersEvents as $event) {
                              $formattedDate = date('j F Y', strtotime($event['date']));
                              echo '<div class="event-item border mb-3 p-2">';
                              echo '<p>' . $event['description'] . '</p>';
                              echo '<p class="text-muted small">' . $formattedDate . '</p>';
                              echo '</div>';
                            }
                        } else {
                            echo '<p>No Others events for this week.</p>';
                        }

                      ?>
                      </div>
                  </div>
              </div>
    
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
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>

    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/isotope/isotope.pkgd.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/dropzone/dropzone.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script src="js/script.js"></script>
    <script src="js/script2.js"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize FullCalendar
        const fullCalendarEl = document.getElementById('calendar');
        const fullCalendar = new FullCalendar.Calendar(fullCalendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            events: [
                {
                    title: 'Monday Appointment',
                    start: '2024-01-10T09:00:00', // Adjust the start date and time
                    dow: [1] // 0=Sunday, 1=Monday, ..., 6=Saturday
                },
                // Add more events as needed
            ]
        });
        fullCalendar.render();

        // Initialize Flatpickr
        const inlineCalendar = document.getElementById('inline-calendar');
        const flatpickrInstance = flatpickr(inlineCalendar, {
            inline: true, // Display the calendar inline
            enableTime: false, // Disable time input
            dateFormat: "Y-m-d", // Specify the date format
            onChange: function (selectedDates, dateStr, instance) {
                // Update FullCalendar to the selected date
                fullCalendar.gotoDate(selectedDates[0]);
            }
        });
    });
</script>


</body>
</html>
