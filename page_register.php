<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: user_dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MY ORCHID</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img\logo.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Roboto:wght@500;700;900&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
     <!-- Content -->
     <div style="background: url('img/loginbg.jpg') center center fixed; background-size: cover; height: 100vh; display: flex; justify-content: center; align-items: center;">
    <div class="container-xxl ">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Registerphp -->
          <?php
          if (isset($_POST["submit"])) {
            $Username = $_POST["Username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            $errors = array();
            
            if (empty($Username) OR empty($email) OR empty($password)) {
                array_push($errors, "All fields are required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 characters long");
            }

            require_once "config/db_conn.php";
            $sql = "SELECT * FROM user WHERE Username = '$Username'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);

            if ($rowCount > 0) {
                array_push($errors, "Username already exists!");
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                $role = 'user'; // Default role for new users
                $profilepicpath = 'img/noprofilepic.jpg'; // Adjust the path accordingly
                $profilepic = file_get_contents($profilepicpath);

                if ($profilepic === false) {
                  die('Error loading default profile picture: ' . error_get_last()['message']);
                }
              

                $sql = "INSERT INTO user (Username, email, password, role,profilepic ) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                
                if ($stmtPrepare = mysqli_stmt_prepare($stmt, $sql)) {
                  mysqli_stmt_bind_param($stmt, "ssssb", $Username, $email, $passwordHash, $role, $profilepic);
                  mysqli_stmt_send_long_data($stmt, 4, $profilepic);  // Send the binary data separately
                  mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>You are registered successfully.</div>";
                } else {
                    die("Something went wrong");
                }
            }
        }
        ?>




          <!-- Registerphp -->

          <!-- Register -->
          <div class="card"style= "background-color:#E8DACD;">
            <div class="card-body">

              <h4 class="mb-4">Adventure starts here </h4>
              <p class="mb-4">Please register to start the adventure</p>
              <form id="formAuthentication" class="mb-3" action="page_register.php" method="POST">
                <div class="mb-3">
                  <label for="Username" class="form-label">Username</label>
                  <input
                    type="text"
                    class="form-control"
                    id="Username"
                    name="Username"
                    placeholder="Enter your username"
                    autofocus
                  />
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" />
                </div>

                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="password">Password</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>

                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit" value="Register" name="submit">Sign up</button>
                </div>
              </form>

              <p class="text-center">
                <span>Already have an account</span>
                <a href="page_login.php">
                  <span>Sign in instead</span>
                </a>
              </p>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>
    </div>
    <!-- / Content -->


    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/isotope/isotope.pkgd.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/themes/default.min.css" />
    

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>