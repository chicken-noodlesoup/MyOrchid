<?php
include('config/db_conn.php');

$response = ['status' => 'error', 'message' => 'Invalid request method'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);

    $query = "INSERT INTO `message` (name, email, subject, note) VALUES ('$name', '$email', '$subject', '$note')";

    if (mysqli_query($conn, $query)) {
        $response = ['status' => 'success', 'message' => 'Message added successfully'];
    } else {
        $response = ['status' => 'error', 'message' => 'Failed to add message'];
    }
} 

// Set content type to JSON and echo the response
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>
