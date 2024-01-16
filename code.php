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



  if (isset($_POST['update_UserInfo'])) {
    $newUsername = mysqli_real_escape_string($conn, $_POST['Username']);
    $usernameToUpdate = $username;

    // Check if a new profile picture is uploaded
    if (!empty($_FILES['uploadedAvatar']['tmp_name'])) {
        $profilepic = $_FILES['uploadedAvatar'];

        // Convert image to base64
        $imageData = base64_encode(file_get_contents($profilepic['tmp_name']));

        // Update the profile picture in the database
        $updatePicQuery = "UPDATE user SET profilepic = ? WHERE username = ?";
        $updatePicStmt = mysqli_prepare($conn, $updatePicQuery);
        mysqli_stmt_bind_param($updatePicStmt, 'ss', $imageData, $usernameToUpdate);
        $resultPic = mysqli_stmt_execute($updatePicStmt);
        mysqli_stmt_close($updatePicStmt);

        if (!$resultPic) {
            $response = array('status' => 500, 'message' => 'Failed to update profile picture in the database: ' . mysqli_error($conn));
            echo json_encode($response);
            exit;
        }
    }

    // Update the username
    $updateQuery = "UPDATE user SET Username = ? WHERE username = ?";
    $updateStmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, 'ss', $newUsername, $usernameToUpdate);
    $result = mysqli_stmt_execute($updateStmt);
    mysqli_stmt_close($updateStmt);

    if ($result) {
        $_SESSION['user'] = $newUsername; // Update the session variable
        $response = array("status" => 200, "message" => "User information updated successfully");
    } else {
        $response = array("status" => 500, "message" => "Failed to update user information: " . mysqli_error($conn));
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}


if (isset($_POST['delete_account'])) {

    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);

    $deleteQuery = "DELETE FROM `user` WHERE user_id = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, 'i', $user_id);
    $query_run = mysqli_stmt_execute($deleteStmt);
    mysqli_stmt_close($deleteStmt);

    if ($query_run) {
        $response = [
            'status' => 200,
            'message' => 'Account Deleted Successfully'
        ];
        echo json_encode($response);
        return;
    } else {
        $error_message = 'Error: ' . mysqli_error($conn);
        error_log($error_message);
        $response = [
            'status' => 500,
            'message' => 'Failed to delete account. Please try again later.'
        ];
        echo json_encode($response);
        return;
    }
}


if(isset($_POST['Add_Orchid']))
{
    $common_names = mysqli_real_escape_string($conn, $_POST['common_names']);
    $specific_names = mysqli_real_escape_string($conn, $_POST['specific_names']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $watering = mysqli_real_escape_string($conn, $_POST['watering']);
    $fertilize = mysqli_real_escape_string($conn, $_POST['fertilize']);
    $lighting = mysqli_real_escape_string($conn, $_POST['lighting']);
    $soil_mixture = mysqli_real_escape_string($conn, $_POST['soil_mixture']);
    $difficulty = mysqli_real_escape_string($conn, $_POST['difficulty']);
    $suggested_location = mysqli_real_escape_string($conn, $_POST['suggested_location']);

    $orchid_image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Process the file and convert it to BLOB if needed
        $file_tmp = $_FILES['image']['tmp_name'];
        $orchid_image = file_get_contents($file_tmp);
    }

    $insertQuery = "INSERT INTO `orchid-name` (common_names, specific_names, description, image) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sssb", $common_names, $specific_names, $description, $orchid_image); // Corrected variable name

    if ($stmt->execute()) {
        $id_orchid = $stmt->insert_id;

        // Insert data into orchid_care_details table
        $queryOrchidCareDetails = "INSERT INTO `orchid_care_details` (id_orchid, specific_names, watering, fertilize, lighting, soil_mixture, difficulty, suggested_location ) 
        VALUES (?,?,?,?,?,?,?,?)";

        $stmtCareDetails = $conn->prepare($queryOrchidCareDetails);
        $stmtCareDetails->bind_param("isssssss", $id_orchid, $specific_names, $watering, $fertilize, $lighting, $soil_mixture, $difficulty, $suggested_location);

        if ($stmtCareDetails->execute()) {
            $response = array("status" => 200, "message" => "Orchid added successfully");
        } else {
            $response = array("status" => 500, "message" => "Failed to add orchid care details");
        }

        $stmtCareDetails->close();
    } else {
        $response = array("status" => 500, "message" => "Failed to add orchid");
    }
 
    // Close your database connection if needed
    $stmt->close();
    $conn->close();

    // Set content type to JSON and echo the response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}



