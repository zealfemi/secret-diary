<?php

    session_start();

    if (array_key_exists("content", $_POST)) {

        $diary = $_POST["content"];
        $id = $_SESSION["id"];

        include("db-connection.php");

        $query = "UPDATE `users` SET `diary` = '".mysqli_real_escape_string($db, $diary)."' WHERE id = ".mysqli_real_escape_string($db, $id)." LIMIT 1";

        if (mysqli_query($db, $query)) {
            echo "<div class='text-success fw-medium'>Saved!</div>";
        } else {
            echo "<div class='text-danger fw-medium'>Not saved, try again!</div>";
        }

    }

?>