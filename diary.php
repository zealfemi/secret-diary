<?php

    session_start();

    $diary = "";
    $user = "";

    if (array_key_exists("id", $_COOKIE) AND $_COOKIE["id"]) {
        $_SESSION["id"] = $_COOKIE["id"];
    }

    if (array_key_exists("id", $_SESSION) AND $_SESSION["id"]) {

        include("db-connection.php");
        $id = $_SESSION["id"];

        $query = "SELECT * FROM `users` WHERE `id` = ".mysqli_real_escape_string($db, $id)." LIMIT 1";

        if (mysqli_query($db, $query)) {
            $result = mysqli_query($db, $query);

            $row = mysqli_fetch_array($result);

            $diary = $row['diary'];
            $user = $row['email'];
            
        }

    } else {
        header("Location: index.php");
        unset($_SESSION);
        setcookie("id", "", time() - 60 * 60 * 365);
        $_COOKIE['id'] = ""; 
    }

    include("header.php");

?>

<nav class="navbar sticky-top bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand font-monospace fw-bold">Secret Diary</a>

    <div id="result" class="small">x</div>

    <div class="d-flex">
      <a href="/index.php?logout=1" class="btn bg-danger d-flex text-white py-1">Logout</a>
    </div>
  </div>
</nav>

<div class="container">
  <div class="alert alert-success text-success p-1 my-1">Welcome back, <?php echo $user; ?>!</div>

  <textarea id="diary" class="mt-2 continer-fluid bg-dark text-white p-2"><?php echo $diary; ?></textarea>
</div>

<?php include("footer.php"); ?>