if (isset($_POST['update_Orchid'])) {
    $specific_names = mysqli_real_escape_string($conn, $_POST['specific_names']);
    $common_names = mysqli_real_escape_string($conn, $_POST['common_names']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Check if a new image is being uploaded
    $hasImage = $_POST['has_image'];

    if (!empty($_FILES['new_image']['tmp_name'])) {
        // Update the image data
        $newImage = file_get_contents($_FILES['new_image']['tmp_name']);

        if ($newImage === false) {
            $res = [
                'status' => 500,
                'message' => 'Failed to read the uploaded image file'
            ];
            echo json_encode($res);
            return;
        }

        $newImage = mysqli_real_escape_string($conn, $newImage);
        $updateImageQuery = "UPDATE `orchid-name` SET image='$newImage' WHERE specific_names='$specific_names'";
        $result = mysqli_query($conn, $updateImageQuery);

        if (!$result) {
            $res = [
                'status' => 500,
                'message' => 'Image upload failed: ' . mysqli_error($conn)
            ];
            echo json_encode($res);
            return;
        }
    } elseif (!$hasImage) {
        // If no new image and no existing image, set image data to NULL
        $updateImageQuery = "UPDATE `orchid-name` SET image=NULL WHERE specific_names='$specific_names'";
        mysqli_query($conn, $updateImageQuery);
    }

    if ($common_names == NULL || $specific_names == NULL || $description == NULL) {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE  `orchid-name` SET common_names='$common_names', description='$description' 
                WHERE specific_names='$specific_names'";
    $query_run = mysqli_query($conn, $query);



    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Orchid Updated Successfully'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Orchid Not Updated'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_GET['id_orchid']))
{
    $id_orchid = mysqli_real_escape_string($conn, $_GET['id_orchid']);

    $query = "SELECT * FROM  `orchid-name` WHERE id_orchid='$id_orchid'";
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) == 1)
    {
        $orchid = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'Orchid Fetch Successfully by id',
            'data' => $orchid
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 404,
            'message' => 'Orchid Id Not Found'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_GET['specific_names'])) {
    $specific_names = mysqli_real_escape_string($conn, $_GET['specific_names']);

    $query = "SELECT o.*, c.* 
              FROM `orchid-name` AS o
              JOIN `orchid_care_details` AS c ON o.specific_names = c.specific_names
              WHERE o.specific_names='$specific_names'";
              
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        if (mysqli_num_rows($query_run) == 1) {
            $orchid = mysqli_fetch_assoc($query_run);

            // Convert blob data to base64 for images
            $orchid['image'] = base64_encode($orchid['image']);

            $res = [
                'status' => 200,
                'message' => 'Orchid Fetch Successfully by specific_names',
                'data' => $orchid
            ];
            echo json_encode($res);
            return;
        } else {
            $res = [
                'status' => 404,
                'message' => 'Orchid Not Found'
            ];
            echo json_encode($res);
            return;
        }
    } else {
        $res = [
            'status' => 500,
            'message' => 'Database query failed'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['delete_Orchid'])) {
    $specific_names = mysqli_real_escape_string($conn, $_POST['specific_names']); // Change to specific_names

    $query = "DELETE FROM `orchid-name` WHERE specific_names='$specific_names'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Orchid Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Error: ' . mysqli_error($conn)
        ];
        echo json_encode($res);
        return;
    }
}


