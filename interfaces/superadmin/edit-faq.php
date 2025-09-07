<?php
require '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['question'], $_POST['answer'])) {
    $id = intval($_POST['id']);
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);

    if (!empty($question) && !empty($answer)) {
        $sql = "UPDATE faqs SET question = ?, answer = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssi", $question, $answer, $id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error: " . $connection->error;
        }
    } else {
        echo "Question and answer cannot be empty.";
    }
} else {
    echo "Invalid request.";
}
