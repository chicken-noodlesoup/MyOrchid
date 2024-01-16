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
        
          <!-- login php -->
            <?php
              if (isset($_POST["login"])) {
                $Username = $_POST["Username"];
                $password = $_POST["password"];
            
                require_once "config/db_conn.php";
                
                $sql = "SELECT * FROM user WHERE Username = '$Username'";
                $result = mysqli_query($conn, $sql);
                $user = mysqli_fetch_assoc($result);
            
                if ($user) {
                    if (password_verify($password, $user["password"])) {
                        session_start();
            
                        // Store user information in the session
                        $_SESSION["user"] = $user["Username"];
                        $_SESSION["role"] = $user["role"];
            
                        // Redirect based on user role
                        if ($user["role"] === "admin") {
                            header("Location: admin.php");
                        } elseif ($user["role"] === "user") {
                            header("Location: user_dashboard.php");
                        } else {
                            // Handle other roles or redirect to a default page
                            header("Location: default_dashboard.php");
                        }
                        exit();
                    } else {
                        echo "<div class='alert alert-danger'>Password does not match</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Username does not exist</div>";
                }
            }
            ?>
          <!-- login php -->




          <!-- Login -->
          <div class="loginregisterbg ">

            <div class="card "style= "background-color:#E8DACD;">
              <div class="card-body">

                <h4 class="mb-4">Welcome to MyOrchid</h4>
                <p class="mb-4">Please sign-in to your account and start the adventure</p>
                <form id="formAuthentication" class="mb-3" action="" method="POST">
                  <div class="mb-3">
                    <label for="Username" class="form-label">Username</label>
                    <input
                      type="text"
                      class="form-control"
                      id="Username"
                      name="Username"
                      placeholder="Enter your Username"
                      autofocus
                    />
                  </div>
                  <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                      <label class="form-label" for="password">Password</label>
                      <a href="auth-forgot-password-basic.html">
                        <small>Forgot Password?</small>
                      </a>
                    </div>
                    <div class="input-group input-group-merge">
                      <input
                        type="password"
                        id="password"
                        class="form-control"
                        name="password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="password"
                      />
                      
                    </div>
                  </div>
                  <div class="mb-3">
                    <button class="btn btn-primary d-grid w-100 "value="Login" name="login" type="submit">Sign in</button>
                  </div>
                </form>

                <p class="text-center">
                  <span>New on our platform?</span>
                  <a href="page_register.php">
                    <span>Create an account</span>
                  </a>
                </p>
              </div>
            </div>
          </div>
          <!-- /Login -->
        </div>
      </div>
    </div>
    
    </div>

    <!-- / Content -->


    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/isotope/isotope.pkgd.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>