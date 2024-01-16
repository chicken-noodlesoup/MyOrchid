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
    <title>MY ORCHID</title>
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
        <h3 class="pb-1 mb-2">Discussion Forum</h3>

        <div class="row justify-content-end">

            <!-- Filter by Tags -->
            <div class="col-md-2 text-md-end mb-3">
                <button id="filterButton" class="btn btn-primary">Filter</button>
            </div>

            <!-- Sort by -->
            <div class="col-md-2 text-md-end mb-3">
                <div class="btn-group">
                    <button
                        type="button"
                        class="btn btn-primary dropdown-toggle"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        Sort by
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?sort=newest">Newest First</a></li>
                        <li><a class="dropdown-item" href="?sort=oldest">Oldest First</a></li>
                        <li><a class="dropdown-item" href="?sort=most-liked">Most Liked</a></li>
                        <li><a class="dropdown-item" href="?sort=most-commented">Most Commented</a></li>
                    </ul>
                </div>
            </div>

        </div>

        <div id="filterOptions" style="display: none;">
            <!-- Add your filter options here -->
            <button class="btn btn-light m-2">Show All</button>
            <button class="btn btn-light m-2">Care Tips</button>
            <button class="btn btn-light m-2">Species Identification</button>
            <button class="btn btn-light m-2">Blooming Cycles</button>
            <button class="btn btn-light m-2">Orchid Species</button>
            <button class="btn btn-light m-2">Pests And Diseases</button>
            <button class="btn btn-light m-2">Growing Mediums</button>
            <button class="btn btn-light m-2">Propagation Techniques</button>
            <button class="btn btn-light m-2">Orchid Artistry</button>
            <button class="btn btn-light m-2">Others</button>
            <!-- Add more filter options as needed -->
        </div>




        <?php
            
            // Number of items per page (adjust as needed)
            $itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 12;

            // Current page (adjust as needed)
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $itemsPerPage;

            // SQL query to get the total number of items
            $countQuery = "SELECT COUNT(*) AS total FROM discussion_topic";
            $countResult = $conn->query($countQuery);

            // Check if the count query was successful
            if ($countResult) {
                $row = $countResult->fetch_assoc();
                $totalItems = $row['total'];
            } else {
                $totalItems = 0;
            }


            // Fetch discussion topics from the database
            $sql = "SELECT * FROM discussion_topic 
                    INNER JOIN user ON discussion_topic.user_id = user.user_id ";

            // Modify the query based on the sort parameter
            if (isset($_GET['sort'])) {
                switch ($_GET['sort']) {
                    case 'newest':
                        $sql .= "ORDER BY discussion_topic.timestamp DESC";
                        break;
                    case 'oldest':
                        $sql .= "ORDER BY discussion_topic.timestamp ASC";
                        break;
                    case 'most-liked':
                        $sql .= "ORDER BY (SELECT COUNT(*) FROM like_topic WHERE like_topic.topic_id = discussion_topic.topic_id) DESC";
                        break;
                    case 'most-commented':
                        $sql .= "ORDER BY (SELECT COUNT(*) FROM comment_topic WHERE comment_topic.topic_id = discussion_topic.topic_id) DESC";
                        break;
                    default:
                        // Default sorting (e.g., newest first)
                        $sql .= "ORDER BY discussion_topic.timestamp DESC";
                }
            } else {
                // Default sorting (e.g., newest first)
                $sql .= "ORDER BY discussion_topic.timestamp DESC";
            }

            $sql .= " LIMIT $itemsPerPage OFFSET $offset";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $topic_id = $row['topic_id'];
                    $topicTitle = $row['topic_title'];
                    $content = substr($row['content'], 0, 50); // Display the first 50 characters of content
                    $username = $row['Username'];
                    $timestamp = $row['timestamp'];

                    // Calculate the time difference using DateTime objects
                    $timezone = new DateTimeZone('Asia/Kuala_Lumpur');
                    $topicTime = new DateTime($timestamp, $timezone);
                    $currentTime = new DateTime(null, $timezone);

                    $interval = $topicTime->diff($currentTime);


                    // Determine the appropriate unit (just now, minutes ago, hours ago, or date)
                    if ($interval->y > 0 || $interval->m > 0 || $interval->d > 0) {
                        // If years, months, or days ago, show the date
                        $timeAgo = $topicTime->format('M j, Y');
                    } elseif ($interval->h > 0 && $interval->i > 0) {
                        // If more than an hour and more than a minute ago, show hours and minutes
                        $timeAgo = $interval->format('%h hours and %i minutes ago');
                    } elseif ($interval->h > 0) {
                        // If more than an hour ago, show hours
                        $timeAgo = $interval->format('%h') . ' ' . ($interval->format('%h') > 1 ? "hours ago" : "hour ago");
                    } elseif ($interval->i > 0) {
                        // If more than a minute ago, show minutes
                        $timeAgo = $interval->format('%i') . ' ' . ($interval->format('%i') > 1 ? "minutes ago" : "minute ago");
                    } else {
                        // Otherwise, just now
                        $timeAgo = "Just now";
                    }


                    // Fetch like count for each discussion topic
                    $likeCountQuery = "SELECT COUNT(*) AS like_count FROM like_topic WHERE topic_id = $topic_id";
                    $likeCountResult = $conn->query($likeCountQuery);
                    $likeCount = $likeCountResult ? $likeCountResult->fetch_assoc()['like_count'] : 0;

                    // Fetch like status for each discussion topic
                    $likeStatusQuery = "SELECT COUNT(*) AS like_count FROM like_topic WHERE topic_id = $topic_id AND user_id = $currentUserId";
                    $likeStatusResult = $conn->query($likeStatusQuery);
                    $likeStatusCount = $likeStatusResult ? $likeStatusResult->fetch_assoc()['like_count'] : 0;
                    $likeStatus = ($likeStatusCount > 0);

                    // Display the discussion card
                    // Output timestamps, current time, and interval for debugging
                       // echo "Timestamp: " . $topicTime->format('Y-m-d H:i:s') . "<br>";
                       // echo "Current Time: " . $currentTime->format('Y-m-d H:i:s') . "<br>";
                        //echo "Interval: " . $interval->format('%d days and %h hours and %i minutes') . "<br>";

                    echo "

                        <div class='card mb-4' onmouseover=\"this.style.backgroundColor='#f0f0f0';\" onmouseout=\"this.style.backgroundColor='white';\">
                        <div class='card-header d-flex justify-content-between align-items-center'>
                            <a href='admin_discussion_details.php?topic_id=$topic_id'>$topicTitle</a>
                            <button class='btn btn-sm btn-danger' onclick='deleteDiscussion($topic_id)'>
                                <i class='bx bx-trash'></i> Delete
                            </button>
                        </div>
                            <div class='card-body'>
                                <p class='card-text'>$content...</p>

                                <div class='d-flex justify-content-between align-items-center'>
                                    <div>
                                        <span class='like-icon' data-topic_id='$topic_id' data-user_id='$currentUserId' data-liked='" . ($likeStatus ? 'true' : 'false') . "'>
                                            <i  class='bi " . ($likeStatus ? 'bi-heart-fill' : 'bi-heart') . "'></i>
                                        </span>
                                        <span class='ms-1 like-count'>$likeCount </span>
                                        <span class='ms-1'><a href='admin_discussion_details.php?topic_id=$topic_id'><small>Reply</small></a></span>
                                    </div>
                                    <div class='text-muted'>
                                        <small>Posted by $username $timeAgo</small>
                                    </div>
                                </div>
                            </div>
                        </div>
        ";


                }
            } else {
                echo "No discussion topics found.";
            }



        ?>  
    
    <!-- Basic Pagination -->
    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="demo-inline-spacing">
                    <?php
                    $totalPages = ceil($totalItems / $itemsPerPage);

                    // Check if there are more than 12 items to display pagination
                    if ($totalItems > 12) {
                        echo '<ul class="pagination justify-content-end">';
                        echo '<div class="pagination">';
                        if ($page > 1) {
                            echo '<li class="page-item first">
                                    <a class="page-link" href="?page=1&itemsPerPage=' . $itemsPerPage . '"
                                    ><i class="tf-icon bx bx-chevrons-left"></i
                                    ></a>
                                    </li>';
                            echo '<li class="page-item prev">
                                    <a class="page-link" href="?page=' . ($page - 1) . '&itemsPerPage=' . $itemsPerPage . '"
                                    ><i class="tf-icon bx bx-chevron-left"></i
                                    ></a>
                                    </li>';
                        }
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo '<li class="page-item">
                                    <a class="page-link"href="?page=' . $i . '&itemsPerPage=' . $itemsPerPage . '" ' . ($page == $i ? 'class="active"' : '') . '>' . $i . '</a></li>';
                        }
                        if ($page < $totalPages) {
                            echo '<li class="page-item next">
                                    <a class="page-link" href="?page=' . ($page + 1) . '&itemsPerPage=' . $itemsPerPage . '"
                                ><i class="tf-icon bx bx-chevron-right"></i
                                    ></a>
                                </li>';
                            echo '<li class="page-item last">
                                    <a class="page-link" href="?page=' . $totalPages . '&itemsPerPage=' . $itemsPerPage . '"
                                    ><i class="tf-icon bx bx-chevrons-right"></i
                                    ></a>
                                    </li>';
                        }
                        echo '</div>';
                        echo '</ul>';
                    }
                    ?>
                </div>
                <!--/ Basic Pagination -->
            </div>
        </div>
    </div>


    <div class="mydiscussion myfixed-bottom">
        
         <button class="btn btn-primary btn-discussion-add back-to-top" type="button" data-bs-toggle="modal" data-bs-target="#AddDiscussion_Modal" aria-controls="AddDiscussion_Modal"
         >New Discussion</button>

    </div>


    </div>

    <!-- Modal -->
