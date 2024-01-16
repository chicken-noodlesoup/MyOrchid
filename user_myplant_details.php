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
    if (isset($_GET['myplant_id'])) {
    $myplant_id = $_GET['myplant_id'];

    // Query to retrieve myplants details
    $queryMyPlants = "SELECT * FROM `myplants` WHERE myplant_id = ?";

    // Use prepared statement to prevent SQL injection
    $stmtMyPlants = $conn->prepare($queryMyPlants);
    $stmtMyPlants->bind_param("i", $myplant_id);

    // Execute the statement
    $stmtMyPlants->execute();

    $resultMyPlants = $stmtMyPlants->get_result();

    if ($resultMyPlants->num_rows > 0) {
        $rowMyPlants = $resultMyPlants->fetch_assoc();

        // Fetch myplants details
        $user_id = $rowMyPlants['user_id'];
        $myplant_name = $rowMyPlants['myplant_name'];
        $myplant_species = $rowMyPlants['myplant_species'];
        $myplant_img = $rowMyPlants['myplant_img'];
        $time = $rowMyPlants['time'];
        $myplant_note = $rowMyPlants['myplant_note'];

        // Close the statement
        $stmtMyPlants->close();

        // Query to retrieve the latest 3 myplant_notes in descending order
        $queryMyPlantNote = "SELECT * FROM `myplant_note` WHERE myplant_id = ? ORDER BY note_date DESC ";

        // Use prepared statement to prevent SQL injection
        $stmtMyPlantNote = $conn->prepare($queryMyPlantNote);
        $stmtMyPlantNote->bind_param("i", $myplant_id);

        // Execute the statement
        $stmtMyPlantNote->execute();

        $resultMyPlantNote = $stmtMyPlantNote->get_result();

        // Fetch watering_schedule data
        $queryWateringSchedule = "SELECT * FROM `watering_schedule` WHERE myplant_id = ?";
        $stmtWateringSchedule = $conn->prepare($queryWateringSchedule);
        $stmtWateringSchedule->bind_param("i", $myplant_id);
        $stmtWateringSchedule->execute();
        $resultWateringSchedule = $stmtWateringSchedule->get_result();

        // Fetch fertilizing_schedule data
        $queryFertilizingSchedule = "SELECT * FROM `fertilizing_schedule` WHERE myplant_id = ?";
        $stmtFertilizingSchedule = $conn->prepare($queryFertilizingSchedule);
        $stmtFertilizingSchedule->bind_param("i", $myplant_id);
        $stmtFertilizingSchedule->execute();
        $resultFertilizingSchedule = $stmtFertilizingSchedule->get_result();

        // Fetch watering and fertilizing frequencies
        $wateringFrequency = ($resultWateringSchedule->num_rows > 0) ? $resultWateringSchedule->fetch_assoc()['frequency'] : "N/A";
        $fertilizingFrequency = ($resultFertilizingSchedule->num_rows > 0) ? $resultFertilizingSchedule->fetch_assoc()['frequency'] : "N/A";

    } else {
        echo "MyPlants details not found.";
    }

    // Close the database connection
    $conn->close();
    }