if (isset($_POST['update_OrchidCare'])) {
   

    $specific_names = mysqli_real_escape_string($conn, $_POST['specific_names']);
    $watering = mysqli_real_escape_string($conn, $_POST['watering']);
    $fertilize = mysqli_real_escape_string($conn, $_POST['fertilize']);
    $lighting = mysqli_real_escape_string($conn, $_POST['lighting']);
    $soil_mixture = mysqli_real_escape_string($conn, $_POST['soil_mixture']);
    $difficulty = mysqli_real_escape_string($conn, $_POST['difficulty']);
    $suggested_location = mysqli_real_escape_string($conn, $_POST['suggested_location']);

    if (($specific_names)== NULL || ($watering)== NULL || ($fertilize)== NULL || ($lighting)== NULL || ($soil_mixture)== NULL || ($difficulty) == NULL||($suggested_location)== NULL) {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $query = "UPDATE `orchid_care_details` SET
                watering='$watering', fertilize='$fertilize', lighting='$lighting', soil_mixture='$soil_mixture', 
                difficulty='$difficulty', suggested_location='$suggested_location'
                WHERE specific_names='$specific_names'";
    
   
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Orchid Care Updated Successfully'
        ];
        echo json_encode($res);
    } else {
        $res = [
            'status' => 500,
            'message' => 'Orchid Care Not Updated: ' . mysqli_stmt_error($stmt)
        ];
        echo json_encode($res);
    }

    
    return;
}


if (isset($_POST['delete_Orchid_care'])) {

    $specific_names = mysqli_real_escape_string($conn, $_POST['specific_names']); // Change to specific_names

    $query = "DELETE FROM `orchid_care_details` WHERE specific_names='$specific_names'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Orchid Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Error: ' . mysqli_error($conn)
        ];
        echo json_encode($res);
        return;
    }
}





if (isset($_POST['Add_Topic'])) {
    // Assuming you have a user session
    $user_id = $currentUserId;
    $topic_title = $_POST['topicTitle'];
    $topic_content = $_POST['topicContent'];

    $topic_picture = '';

    if (isset($_FILES['topicPicture']) && $_FILES['topicPicture']['error'] == UPLOAD_ERR_OK) {
        // Process the file and convert it to BLOB if needed
        $file_tmp = $_FILES['topicPicture']['tmp_name'];
        $topic_picture = file_get_contents($file_tmp);
    }

    // Insert into the database
    $insertQuery = "INSERT INTO discussion_topic (user_id, topic_title, content, content_img, timestamp) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("isss", $user_id, $topic_title, $topic_content, $topic_picture);

    if ($stmt->execute()) {
        $topic_id = $stmt->insert_id; // Get the last inserted topic_id

        // Handle tags
        if (isset($_POST['topicTags']) && is_array($_POST['topicTags'])) {
            $tags = $_POST['topicTags'];
            foreach ($tags as $tag_name) {
                // Check if the tag already exists in the tags table
                $checkTagQuery = "SELECT tag_id FROM tags WHERE tag_name = ?";
                $checkTagStmt = $conn->prepare($checkTagQuery);
                $checkTagStmt->bind_param("s", $tag_name);
                $checkTagStmt->execute();
                $checkTagResult = $checkTagStmt->get_result();

                if ($checkTagResult->num_rows > 0) {
                    // Tag already exists, get the tag_id
                    $tag_id = $checkTagResult->fetch_assoc()['tag_id'];
                } else {
                    // Tag doesn't exist, insert into tags table
                    $insertTagQuery = "INSERT INTO tags (tag_name) VALUES (?)";
                    $insertTagStmt = $conn->prepare($insertTagQuery);
                    $insertTagStmt->bind_param("s", $tag_name);
                    $insertTagStmt->execute();
                    $tag_id = $insertTagStmt->insert_id;
                }

                // Insert into discussion_tag table
                $insertDiscussionTagQuery = "INSERT INTO discussion_tag (topic_id, tag_id) VALUES (?, ?)";
                $insertDiscussionTagStmt = $conn->prepare($insertDiscussionTagQuery);
                $insertDiscussionTagStmt->bind_param("ii", $topic_id, $tag_id);
                $insertDiscussionTagStmt->execute();
            }
        }

        $response = array("status" => 200, "message" => "Topic created successfully");
    } else {
        $response = array("status" => 500, "message" => "Failed to create topic");
    }

    // Close your database connection if needed
    $stmt->close();
    $conn->close();

    // Set content type to JSON and echo the response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}



if (isset($_POST['commentContent'])) {
    // Assuming you have a user session
    $user_id = $currentUserId;
    $topic_id = $_POST['topic_id'];  // Assuming you have a way to get the topic_id, adjust accordingly
    $comment_content = $_POST['commentContent'];

    // Insert into the database
    $insertQuery = "INSERT INTO comment_topic (user_id, topic_id, comment_content, timestamp) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iis", $user_id, $topic_id, $comment_content);

    if ($stmt->execute()) {
        $response = array("status" => 200, "message" => "Comment added successfully");
    } else {
        $response = array("status" => 500, "message" => "Failed to add comment");
    }

    // Set content type to JSON and echo the response
    header('Content-Type: application/json');
    echo json_encode($response);

    // Close your database connection if needed
    $stmt->close();
    $conn->close();
}


