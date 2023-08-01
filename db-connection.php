<?php

    $db = mysqli_connect("sql211.byethost9.com", "b9_34311591", "vitaminc1", "b9_34311591_users");

    if (mysqli_connect_error()) {
                        
        $error = "Could not connect to database, try again!";
        die();

    }

?>