?>
<!-- Main Content Area -->
<div id="main">
    <div class="container" style="margin-top: 100px;">
        <div class="row">
            <div class="col-xl-12">
                <h6 class="text-muted"></h6>
                <div class="nav-align-top mb-4">
                    <ul class="nav nav-tabs nav-fill mb-4" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-home" aria-controls="navs-justified-home" aria-selected="true">
                                <i class="tf-icons bx bxs-leaf me-1"></i> My Orchid 
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="false">
                                <i class="tf-icons bx bx-book me-1"></i> Notes
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-messages" aria-controls="navs-justified-messages" aria-selected="false">
                                <i class="tf-icons bx bx-calendar me-1"></i> Calendar
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                            <div class="col-xl-12">
                                <div class="nav-align-top mb-4">
                                    
                                    <div class="row">
                                        <!-- Plant Details - col-xl-8 -->
                                        <div class="col-xl-8 mb-4 mb-xl-0">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-2 gap-1">
                                                        <div class="me-1">
                                                            <h5><?= $myplant_name ?></h5>
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn p-0" type="button" id="topic" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="topic">
                                                            <button class="dropdown-item edit-orchid" type="button"
                                                                    data-myplant-id="<?= $myplant_id ?>"
                                                                    data-myplant-name="<?= htmlspecialchars($myplant_name, ENT_QUOTES, 'UTF-8') ?>"
                                                                    data-myplant-species="<?= htmlspecialchars($myplant_species, ENT_QUOTES, 'UTF-8') ?>"
                                                                    data-myplant-img="<?= base64_encode($myplant_img) ?>"
                                                                    data-myplant-note="<?= htmlspecialchars($myplant_note, ENT_QUOTES, 'UTF-8') ?>"
                                                                    data-watering-frequency="<?= htmlspecialchars($wateringFrequency, ENT_QUOTES, 'UTF-8') ?>"
                                                                    data-fertilizing-frequency="<?= htmlspecialchars($fertilizingFrequency, ENT_QUOTES, 'UTF-8') ?>"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#EditOrchidModal">
                                                                Edit
                                                            </button>

                                                            <button class="dropdown-item delete-orchid" type="button" 
                                                                        data-myplant-id="<?= $myplant_id ?>" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#DeleteOrchidModal">
                                                                        Delete
                                                            </button>


                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="mb-1">Species Names: <span class="fw-medium"><?= $myplant_species ?></span></p>

                                                    <div class="card academy-content shadow-none border">
                                                        <div class="p-2">
                                                            <div class="image-container mb-4 clickable" data-bs-toggle="modal" data-bs-target="#imageModal" data-src="data:image/jpeg;base64,<?= base64_encode($myplant_img) ?>">
                                                                <img src="data:image/jpeg;base64,<?= base64_encode($myplant_img) ?>" alt="Orchid Image" class="img-fluid" style="width: 400px; height: 300px; object-fit: cover;">
                                                            </div>

                                                            <h5 class="mb-2">Description </h5>
                                                            <p class="mb-0 pt-1"><?= nl2br($myplant_note) ?></p>
                                                            <hr class="my-4">
                                                            <h5>Orchid Care Details</h5>
                                                            <p class="mb-0 pt-1">Watering frequency: Every <?= nl2br ($wateringFrequency) ?> days</p>
                                                            <p class="mb-0 pt-1">Fertilizer frequency: Every <?= nl2br ($fertilizingFrequency) ?> days </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Timeline for notes - col-xl-4 -->
                                        <div class="col-xl-4 mb-4 mb-xl-0">
                                            <div class="card" >
                                                <h5 class="card-header"><?= $myplant_name ?> Notes</h5>
                                                <div class="card-body"style="height: 400px; overflow-y: auto;">
                                                    <ul class="timeline">
                                                        <?php if ($resultMyPlantNote->num_rows > 0): ?>
                                                            <?php while ($rowNote = $resultMyPlantNote->fetch_assoc()): ?>
                                                                <?php
                                                                // Fetch note details
                                                                $note_title = $rowNote['note_tittle'];
                                                                $note_content = $rowNote['note_content'];
                                                                $note_img = $rowNote['note_img'];
                                                                $note_date = $rowNote['note_date'];
                                                                ?>
                                                                <li class="timeline-item timeline-item-transparent">
                                                                    <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-primary"></span></span>
                                                                    <div class="timeline-event">
                                                                        <div class="timeline-header border-bottom mb-3 d-flex justify-content-between">
                                                                            <div>
                                                                                <h6 class="mb-0"><?= $note_title ?></h6>
                                                                                <span class="text-muted"><?= date('j F Y', strtotime($note_date)) ?></span>
                                                                            </div>
                                                                            
                                                                        </div>
                                                                        <div class="d-flex justify-content-between flex-wrap mb-2">
                                                                            <div>
                                                                                <p><?= $note_content ?></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            <?php endwhile; ?>
                                                        <?php else: ?>
                                                            <li class="timeline-item">No notes available for this plant.</li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div> 


                            </div> 
                        </div>
                                        
                        <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
                            <!-- Your content for Notes tab -->
                            
                            <div class="col-xl-12 mb-4 mb-xl-0">
                                <div class="card">
                                    <h5 class="card-header"> Notes</h5>
                                    <div class="card-body">
                                        <?php
                                        // Reset the internal pointer of the result set back to the beginning
                                        $resultMyPlantNote->data_seek(0);

                                        if ($resultMyPlantNote->num_rows > 0): ?>
                                            <?php while ($rowNote = $resultMyPlantNote->fetch_assoc()): ?>
                                                <?php
                                                // Fetch note details
                                                $note_title = $rowNote['note_tittle'];
                                                $note_content = $rowNote['note_content'];
                                                $note_img = $rowNote['note_img'];
                                                $note_date = $rowNote['note_date'];
                                                ?>
                                                <div class="card mb-3">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <h6 class="mb-0"><?= $note_title ?></h6>
                                                                <span class="text-muted"><?= date('j F Y', strtotime($note_date)) ?></span>
                                                            </div>
                                                            <div class="dropdown">
                                                                <button class="btn p-0" type="button" id="topic" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="topic">
                                                                    

                                                                    <button class="dropdown-item edit-note" type="button" 
                                                                        data-note-id="<?= $rowNote['note_id'] ?>" 
                                                                        data-note-title="<?= htmlspecialchars($note_title, ENT_QUOTES, 'UTF-8') ?>" 
                                                                        data-note-content="<?= htmlspecialchars($note_content, ENT_QUOTES, 'UTF-8') ?>" 
                                                                        data-note-img="<?= base64_encode($note_img) ?>" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#EditNoteModal">
                                                                        Edit
                                                                    </button>

                                                                    <button class="dropdown-item delete-note" type="button" 
                                                                        data-note-id="<?= $rowNote['note_id'] ?>" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#DeleteNoteModal">
                                                                        Delete
                                                                    </button>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between flex-wrap mb-0">
                                                            <div class ="mt-3">
                                                            <?php if (!empty($note_img)): ?>
                                                                <div class="image-container mb-4 clickable" data-bs-toggle="modal" data-bs-target="#imageModal" data-src="data:image/jpeg;base64,<?= base64_encode($note_img) ?>">
                                                                    <img src="data:image/jpeg;base64,<?= base64_encode($note_img) ?>" alt="Note Image" class="img-fluid" style="width: 400px; height: 300px; object-fit: cover;">
                                                                </div>
                                                            <?php endif; ?>
                                                                <p><?= $note_content ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <p class="mb-0">No notes available for this plant.</p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>



                        </div>

                        <div class="tab-pane fade" id="navs-justified-messages" role="tabpanel">
                            <!-- Content wrapper -->
                            <div class="content-wrapper">

                                <!-- Content -->
                                <div class="container-xxl flex-grow-1 container-p-y">

                                    <div class="card app-calendar-wrapper">
                                        <div class="row g-0">
                                            <!-- Calendar Sidebar -->
                                            <div class="col app-calendar-sidebar" id="app-calendar-sidebar">
                                                <div class="border-bottom p-4 my-sm-0 mb-3">
                                                    <div class="d-grid">
                                                        <button class="btn btn-primary btn-toggle-sidebar" data-bs-toggle="offcanvas" data-bs-target="#addEventSidebar" aria-controls="addEventSidebar">
                                                            <i class="bx bx-plus me-1"></i>
                                                            <span class="align-middle">Add Event</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- Move the inline calendar here -->
                                                <div class = "p-4">
                                                    <div class="ms-n2" id='inline-calendar'></div>
                                                    <hr class="container-m-nx my-4">
                                                </div>
                                            </div>
                                            <!-- /Calendar Sidebar -->
                                            

                                            <!-- Calendar & Modal -->
                                            <div class="col app-calendar-content">
                                                <div class="card shadow-none border-0">
                                                    <div class="card-body pb-0">
                                                        <!-- FullCalendar -->
                                                        <div id="calendar"></div>
                                                    </div>
                                                </div>
                                                <div class="app-overlay"></div>
                                            </div>
                                            <!-- /Calendar & Modal -->

                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- / Content -->
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

                    <div class="mydiscussion myfixed-bottom">
                        <button class="btn btn-primary btn-discussion-add back-to-top m-3" type="button" data-bs-toggle="modal" data-bs-target="#AddNoteModal">New Note</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


                    