if (isset($_POST['deleteDiscussion'])) {
    $topicId = $_POST['topic_id'];

    
    mysqli_begin_transaction($conn);

    try {
        // Delete from discussion_topic
        $deleteTopicQuery = "DELETE FROM discussion_topic WHERE topic_id = ?";
        $stmtDeleteTopic = $conn->prepare($deleteTopicQuery);
        $stmtDeleteTopic->bind_param("i", $topicId);

        if (!$stmtDeleteTopic->execute()) {
            throw new Exception("Failed to delete discussion topic");
        }

        // Delete from discussion_tag
        $deleteTagQuery = "DELETE FROM discussion_tag WHERE topic_id = ?";
        $stmtDeleteTag = $conn->prepare($deleteTagQuery);
        $stmtDeleteTag->bind_param("i", $topicId);

        if (!$stmtDeleteTag->execute()) {
            throw new Exception("Failed to delete discussion tags");
        }

        // Delete from comment_topic
        $deleteCommentQuery = "DELETE FROM comment_topic WHERE topic_id = ?";
        $stmtDeleteComment = $conn->prepare($deleteCommentQuery);
        $stmtDeleteComment->bind_param("i", $topicId);

        if (!$stmtDeleteComment->execute()) {
            throw new Exception("Failed to delete comments");
        }

        // Commit the transaction
        mysqli_commit($conn);

        $response = array("status" => 200, "message" => "Discussion deleted successfully");
    } catch (Exception $e) {
        // Rollback the transaction on failure
        mysqli_rollback($conn);

        $response = array("status" => 500, "message" => "Failed to delete discussion: " . $e->getMessage());
    }

    // Close the prepared statements
    $stmtDeleteTopic->close();
    $stmtDeleteTag->close();
    $stmtDeleteComment->close();

    // Set content type to JSON and echo the response
    header('Content-Type: application/json');
    echo json_encode($response);

    // Close your database connection if needed
    $conn->close();
    exit; 
}



