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
    <link rel="stylesheet" href="lib/dropzone/dropzone.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/themes/default.min.css" />



    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style2.css" />
    <link rel="stylesheet" href="css/style3.css" />

  </head>
  <body>

    <?php
    require 'navbar.php'; // Include your navbar
    require 'user_sidebar.php';
    ?>



<!-- Main Content Area -->
<div id="main">
    <div class="container" style="">

    <?php
        // Get the topic_id from the URL
        $topicTitle = $timestamp = $content = $username = "";

        if (isset($_GET['topic_id'])) {
            $topic_id = $_GET['topic_id'];

            // Fetch discussion topic
            $sqlTopic = "SELECT discussion_topic.*, user.*, tags.tag_name
            FROM discussion_topic
            INNER JOIN user ON discussion_topic.user_id = user.user_id
            LEFT JOIN discussion_tag ON discussion_topic.topic_id = discussion_tag.topic_id
            LEFT JOIN tags ON discussion_tag.tag_id = tags.tag_id
            WHERE discussion_topic.topic_id = ?";

            $stmtTopic = $conn->prepare($sqlTopic);
            $stmtTopic->bind_param("i", $topic_id);
            $stmtTopic->execute();

            $resultTopic = $stmtTopic->get_result();

            if ($resultTopic->num_rows > 0) {
                $row = $resultTopic->fetch_assoc();

                $topicTitle = $row['topic_title'];
                $content = $row['content'];
                $timestamp = $row['timestamp'];
                $username = $row['Username'];
                $content_img = $row['content_img']; 

                // Fetch like count for the discussion topic
                $likeCountQuery = "SELECT COUNT(*) AS like_count FROM like_topic WHERE topic_id = ?";
                $stmtLikeCount = $conn->prepare($likeCountQuery);
                $stmtLikeCount->bind_param("i", $topic_id);
                $stmtLikeCount->execute();
                $likeCountResult = $stmtLikeCount->get_result();
                $likeCount = $likeCountResult ? $likeCountResult->fetch_assoc()['like_count'] : 0;

                // Fetch like status for the discussion topic
                $likeStatusQuery = "SELECT COUNT(*) AS like_count FROM like_topic WHERE topic_id = ? AND user_id = ?";
                $stmtLikeStatus = $conn->prepare($likeStatusQuery);
                $stmtLikeStatus->bind_param("ii", $topic_id, $currentUserId);
                $stmtLikeStatus->execute();
                $likeStatusResult = $stmtLikeStatus->get_result();
                $likeStatusCount = $likeStatusResult ? $likeStatusResult->fetch_assoc()['like_count'] : 0;
                $likeStatus = ($likeStatusCount > 0);

                // Fetch comments for the discussion topic
                $commentsQuery = "SELECT comment_topic.*, user.Username, user.profilepic 
                  FROM comment_topic 
                  INNER JOIN user ON comment_topic.user_id = user.user_id 
                  WHERE comment_topic.topic_id = ? 
                  ORDER BY comment_topic.timestamp DESC";

                $stmtComments = $conn->prepare($commentsQuery);
                $stmtComments->bind_param("i", $topic_id);
                $stmtComments->execute();

                $resultComments = $stmtComments->get_result();

                // Check if there are any comments
                $commentsExist = $resultComments->num_rows > 0;

                // Close prepared statements
                $stmtLikeCount->close();
                $stmtLikeStatus->close();
                $stmtComments->close();

                // Fetch tags associated with the topic
                $sqlTags = "SELECT tags.tag_name
                FROM discussion_tag
                LEFT JOIN tags ON discussion_tag.tag_id = tags.tag_id
                WHERE discussion_tag.topic_id = ?";

                $stmtTags = $conn->prepare($sqlTags);
                $stmtTags->bind_param("i", $topic_id);
                $stmtTags->execute();

                $resultTags = $stmtTags->get_result();

                // Fetch tags into an array
                $tags = [];
                while ($tagRow = $resultTags->fetch_assoc()) {
                $tags[] = $tagRow['tag_name'];
                }

                // Close the prepared statement for tags
                $stmtTags->close();

            } else {
                echo "Discussion topic not found.";
            }

            // Close the prepared statement
            $stmtTopic->close();
        } else {
            echo "Topic ID not provided in the URL.";
        }



        // Close the database connection
        $conn->close();
    ?>

    <!-- Display the fetched information -->
    <div id="main">
        <div class="container" style="margin-top: 50px;">
            <div class="mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $topicTitle; ?></h5>
                        <p class="card-text">
                            <small class="text-muted"><?php echo $timestamp; ?> by <?php echo $username; ?></small>
                        </p>
                        <!-- Display Tags -->
                        
                        <?php if (!empty($tags)) : ?>
                            <p class="card-text">
                                <strong>Tags : </strong>
                                <?php foreach ($tags as $tag) : ?>
                                    <span class="badge bg-primary"><?php echo $tag; ?></span>
                                <?php endforeach; ?>
                            </p>
                        <?php endif; ?>
                        
                        <p class="card-text"><?php echo nl2br($content); ?></p>
                        
                        <?php if (!empty($row['content_img'])) : ?>
                            <div class="image-container mb-4 clickable" data-bs-toggle="modal" data-bs-target="#imageModal" data-src="data:image/jpeg;base64,<?php echo base64_encode($content_img); ?>">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($content_img); ?>" alt="Discussion Image" class="img-fluid" width="200" height="200">
                            </div>
                        <?php endif; ?>


                        <div class="like-container">
                            <span class='like-icon' 
                                data-topic_id='<?php echo $topic_id; ?>' 
                                data-user_id='<?php echo $currentUserId; ?>' 
                                data-liked='<?php echo ($likeStatus ? 'true' : 'false'); ?>'>
                                <i class='bi <?php echo ($likeStatus ? 'bi-heart-fill' : 'bi-heart'); ?>'></i>
                            </span>
                            <span class='ms-1 like-count'><?php echo $likeCount; ?> </span>
                            <a href="#" class="icon-link ms-2"><i class='bx bx-comment'></i></a>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Comment Form -->
        <div class="commentsection">
            <input type="hidden" id="topic_id" value="<?php echo $topic_id; ?>">
            <div class="form-group comment-form">
                <textarea class="form-control" rows="4" placeholder="Write a comment..." required></textarea>
                
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary cancel-btn" style="display: none; margin: 5px">Cancel</button>
                    <button type="submit" class="btn btn-primary comment-btn" style="display: none; margin: 5px">Comment</button>
                </div>
            </div>
        


            <?php
            // Display comments if they exist
            if ($commentsExist) {
                while ($comment = $resultComments->fetch_assoc()) {
                    ?>
                   
                   <div class="card mt-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($comment['profilepic']); ?>" alt="Profile Picture" class="rounded-circle" width="40" height="40">
                                <div class="ms-2">
                                    <p class="card-text"><?php echo $comment['comment_content']; ?></p>
                                    <small class="text-muted">
                                        <?php 
                                            // Convert timestamp to a custom format
                                            $formattedDate = date('g.i A, j F Y', strtotime($comment['timestamp']));
                                            echo $formattedDate; 
                                        ?> by <?php echo $comment['Username']; ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                }
            } else {
                // Display a message if there are no comments
                ?>
                <div class="alert alert-info mt-3" role="alert">
                    No comments available for this topic.
                </div>
                <?php
            }
            ?>
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


    <script src="js/script.js"></script>
    <script src="js/script2.js"></script>
    
    <!-- JavaScript to toggle visibility of the submit button -->