<!-- The Edit Orchid Modal -->
<div class="modal fade" id="EditOrchidModal" tabindex="-1" aria-labelledby="EditOrchidModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="text-center">
                    <h5 class="modal-title">Edit Orchid</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editOrchidForm" enctype="multipart/form-data">
                    <input type="hidden" id="editOrchidId" name="myplant_id">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="editOrchidName" class="form-label">Orchid Name</label>
                            <input type="text" class="form-control" id="editOrchidName" name="myplant_name" placeholder="Enter Orchid Name" required>
                        </div>
                        <div class="col">
                            <label for="editOrchidSpecies" class="form-label">Orchid Species</label>
                            <input type="text" class="form-control" id="editOrchidSpecies" name="myplant_species" placeholder="Enter Orchid Species" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editPlantNote" class="form-label">Plant Note</label>
                        <textarea class="form-control" id="editPlantNote" name="myplant_note" rows="3" placeholder="Enter Plant Note" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editPlantImage" class="form-label">Plant Image</label>
                        <input type="file" class="form-control" id="editPlantImage" name="myplant_img">
                        <div id="editPlantImageContainer" class="mt-2"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="editWateringFrequency" class="form-label">Watering Frequency (in days)</label>
                            <input type="number" class="form-control" id="editWateringFrequency" name="wateringFrequency" placeholder="Enter Watering Frequency" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="editFertilizingFrequency" class="form-label">Fertilizing Frequency (in days)</label>
                            <input type="number" class="form-control" id="editFertilizingFrequency" name="fertilizingFrequency" placeholder="Enter Fertilizing Frequency" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="updateOrchid"data-myplant-id="" >Save Changes</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>

