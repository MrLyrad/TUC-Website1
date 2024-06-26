<?php
    session_start();
    if (isset($_SESSION["admin"])) {
        header("Location: ../admin/adminHome.php");
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin Login</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/adminFavicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet"> -->

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
  <link href="../assets/css/font.css" rel="stylesheet">
  
  <!-- =======================================================
  * Template Name: Arsha
  * Template URL: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <style>
    .container-form {
          grid-template-areas:
            'name'
            'image'
            'location'
            'contact'
            'person'
            'startdate'
            'enddate'
            'timestart'
            'timeend'
            'instructions'
            'description';
          grid-template-columns: 1fr;  /* Single column for all items */
        }
    #footer {
          position: fixed;
          bottom: 0;
          width: 100%;
          padding: 20px 0; /* Adjust padding as needed */
    }
    </style>
</head>



<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top header-inner-pages">
    <div class="container d-flex align-items-center">

    <a href="adminLogin.php" class="logo me-auto"><img src="../assets/img/logo.png" alt="" class="img-fluid"></a>
    </div>
  </header><!-- End Header -->

  <main id="main">

   <!-- ======= Breadcrumbs ======= -->
   <section id="breadcrumbs" class="breadcrumbs">
      <div class="container">
        <h2 class="header-text-2">Admin Login</h2>
      </div>
    </section><!-- End Breadcrumbs -->

  <section class="inner-page">
      <div class="container">
      <div class="line"></div>

      <?php
        if(isset($_POST["login"])){
            $email = $_POST["admin_email"];
            $password = $_POST["admin_password"];

            require_once "../db-connector.php";

            $sql = "SELECT * FROM admins WHERE admin_email = '$email'";
            $result = mysqli_query($connection, $sql);
            $admin = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if(empty($password) OR empty($email)) {
                echo "<div>Please input Login details</div>";
            }
            
            if ($admin) {
                if (password_verify($password, $admin["admin_password"])){
                    session_start();
                    $_SESSION["admin"] = $admin;
                    header("Location: ../admin/adminHome.php");
                    die();
            } else {
                    echo "<div>Password does not match</div><br />";
                }
            } else {
                echo "<div>Email does not match</div>";
            }
        }
    ?>  


      <form action="adminLogin.php" method="post">

      <div class="container-form">

      <div class="item2">
                        <div class="item1">
                            <label for="admin_email" class="textlabel input-head">Email</label><br>
                            <input type="email" id="admin_email" name="admin_email" class="form-control" required>
                        </div>
                        
                        <!-- Input for Event Location -->
                
                        <div class="item3">
                            <label for="admin_password" class="textlabel input-head">Password</label><br>
                            <input type="password" id="admin_password" name="admin_password" class="form-control" required>
                        </div>

                        <!-- Input for Event Contact Number -->
                
                    </div>
                    <div class="line"></div>
                    
                    <div class="submitbutton">
                            <button type="submit" id="submit-button" name="login" value="login" class="btn btn-success my-2 my-sm-0" style="width: 200px; margin-bottom: 20px;">Login</button>
                            <button onclick="window.location.href='../index.php';" class="btn btn-success my-2 my-sm-0" style="width: 300px; margin-bottom: 20px;">Return to Home Page</button></button>
                        </div>
                </form>
        <br />      
    </div>


    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container footer-bottom clearfix">
      <div class="copyright">
        &copy; Copyright <strong><span>Tanglaw University Center</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/ -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/aos/aos.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="../assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

</body>

</html>