<div class=" modal fade" id="AddDiscussion_Modal" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormLabel">New Topic</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Your form goes here -->
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


    
    
    <?php
        require 'footer.php'; 
    ?>
    
</div>
      

          

    <!-- JavaScript Libraries -->
    <<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        placeholder: "Maximum of 3"
    });
    });

    document.getElementById('filterButton').addEventListener('click', function() {
        var filterOptions = document.getElementById('filterOptions');
        filterOptions.style.display = (filterOptions.style.display === 'none' || filterOptions.style.display === '') ? 'block' : 'none';
    });
    

    $(document).ready(function() {
        $('.like-icon').on('click', function() {
            console.log('Like button clicked');
            // Store a reference to the clicked element
            var $clickedElement = $(this);

            var topic_id = parseInt($clickedElement.data('topic_id'));
            var user_id = parseInt($clickedElement.data('user_id'));
            var isLiked = $clickedElement.find('i').hasClass('bi-heart-fill');

            console.log('topic_id:', topic_id);
            console.log('user_id:', user_id);
            console.log('isLiked:', isLiked);

            $.ajax({
                type: 'POST',
                url: 'like_topic_handler.php',
                data: {
                    topic_id: topic_id,
                    user_id: user_id,
                    liked: !isLiked  // Toggle the liked status
                },
                success: function(response) {
                    response = JSON.parse(response); // Parse the JSON response

                    if (response.success) {
                        var likeCountElement = $clickedElement.closest('.card').find('.like-count');
                        var newLikeCount = isLiked ? parseInt(likeCountElement.text()) - 1 : parseInt(likeCountElement.text()) + 1;
                        likeCountElement.text(newLikeCount);

                        // Toggle the heart icon for the current user
                        var heartIcon = $clickedElement.find('i');
                        heartIcon.toggleClass('bi-heart-fill bi-heart');
                    } else {
                        console.error('Failed to update like status.');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Ajax request failed:', textStatus, errorThrown);
                }
            });
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
                    $('#AddDiscussion_Modal').modal('hide');
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