<script>

    $('.clickable').click(function () {
            var src = $(this).data('src');
            $('#enlargedImage').attr('src', src);
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

    $(document).ready(function () {
    
        var commentForm = $('.comment-form');
        var commentBtn = $('.comment-btn');
        var cancelBtn = $('.cancel-btn');
        var textarea = commentForm.find('textarea');
        var topicId = $('#topic_id').val();

        

        textarea.on('focus', function () {
            commentBtn.show();
            cancelBtn.show();
        });

        cancelBtn.on('click', function () {
            commentBtn.hide();
            cancelBtn.hide();
        });

        textarea.on('blur', function () {
            // If you want to hide the button when the textarea loses focus, you can add this event
            // commentBtn.hide();
            // cancelBtn.hide();
        });

        commentBtn.on('click', function () {
            
            var formData = new FormData();
            formData.append("commentContent", textarea.val());
            formData.append("topic_id", topicId);

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

                    // Clear out the textarea and reset buttons
                    textarea.val('');
                    commentBtn.hide();
                    cancelBtn.hide();

                    
                
                    if (response.status == 422) {
                        console.log("1");
                        $('#errorMessage').removeClass('d-none');
                        $('#errorMessage').text(response.message);

                    } else if (response.status == 200) {
                        $('#errorMessage').addClass('d-none');
                        console.log("2");

                        /*// Reload the content or update as needed after a delay
                        setTimeout(function () {
                            try {
                                console.log('Before reload:', $('#commentsection').html());
                                $('#commentsection').load(location.href + " #commentsection");
                                console.log('After reload:', $('#commentsection').html());
                            } catch (error) {
                                console.error('Error during reload:', error);
                            }
                        }, 1000); // You can adjust the delay as needed

                        // Reload the whole page after a delay
                        setTimeout(function () {
                            location.reload(true);
                        }, 1000); // You can adjust the delay as needed
                        */

                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success(response.message);


                        
                    } else if (response.status == 500) {
                        console.log("3");
                        alert(response.message);
                    }

                    

                },
                error: function () {
                    // Hide loading spinner on error
                    console.log('AJAX error:', error);
                    $('#loadingSpinner').addClass('d-none');
                    alert('An error occurred during the submission.');
                },
                complete: function () {
                    // Hide loading spinner on completion
                    $('#loadingSpinner').addClass('d-none');
                }
            });
        });
    });





</script>

</body>
</html>



