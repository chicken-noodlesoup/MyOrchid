<?php

require 'config/db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topic_id = isset($_POST['topic_id']) ? $_POST['topic_id'] : '';
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $liked = isset($_POST['liked']) ? $_POST['liked'] : '';

    // Ensure $liked is a boolean
    $liked = filter_var($liked, FILTER_VALIDATE_BOOLEAN);

    if ($liked ) {
        // If the topic is liked, insert a new like record using prepared statement
        $updateQuery = "INSERT INTO like_topic (topic_id, user_id) VALUES (?, ?)";
    } else {
        // If the topic is unliked, delete the existing like record using prepared statement
        $updateQuery = "DELETE FROM like_topic WHERE topic_id = ? AND user_id = ?";
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($updateQuery);

    if ($stmt) {
        $stmt->bind_param('ii', $topic_id, $user_id);
        $stmt->execute();

        if ($liked) {
            // Insertion case
            echo json_encode(['success' => true]);
        } else {
            // Deletion case
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Record not found']);
            }
        }

        $stmt->close();
    } else {
        // Update failed
        echo json_encode(['success ' => false]);
    }
} else {
    // Invalid request method
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
