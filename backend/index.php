<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json');
    include("./pdo.php");

    $query = filter_input(INPUT_GET, "book", FILTER_SANITIZE_SPECIAL_CHARS);

    $sql = "INSERT INTO search_history(search) VALUES(:query)";

    $statements = $pdo->prepare($sql);
    $statements->execute(['query' => $query]);
?>