<!-- Deletion Orchid Modal -->
<div class="modal fade" id="DeleteOrchidModal" tabindex="-1" aria-labelledby="DeleteOrchidModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DeleteOrchidModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this orchid?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <!-- Add a unique ID to the "Delete" button in the modal -->
                <button type="button" class="btn btn-danger" id="confirmDeleteOrchid" data-myplant-id="">Delete</button>
            </div>
        </div>
    </div>
</div>


<!-- The Add Note Modal -->
<div class="modal fade" id="AddNoteModal" tabindex="-1" aria-labelledby="AddNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="text-center">
                    <h5 class="modal-title">Add New Note</h5> <!-- Change modal title -->
                </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="noteForm" enctype="multipart/form-data">
                    <input type="hidden" id="myplantId" name="myplant_id" value="<?= $myplant_id ?>">    
                    <div class="mb-3">
                            <label for="noteTitle" class="form-label">Note Title</label>
                            <input type="text" class="form-control" id="noteTitle" name="note_title" placeholder="Enter note Title" required>
                        </div>

                        <div class="mb-3">
                            <label for="noteContent" class="form-label">Note Content</label>
                            <textarea class="form-control" id="noteContent" name="note_content" rows="3" placeholder="Enter Note Content" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="noteImage" class="form-label">Note Image</label>
                            <input type="file" class="form-control" id="noteImage" name="note_img" >
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Note</button> <!-- Change button label -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- The Edit Note Modal -->
<div class="modal fade" id="EditNoteModal" tabindex="-1" aria-labelledby="EditNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="text-center">
                    <h5 class="modal-title">Edit Note</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editnoteForm" enctype="multipart/form-data">
                    <input type="hidden" id="editNoteId" name="note_id">
                    <div class="mb-3">
                        <label for="editNoteTitle" class="form-label">Note Title</label>
                        <input type="text" class="form-control" id="editNoteTitle" name="note_title" placeholder="Enter note Title" required>
                    </div>

                    <div class="mb-3">
                        <label for="editNoteContent" class="form-label">Note Content</label>
                        <textarea class="form-control" id="editNoteContent" name="note_content" rows="3" placeholder="Enter Note Content" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editNoteImage" class="form-label">Note Image</label>
                        <input type="file" class="form-control" id="editNoteImage" name="note_img">
                        <div id="editNoteImageContainer" class="mt-2"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="updateNote"data-note-id="" >Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Deletion Note Modal -->
