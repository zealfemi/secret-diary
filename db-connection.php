<?php

    $db = mysqli_connect("HOSTNAME", "USERNAME", "PASSWORD", "DATABASE TABLE");

    if (mysqli_connect_error()) {
                        
        $error = "Could not connect to database, try again!";
        die();

    }

?>