if (isset($_POST['AddMyPlant'])) {
    // Extract form data
    $userId = $currentUserId;
    $myPlantName = $_POST['myplant_name'];
    $myPlantSpecies = $_POST['myplant_species'];
    $myPlantNote = $_POST['myplant_note'];
    $wateringFrequency = $_POST['wateringFrequency'];
    $fertilizingFrequency = $_POST['fertilizingFrequency'];

    $water_StartDate = date('Y-m-d');
    $fert_StartDate = date('Y-m-d');

    // Handle plant image upload
    $plantImage = '';

    // Check for file upload errors
    if (isset($_FILES['myplant_img']) && $_FILES['myplant_img']['error'] == UPLOAD_ERR_OK) {
        // Process the file and convert it to BLOB if needed
        $file_tmp = $_FILES['myplant_img']['tmp_name'];
        $plantImage = file_get_contents($file_tmp);
    }

    try {
        // Insert data into 'myplants' table with the current date
        $queryMyPlants = "INSERT INTO myplants (user_id, myplant_name, myplant_species, myplant_img, time, myplant_note)
                        VALUES (?, ?, ?, ?, CURDATE(), ?)";
        $stmtMyPlants = $conn->prepare($queryMyPlants);
        $stmtMyPlants->bind_param("issss", $userId, $myPlantName, $myPlantSpecies, $plantImage, $myPlantNote);
        $stmtMyPlants->execute();
        $stmtMyPlants->close();

        // Get the last inserted ID (myplant_id)
        $myPlantId = mysqli_insert_id($conn);

        // Insert data into 'watering_schedule' table
        $queryWatering = "INSERT INTO watering_schedule (myplant_id, frequency) VALUES (?, ?)";
        $stmtWatering = $conn->prepare($queryWatering);
        $stmtWatering->bind_param("ii", $myPlantId, $wateringFrequency);
        $stmtWatering->execute();
        $stmtWatering->close();

        // Insert data into 'fertilizing_schedule' table
        $queryFertilizing = "INSERT INTO fertilizing_schedule (myplant_id, frequency) VALUES (?, ?)";
        $stmtFertilizing = $conn->prepare($queryFertilizing);
        $stmtFertilizing->bind_param("ii", $myPlantId, $fertilizingFrequency);
        $stmtFertilizing->execute();
        $stmtFertilizing->close();

        // Insert added event
        $inserfirsteventQuery = "INSERT INTO EVENT (user_id, myplant_id, event_type, event_description, event_date) 
                                VALUES (?, ?, 'New add', ' $myPlantName is added ', CURDATE())";
        $stmtAddedEvent = $conn->prepare($inserfirsteventQuery);
        $stmtAddedEvent->bind_param("is", $currentUserId, $myPlantId);
        $stmtAddedEvent->execute();
        $stmtAddedEvent->close();

        for ($i = 0; $i < 20; $i++) {
            // Calculate the date based on the fixed watering frequency
            $wateringDate = date('Y-m-d', strtotime("+" . $wateringFrequency . " days", strtotime($water_StartDate)));
        
            // Insert the watering event into the event table
            $insertWaterEventQuery = "INSERT INTO event (user_id, myplant_id, event_type, event_description, event_date) 
                                      VALUES (?, ?, 'Watering', '$myPlantName Watering day', ?)";
            $stmtWaterEvent = $conn->prepare($insertWaterEventQuery);
            $stmtWaterEvent->bind_param("iss", $currentUserId, $myPlantId, $wateringDate);
            $stmtWaterEvent->execute();
            $stmtWaterEvent->close();

            $water_StartDate = $wateringDate;

            $fertilizingDate = date('Y-m-d', strtotime("+". $fertilizingFrequency ."days", strtotime($fert_StartDate)));

            // Insert the fertilizing event into the event table
            $insertFertEventQuery = "INSERT INTO event (user_id, myplant_id, event_type, event_description, event_date) 
                                        VALUES (?, ?, 'Fertilizing', ' $myPlantName Fertilizing day', ?)";
            $stmtFertEvent = $conn->prepare($insertFertEventQuery);
            $stmtFertEvent->bind_param("iss", $currentUserId, $myPlantId, $fertilizingDate);
            $stmtFertEvent->execute();
            $stmtFertEvent->close();

            // Update the start date for the next fertilizing iteration
            $fert_StartDate = $fertilizingDate;


        }
                
            

        mysqli_commit($conn);

        $response = array('status' => 200, 'message' => 'Data added successfully.');
    } catch (Exception $e) {
        // Rollback the transaction on failure
        mysqli_rollback($conn);

        $response = array('status' => 500, 'message' => 'Error adding data: ' . $e->getMessage());
    }

    // Set content type to JSON and echo the response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}


if (isset($_POST['AddMyNote'])) {
    // Extract form data
    $userId = $currentUserId;
    $myPlantId =  $_POST['myplant_id'];
    $noteTitle =  $_POST['note_title'];
    $noteContent =  $_POST['note_content'];

    // Handle note image upload
    $noteImage = '';

    if (isset($_FILES['note_img']) && $_FILES['note_img']['error'] == UPLOAD_ERR_OK) {
        // Process the file and convert it to BLOB if needed
        $file_tmp = $_FILES['note_img']['tmp_name'];
        $noteImage = file_get_contents($file_tmp);
    }

    $insertQuery = "INSERT INTO myplant_note (myplant_id, note_tittle, note_content, note_img, note_date) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("isss", $myPlantId, $noteTitle, $noteContent, $noteImage);
    
    if ($stmt->execute()) {
        $response = array("status" => 200, "message" => "Note added successfully");
    } else {
        $response = array("status" => 500, "message" => "Failed to add note");
    }

    // Close your database connection if needed
    $stmt->close();
    $conn->close();

    // Set content type to JSON and echo the response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}


if (isset($_POST['EditMyNote'])) {

    // Extract form data
    $noteId = $_POST['note_id'];
    $userId = $currentUserId;
    $noteTitle =  $_POST['note_title'];
    $noteContent =  $_POST['note_content'];
    // Handle note image upload
    $noteImage = '';

    if (isset($_FILES['note_img']) && $_FILES['note_img']['error'] == UPLOAD_ERR_OK) {
        // Process the file and convert it to BLOB if needed
        $file_tmp = $_FILES['note_img']['tmp_name'];
        $noteImage = file_get_contents($file_tmp);
    }

    $updateQuery = "UPDATE myplant_note 
                    SET note_tittle = ?, note_content = ?, note_img = ?
                    WHERE note_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssi", $noteTitle, $noteContent, $noteImage, $noteId);

    
    if ($stmt->execute()) {
        $response = array("status" => 200, "message" => "Note update successfully");
    } else {
        $response = array("status" => 500, "message" => "Failed to update note");
    }

    // Close your database connection if needed
    $stmt->close();
    $conn->close();


   // Set content type to JSON and echo the response
   header('Content-Type: application/json');
   echo json_encode($response);
   exit();
} 

if (isset($_POST['deleteNote'])) {
    $noteId = $_POST['note_id'];

    // Begin a transaction
    mysqli_begin_transaction($conn);

    try {
        // Delete from myplant_note
        $deleteNoteQuery = "DELETE FROM myplant_note WHERE note_id = ?";
        $stmtDeleteNote = $conn->prepare($deleteNoteQuery);
        $stmtDeleteNote->bind_param("i", $noteId);

        if (!$stmtDeleteNote->execute()) {
            throw new Exception("Failed to delete note");
        }

        // Commit the transaction
        mysqli_commit($conn);

        $response = array("status" => 200, "message" => "Note deleted successfully");
    } catch (Exception $e) {
        // Rollback the transaction on failure
        mysqli_rollback($conn);

        $response = array("status" => 500, "message" => "Failed to delete note: " . $e->getMessage());
    }

    // Close the prepared statement
    $stmtDeleteNote->close();

    // Set content type to JSON and echo the response
    header('Content-Type: application/json');
    echo json_encode($response);

    // Close your database connection if needed
    $conn->close();
    exit;
}

if (isset($_POST['deleteOrchid'])) {
    $OrchidId = $_POST['Orchid_id'];

    // Begin a transaction
    mysqli_begin_transaction($conn);

    try {
        // Delete from myplant_Orchid
        $deleteOrchidQuery = "DELETE FROM myplants WHERE myplant_id = ?";
        $stmtDeleteOrchid = $conn->prepare($deleteOrchidQuery);
        $stmtDeleteOrchid->bind_param("i", $OrchidId);

        if (!$stmtDeleteOrchid->execute()) {
            throw new Exception("Failed to delete Orchid");
        }

        // Commit the transaction
        mysqli_commit($conn);

        $response = array("status" => 200, "message" => "Orchid deleted successfully");
    } catch (Exception $e) {
        // Rollback the transaction on failure
        mysqli_rollback($conn);

        $response = array("status" => 500, "message" => "Failed to delete Orchid: " . $e->getMessage());
    }

    // Close the prepared statement
    $stmtDeleteOrchid->close();

    // Set content type to JSON and echo the response
    header('Content-Type: application/json');
    echo json_encode($response);

    // Close your database connection if needed
    $conn->close();
    exit;
}

if (isset($_POST['EditMyOrchid'])) {
    // Extract form data
    $userId = $currentUserId;
    $OrchidId = $_POST['myplant_id'];
    $myPlantName = $_POST['myplant_name'];
    $OrchidSpecies = $_POST['myplant_species'];
    $OrchidNote = $_POST['myplant_note'];
    $wateringFrequency = $_POST['wateringFrequency'];
    $fertilizingFrequency = $_POST['fertilizingFrequency'];
    $todaydate = date('Y-m-d');

    // Handle Orchid image upload
    $OrchidImage = '';

    if (isset($_FILES['myplant_img']) && $_FILES['myplant_img']['error'] == UPLOAD_ERR_OK) {
        // Process the file and convert it to BLOB if needed
        $file_tmp = $_FILES['myplant_img']['tmp_name'];
        $OrchidImage = file_get_contents($file_tmp);
    } else {
        // If no new image is uploaded, retain the existing image
        $getExistingImageQuery = "SELECT myplant_img FROM myplants WHERE myplant_id = ?";
        $stmtExistingImage = $conn->prepare($getExistingImageQuery);
        $stmtExistingImage->bind_param("i", $OrchidId);
        $stmtExistingImage->execute();
        $stmtExistingImage->bind_result($existingImage);
        $stmtExistingImage->fetch();
        $OrchidImage = $existingImage;
        $stmtExistingImage->close();
    }

    // Prepare and execute the update query
    $updateQuery = "UPDATE myplants 
                    SET myplant_name = ?, myplant_species = ?, myplant_note = ?, myplant_img = ?
                    WHERE myplant_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssi", $myPlantName, $OrchidSpecies, $OrchidNote, $OrchidImage, $OrchidId);

    if ($stmt->execute()) {

        // Retrieve existing watering frequency
        $selectWateringQuery = "SELECT frequency FROM watering_schedule WHERE myplant_id = ?";
        $stmtSelectWatering = $conn->prepare($selectWateringQuery);
        $stmtSelectWatering->bind_param("i", $OrchidId);
        $stmtSelectWatering->execute();
        $stmtSelectWatering->bind_result($existingWateringFrequency);
        $stmtSelectWatering->fetch();
        $stmtSelectWatering->close();

        // Retrieve existing fertilizing frequency
        $selectFertilizingQuery = "SELECT frequency FROM fertilizing_schedule WHERE myplant_id = ?";
        $stmtSelectFertilizing = $conn->prepare($selectFertilizingQuery);
        $stmtSelectFertilizing->bind_param("i", $OrchidId);
        $stmtSelectFertilizing->execute();
        $stmtSelectFertilizing->bind_result($existingFertilizingFrequency);
        $stmtSelectFertilizing->fetch();
        $stmtSelectFertilizing->close();

        // Update watering frequency if it has changed
        if ($existingWateringFrequency != $wateringFrequency) {
            //delete how many over today 
            $water_eventdelete_query = "DELETE FROM event 
                                        WHERE myplant_id = ? 
                                        AND event_type = 'Watering' 
                                        AND event_date > CURDATE() ";
                        
            $stmtwater_eventdelete = $conn->prepare($water_eventdelete_query);
            $stmtwater_eventdelete->bind_param("i", $OrchidId);
            $stmtwater_eventdelete->execute();
            $stmtwater_eventdelete->close();


            //update schedule
            $updateWateringQuery = "UPDATE watering_schedule SET frequency = ? WHERE myplant_id = ?";
            $stmtWatering = $conn->prepare($updateWateringQuery);
            $stmtWatering->bind_param("ii", $wateringFrequency, $OrchidId);
            $stmtWatering->execute();
            $stmtWatering->close();

            //insert today
            $water_StartDate = date('Y-m-d');
            // Insert the watering event into the event table
            $insertWaterEventQuery = "INSERT INTO event (user_id, myplant_id, event_type, event_description, event_date) 
                                    VALUES (?, ?, 'Watering', '$myPlantName Watering day', ?)";
            $stmtWaterEvent = $conn->prepare($insertWaterEventQuery);
            $stmtWaterEvent->bind_param("iss", $userId, $myPlantId, $water_StartDate );
            $stmtWaterEvent->execute();
            $stmtWaterEvent->close();

            for ($i = 0; $i < 20 ; $i++) {
                // Calculate the date based on the fixed watering frequency
                $wateringDate = date('Y-m-d', strtotime("+" . $wateringFrequency . " days", strtotime($water_StartDate)));
            
                $updateWaterEventQuery = "INSERT INTO event (user_id, myplant_id, event_type, event_description, event_date) 
                                         VALUES (?, ?, 'Watering', '$myPlantName Watering day', ?)";
                            
                $stmtUpdateWaterEvent = $conn->prepare($updateWaterEventQuery);
                $stmtUpdateWaterEvent->bind_param("iss", $userId, $myPlantId, $water_StartDate );
                $stmtUpdateWaterEvent->execute();
                $stmtUpdateWaterEvent->close();

                $water_StartDate = $wateringDate;
            }
        }

        // Update fertilizing frequency if it has changed
        if ($existingFertilizingFrequency != $fertilizingFrequency) {
            //count how many over today 
            $fert_eventdelete_query = "DELETE FROM event 
                                WHERE myplant_id = ? 
                                AND event_type = 'Fertilizing' 
                                AND event_date > CURDATE() ";
                
            $stmtfert_eventdelete = $conn->prepare($fert_eventdelete_query);
            $stmtfert_eventdelete->bind_param("i", OrchidId);
            $stmtfert_eventdelete->execute();
            $stmtfert_eventdelete->close();

            //update schedule
            $updateFertilizingQuery = "UPDATE fertilizing_schedule SET frequency = ? WHERE myplant_id = ?";
            $stmtFertilizing = $conn->prepare($updateFertilizingQuery);
            $stmtFertilizing->bind_param("ii", $fertilizingFrequency, $OrchidId);
            $stmtFertilizing->execute();
            $stmtFertilizing->close();

            //insert today
            $fert_StartDate = date('Y-m-d');
            // Insert the fert event into the event table
            $insertfertEventQuery = "INSERT INTO event (user_id, myplant_id, event_type, event_description, event_date) 
            VALUES (?, ?, 'Fertilizing', '$myPlantName Fertilizing day', ?)";
            $stmtfertEvent = $conn->prepare($insertfertEventQuery);
            $stmtfertEvent->bind_param("iss", $userId, $myPlantId, $fert_StartDate );
            $stmtfertEvent->execute();
            $stmtfertEvent->close();

            for ($i = 0; $i < $fert_eventcount ; $i++) {
                // Calculate the date based on the fixedfert frequency
                $fertilizingDate = date('Y-m-d', strtotime("+" . $fertilizingFrequency . " days", strtotime($fert_StartDate)));
            
                $updatefertEventQuery = "INSERT INTO event (user_id, myplant_id, event_type, event_description, event_date) 
                                    VALUES (?, ?, 'Fertilizing', '$myPlantName Fertilizing day', ?)";
                            
                $stmtUpdatefertEvent = $conn->prepare($updatefertEventQuery);
                $stmtUpdatefertEvent->bind_param("iss", $myPlantId, $fert_StartDate );
                $stmtUpdatefertEvent->execute();
                $stmtUpdatefertEvent->close();

                $fert_StartDate = $fertilizingDate;
            }

        }

        $response = array("status" => 200, "message" => "Orchid updated successfully");
    } else {
        $response = array("status" => 500, "message" => "Failed to update Orchid");
    }

    // Close your database connection if needed
    $stmt->close();
    $conn->close();

    // Set content type to JSON and echo the response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}



?>