<div class="modal fade" id="DeleteNoteModal" tabindex="-1" aria-labelledby="DeleteNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DeleteNoteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this note?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <!-- Add a unique ID to the "Delete" button in the modal -->
                <button type="button" class="btn btn-danger" id="confirmDeleteNote" data-note-id="">Delete</button>
            </div>
        </div>
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
    
<?php
    $eventQuery = "SELECT * FROM event WHERE user_id = $currentUserId AND myplant_id = $myplant_id";
    $eventResult = $conn->query($eventQuery);

    $events = array(); // Array to store events

    if ($eventResult && $eventResult->num_rows > 0) {
        while ($eventData = $eventResult->fetch_assoc()) {
            // Assign different colors based on event type
            $eventColor = '';
            switch ($eventData['event_type']) {
                case 'Watering':
                    $eventColor = '#d0foff'; 
                    break;
                case 'Fertilizing':
                    $eventColor = '#9dcd5a'; 
                    break;
                case 'New add':
                    $eventColor = '#ff0000'; 
                    break;
                default:
                    $eventColor = '#ffeb5b'; 
            }

            // Format the event data for FullCalendar
            $events[] = array(
                'title' => $eventData['event_description'],
                'start' => $eventData['event_date'],
                'backgroundColor' => $eventColor, // Set the background color
                // Add more fields as needed
            );
        }
    }
