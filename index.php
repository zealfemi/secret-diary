<?php

    session_start();

    $error = "";

    if (array_key_exists("logout", $_GET)) {
        unset($_SESSION);

        setcookie("id", "", time() - 60 * 60);
        $_COOKIE['id'] = ""; 

    } else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
        header("Location: diary.php");
    }

    if ($_POST) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $stay = $_POST['stay'];
        $submit = $_POST['submit'];
        
        if ($email == "") {

            $error .= "Email address is required. <br>";

        }
        if ($password == "") {

            $error .= "Password is required.";

        }

        if($error == "") {

            include("db-connection.php");

            if ($submit == "signUp") {
                $getUser = "SELECT `email` FROM `users` WHERE `email` LIKE '".mysqli_real_escape_string($db, $email)."' LIMIT 1";

                $user = mysqli_query($db, $getUser);
                if (mysqli_num_rows($user) > 0) {

                    $error = "Email has been taken";

                } else {
                    $createUser = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($db, $email)."', '".mysqli_real_escape_string($db, $password)."')";

                    if (!(mysqli_query($db, $createUser))) {
                        $error = "Could not sign up this time, try again!";

                    } else {
                        $updatedPassword = md5(md5(mysqli_insert_id($db)).$password);

                        $query = "UPDATE `users` SET password = '".$updatedPassword."' WHERE id = '".mysqli_insert_id($db)."' LIMIT 1";

                        mysqli_query($db, $query);
                        
                        $_SESSION['id'] = mysqli_insert_id($db);

                        if ($stay == 'on') {
                            setcookie("id", mysqli_insert_id($db), time() + 60 * 60 * 24);
                        }

                        header("Location: diary.php");
                    }

                }

            } else if ($submit == "signIn") {

                $getUser = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($db, $email)."'";
                
                if (!(mysqli_query($db, $getUser))) {
                    $error = "Could not load database this time, try again";
                } else {
                    $query = mysqli_query($db, $getUser);

                    $user = mysqli_fetch_array($query);

                    if (array_key_exists("id", $user)) {
                        
                        $hashedPassword = md5(md5($user['id']).$password);
                        if ($hashedPassword == $user["password"]) {
                            $_SESSION["id"] = $user["id"];

                            if ($stay == "on") {
                                setcookie("id", $user["id"], time() + 60 *60 *24);
                            }

                            header("Location: diary.php");
                        } else {
                            $error = "Password is incorrect!";
                        }

                    } else {
                        $error = "Email is not registered.";
                    }
                }
            }

        } else {
            $error = "<p>There were error(s) in your form:</p>". $error;
        }

    }

?>

<?php include("header.php"); ?>

<div id="homeContainer" class="container m-o py-5 text-danger text-center">

  <div class="fw-medium">
    <h1 class="fw-bolder font-monospace">SECRET DIARY</h1>
    <p>Store your thoughts permanently and securely.</p>
  </div>

  <div id="info">
    <?php
        if($error) {
            echo "<div class='alert alert-danger text-danger fw-medium small'>".$error."</div>";
        }
    ?>
  </div>

  <!-- SIGN IN FORM -->

  <form method="POST" id="sign-in" class="hidden">
    <input type="hidden" name="submit" value="signIn">

    <div id="note" class="fw-medium my-3">Sign in using your email and password.</div>

    <div class="mb-3">
      <input type="email" class="form-control border-danger bg-dark text-white" id="email" name="email"
        aria-describedby="emailHelp" placeholder="Email Address">
    </div>

    <div class="mb-3">
      <input type="password" class="form-control border-danger bg-dark text-white" id="password" name="password"
        placeholder="Password">
    </div>

    <div class="navbar mb-3">
      <div class="flex justify-items-center">
        <input type="checkbox" class="form-check-input bg-dark text-white border-danger" id="stay" name="stay">
        <label class="form-check-label fw-medium" for="stay">Stay signed in?</label>
      </div>

      <input type="submit" class="btn btn-danger mb-2" value="Sign In" />
    </div>

    <div class="hide-form fw-medium d-inline text-center">No account? Sign up here</div>

  </form>

  <!-- SIGN UP FORM -->

  <form method="POST" id="sign-up">
    <input type="hidden" name="submit" value="signUp">

    <div id="note" class="fw-medium mb-3">Interested? Sign Up!</div>

    <div class="mb-3">
      <input type="email" class="form-control bg-dark text-white border-danger" id="email" name="email"
        placeholder="Email address">
    </div>

    <div class="mb-3">
      <input type="password" class="form-control bg-dark text-white border-danger" id="password" name="password"
        placeholder="Password">
    </div>

    <div class="navbar mb-3">
      <div class="flex justify-items-center">
        <input type="checkbox" class="form-check-input bg-dark text-white border-danger" id="stay" name="stay">
        <label class="form-check-label fw-medium" for="stay">Stay signed in?</label>
      </div>

      <input type="submit" class="btn btn-danger mb-2" value="Sign Up" />
    </div>

    <div class="hide-form fw-medium d-inline text-center">Have an account? Sign in here</div>
  </form>
</div>
<?php include("footer.php"); ?>