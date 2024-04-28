<?php
    session_start();
    if (!isset($_SESSION["admin"])) {
        header("Location: ../authentication/adminLogin.php");
    }  else {
        $admin = $_SESSION["admin"];
        $admin_fullname = $admin["admin_fullname"];
        $admin_email = $admin["admin_email"];
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Add Admin</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  

  <!-- =======================================================
  * Template Name: Arsha
  * Template URL: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

</head>

<?php
    require_once("../db-connector.php");

    // Fetch data from the Orgs table
    $query = "SELECT * FROM events";
    $stmt = $pdo_obj->query($query);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top header-inner-pages">
    <div class="container d-flex align-items-center">

    <a href="adminHome.php" class="logo me-auto"><img src="../assets/img/logo.png" alt="" class="img-fluid"></a>
      <nav id="navbar" class="navbar">
        <ul>
        <li><a class="nav-link scrollto" href="adminHome.php">Content Dashboard</a></li>
          <li><a class="nav-link scrollto active" href="addAdmin.php">Add Admin</a></li>
          <li><a class="nav-link scrollto" href="../authentication/logout.php">Log Out</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
      <div class="container">
        <h2 class="header-text-2">Add Admin</h2>
        <?php
            echo "Welcome <b>".$admin_fullname."</b>";
        ?>
      </div>
    </section><!-- End Breadcrumbs -->

    <section class="inner-page">
      <div class="container">
      <div class="line"></div>

    <!-- Add Admin Form -->
    <div class="container table-container">
    <form action="addAdmin.php" method="POST" enctype="multipart/form-data">
            <?php
                //Run once form is submitted
                if (isset($_POST["register"])) {
                    //initiate input variables
                    $email = $_POST["admin_email"];
                    $fullname = $_POST["admin_fullname"];
                    $password = $_POST["admin_password"];
                    $password_confirmation = $_POST["password_rep"];
                    
                    //store errors
                    $errs = array();
                    
                    //secure the password
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);

                    //check if complete inputs
                    if(empty($fullname) OR empty($email) OR empty($password)) {
                        array_push( $errs,"All fields are required");
                    }

                    //check if email format valid
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        array_push( $errs,"Enter a valid email");
                    }

                    //check if passwords match
                    if($password!=$password_confirmation){
                        array_push( $errs,"Password does not match");
                    }

                    require_once("../db-connector.php");    //connector file
                    
                    $email_query = "SELECT * FROM admins WHERE admin_email = '$email'";
                    $username_query = "SELECT * FROM admins WHERE admin_fullname = '$fullname'";
                    $email_data = mysqli_query($connection, $email_query);
                    $username_data = mysqli_query($connection, $username_query);
                    $email_rows = mysqli_num_rows($email_data);
                    $username_rows = mysqli_num_rows($username_data);

                    if ($email_rows>0){
                        array_push($errs, "Email already exists!");
                    }

                    if ($username_rows>0){
                        array_push($errs, "Admin already exists!");
                    }


                    //checks error counter
                    if(count($errs) > 0) {  //errors are detected
                        foreach($errs as $err) {
                            //!!! please provide class name for customizing div !!!
                            echo "<div>$err</div>";
                        }
                    } else {    //input requirements fulfilled, initiate connection
                        //SQL statment for insertion of inputs to DB
                        $sql = "INSERT INTO admins (admin_fullname, admin_email, admin_password) VALUES (?,?,?)"; //Values are abstracted to prevent SQL injection

                        $stmt = mysqli_stmt_init($connection);  //create statement
                        $prepare = mysqli_stmt_prepare($stmt, $sql);    //prepare SQL statement

                        //Save volunteer registration
                        if($prepare) {  //connection successful, complete the parameter details

                            //complete preparation statement
                            mysqli_stmt_bind_param($stmt,"sss", $fullname, $email, $password_hash); //Define input variables here for storing to DB
                            mysqli_stmt_execute($stmt); //execute the statment

                            echo "<div>Registration Successful!</div>"; //success confirmation
                        } else {
                            die("Something went wrong"); //database connection unsuccessful
                        }
                    }
                }
            ?>

            <!--Registration form-->
            <!-- Input for Email -->
            <div class="container-form">
                <div class="item1">
                    <label for="admin_email" class="textlabel input-head">Email</label><br>
                    <input type="text" name="admin_email" id="admin_email" class="form-control" required><br>
                </div>
                
                <!-- Input for Full Name -->
                <div class="item2">
                    <label for="admin_fullname" class="textlabel input-head">Full Name</label><br>
                    <input type="text" name="admin_fullname" id="admin_fullname" class="form-control" required><br>
                </div>

                <!-- Input for Password -->             
                <div class="item3">
                    <label for="admin_password" class="textlabel input-head">Password</label><br>
                    <input type="text" name="admin_password" id="admin_password" class="form-control" required><br>
                </div>

                <!-- Input for Confirm Password -->
                <div class="item4">
                    <label for="password_rep" class="textlabel input-head">Confirm Password</label><br>
                    <input type="text" name="password_rep" id="password_rep" class="form-control" required><br>
                </div>

                                  

        </form>
    </div>
    <div class="line"></div>
    <div class="submitbutton">
        <button type="submit" name="bttn" class="btn btn-success my-2 my-sm-0" style="width: 200px; margin-bottom: 20px;">Submit</button>
    </div>
</section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">

    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-contact">
            <h3>Arsha</h3>
            <p>
              A108 Adam Street <br>
              New York, NY 535022<br>
              United States <br><br>
              <strong>Phone:</strong> +1 5589 55488 55<br>
              <strong>Email:</strong> info@example.com<br>
            </p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">About us</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Services</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of service</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Web Design</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Web Development</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Product Management</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Marketing</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Graphic Design</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Our Social Networks</h4>
            <p>Cras fermentum odio eu feugiat lide par naso tierra videa magna derita valies</p>
            <div class="social-links mt-3">
              <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
              <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
              <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
              <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
              <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="container footer-bottom clearfix">
      <div class="copyright">
        &copy; Copyright <strong><span>Arsha</span></strong>. All Rights Reserved
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
<!-- bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  
</body>

</html>