?>

    $('.clickable').click(function () {
            var src = $(this).data('src');
            $('#enlargedImage').attr('src', src);
    });
    

    //Add note 
    $(document).on('submit', '#noteForm', function (e) {
    e.preventDefault();
            var formData = new FormData(this);
            formData.append("AddMyNote", true);
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
                        console.log("addnote1");
                        $('#errorMessage').removeClass('d-none');
                        $('#errorMessage').text(response.message);
                    } else if (response.status == 200) {
                        console.log("addnote2");
                        $('#errorMessage').addClass('d-none');
                        $('#AddNoteModal').modal('hide');
                        $('#noteForm')[0].reset();
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success(response.message);
                        // Reload the content or update as needed
                        $('#example').load(location.href + " #example");
                    } else if (response.status == 500) {
                        console.log("addnote3");
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

    //delete note 
    document.addEventListener('DOMContentLoaded', function () {
        var deleteNoteModal = new bootstrap.Modal(document.getElementById('DeleteNoteModal'));

        deleteNoteModal._element.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            // Extract data from the button's data attributes
            var noteId = button.getAttribute('data-note-id');

            // Set the note ID in the modal's "Delete" button
            var confirmDeleteNoteButton = document.getElementById('confirmDeleteNote');
            confirmDeleteNoteButton.setAttribute('data-note-id', noteId);
        });

        // Assuming you have the "Delete" button in your modal with the ID 'confirmDeleteNote'
        var confirmDeleteNoteButton = document.getElementById('confirmDeleteNote');

        if (confirmDeleteNoteButton) {
            confirmDeleteNoteButton.addEventListener('click', function () {
                // Get the note ID from the modal
                var noteId = confirmDeleteNoteButton.getAttribute('data-note-id');

                // Perform an AJAX request to delete the note
                $.ajax({
                    type: 'POST',
                    url: 'code.php',
                    data: { deleteNote: true, note_id: noteId },
                    success: function (response) {
                        if (response.status === 200) {
                            alertify.set('notifier', 'position', 'top-right');
                            alertify.success('Note deleted successfully!');
                            $('#noteCard_' + noteId).remove();
                            // Close the modal after deletion
                            deleteNoteModal.hide();
                            
                        } else {
                            alertify.set('notifier', 'position', 'top-right');
                            alertify.error('Failed to delete note. Please try again.');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // Handle the error, e.g., show an error message
                        console.error('AJAX error:', errorThrown);
                        alertify.error('An error occurred while deleting the note.');
                    }
                });
            });
        }
    });

    //populate edit note
    document.addEventListener('DOMContentLoaded', function() {
        var editNoteButtons = document.querySelectorAll('.edit-note');

        editNoteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var noteId = this.dataset.noteId;
                var noteTitle = this.dataset.noteTitle;
                var noteContent = this.dataset.noteContent;
                var noteImg = this.dataset.noteImg;

                populateEditNoteModal(noteId, noteTitle, noteContent, noteImg);
            });
        });

        function populateEditNoteModal(noteId, noteTitle, noteContent, noteImg) {
            document.getElementById('editNoteId').value = noteId;
            document.getElementById('editNoteTitle').value = noteTitle;
            document.getElementById('editNoteContent').value = noteContent;

            var editNoteImageContainer = document.getElementById('editNoteImageContainer');
            editNoteImageContainer.innerHTML = '';

            if (noteImg) {
                var img = document.createElement('img');
                img.src = 'data:image/jpeg;base64,' + noteImg;
                img.alt = 'Note Image';
                img.className = 'img-fluid';
                img.style.width = 'auto';
                img.style.maxHeight = '200px'; // You can adjust the height as needed
                editNoteImageContainer.appendChild(img);
            }
        }
    });

    //submit edit note 
    $(document).on('submit', '#editnoteForm', function (e) {
        e.preventDefault();
        var updateNoteButton = document.getElementById('updateNote');
        var noteId = updateNoteButton.getAttribute('data-note-id');

        var formData = new FormData(this);
            formData.append("EditMyNote", true);
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

                        $('#errorMessage').removeClass('d-none');
                        $('#errorMessage').text(response.message);

                    } else if (response.status == 200) {

                        $('#errorMessage').addClass('d-none');
                        $('#EditNoteModal').modal('hide');
                        $('#noteForm')[0].reset();

                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success(response.message);
                        // Reload the content or update as needed
                        $('#example').load(location.href + " #example");

                    } else if (response.status == 500) {
                        alert(response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // Handle the error, e.g., show an error message
                    console.error('AJAX error:', errorThrown);
                    alertify.error('An error occurred while updating the note.');
                }
            });
    });

    //populate edit orchid
    document.addEventListener('DOMContentLoaded', function () {
        var editOrchidModal = new bootstrap.Modal(document.getElementById('EditOrchidModal'));

        // Attach an event listener to the modal before it is shown
        editOrchidModal._element.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            // Extract data from the button's data attributes
            var myplantId = button.getAttribute('data-myplant-id');
            var myplantName = button.getAttribute('data-myplant-name');
            var myplantSpecies = button.getAttribute('data-myplant-species');
            var myplantImg = button.getAttribute('data-myplant-img');
            var myplantNote = button.getAttribute('data-myplant-note');
            var wateringFrequency = button.getAttribute('data-watering-frequency');
            var fertilizingFrequency = button.getAttribute('data-fertilizing-frequency');

            // Populate the form fields with the data
            document.getElementById('editOrchidId').value = myplantId;
            document.getElementById('editOrchidName').value = myplantName;
            document.getElementById('editOrchidSpecies').value = myplantSpecies;
            document.getElementById('editPlantNote').value = myplantNote;
            document.getElementById('editWateringFrequency').value = wateringFrequency;
            document.getElementById('editFertilizingFrequency').value = fertilizingFrequency;

            // Optionally, handle the image display (you may want to implement this part)
            var editPlantImageContainer = document.getElementById('editPlantImageContainer');
            editPlantImageContainer.innerHTML = ''; // Clear previous content

            if (myplantImg) {
                var img = document.createElement('img');
                img.src = 'data:image/jpeg;base64,' + myplantImg;
                img.alt = 'Orchid Image';
                img.className = 'img-fluid';
                img.style.width = 'auto';
                img.style.maxHeight = '200px'; // You can adjust the height as needed
                editPlantImageContainer.appendChild(img);
            }
        });
    });

    //delete orchid 
    document.addEventListener('DOMContentLoaded', function () {
        var deleteOrchidModal = new bootstrap.Modal(document.getElementById('DeleteOrchidModal'));

        deleteOrchidModal._element.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;

            // Extract data from the button's data attributes
            var OrchidId = button.getAttribute('data-myplant-id');

            
            var confirmDeleteOrchidButton = document.getElementById('confirmDeleteOrchid');
            confirmDeleteOrchidButton.setAttribute('data-myplant-id', OrchidId);
        });

        
        var confirmDeleteOrchidButton = document.getElementById('confirmDeleteOrchid');

        if (confirmDeleteOrchidButton) {
            confirmDeleteOrchidButton.addEventListener('click', function () {
                
                var OrchidId = confirmDeleteOrchidButton.getAttribute('data-myplant-id');

                // Perform an AJAX request to delete the note
                $.ajax({
                    type: 'POST',
                    url: 'code.php',
                    data: { deleteOrchid: true, Orchid_id: OrchidId },
                    success: function (response) {
                        if (response.status === 200) {
                            alertify.set('notifier', 'position', 'top-right');
                            alertify.success('Orchid deleted successfully!');
                            $('#OrchidCard_' + OrchidId).remove();
                            
                            deleteOrchidModal.hide();
                            setTimeout(function () {
                                window.location.href = 'user_myplant.php';
                            }, 1000);
                            
                        } else {
                            alertify.set('notifier', 'position', 'top-right');
                            alertify.error('Failed to delete Orchid. Please try again.');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // Handle the error, e.g., show an error message
                        console.error('AJAX error:', errorThrown);
                        alertify.error('An error occurred while deleting the Orchid.');
                    }
                });
            });
        }
    });

    //submit edit orchid 
    $(document).on('submit', '#editOrchidForm', function (e) {
        e.preventDefault();
        var updateOrchidButton = document.getElementById('updateOrchid');
        var OrchidId = updateOrchidButton.getAttribute('data-myplant-id');

        var formData = new FormData(this);
            formData.append("EditMyOrchid", true);
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

                        $('#errorMessage').removeClass('d-none');
                        $('#errorMessage').text(response.message);

                    } else if (response.status == 200) {

                        $('#errorMessage').addClass('d-none');
                        $('#EditOrchidModal').modal('hide');
                        $('#OrchidForm')[0].reset();

                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success(response.message);
                        // Reload the content or update as needed
                        $('#example').load(location.href + " #example");

                    } else if (response.status == 500) {
                        alert(response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // Handle the error, e.g., show an error message
                    console.error('AJAX error:', errorThrown);
                    alertify.error('An error occurred while updating the Orchid.');
                }
            });
    });


    //calendar
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr('.flatpickr', {
            enableTime: false, // Disable time selection, adjust as needed
            dateFormat: "Y-m-d", // Specify the date format
        });
    });
    
    
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
            events: <?php echo json_encode($events); ?>, // Pass the events array
            eventDidMount: function (info) {
                // Set the background color dynamically
                info.el.style.backgroundColor = info.event.backgroundColor;

                $(info.el).tooltip({
                    title: info.event.title,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            }
        });

        fullCalendar.render();

        // Initialize Flatpickr
        const inlineCalendar = document.getElementById('inline-calendar');
        const flatpickrInstance = flatpickr(inlineCalendar, {
            inline: true,
            enableTime: false,
            dateFormat: "Y-m-d",
            onChange: function (selectedDates, dateStr, instance) {
                fullCalendar.gotoDate(selectedDates[0]);
            }
        });
    });






       






</script>




</body>
</html>
