<?php
  session_start();
  if(isset($_SESSION["user"])) {
    $user = $_SESSION["user"];
    $volunteer_id = $user["volunteer_id"];
    $email = $user["email"];
    $username = $user["username"];
  } else {
    $user = null;
    $email = null;
    $username = null;
    header("Location: index.php");
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>My Events</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">

  <!-- Google Fonts -->
  <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet"> -->

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/font.css" rel="stylesheet">
  

  <!-- =======================================================
  * Template Name: Arsha
  * Template URL: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

  <script>
    function onFileSelected(event) {
    var selectedFile = event.target.files[0];
    var reader = new FileReader();

    var imgtag = document.getElementById("myimage");
    imgtag.title = selectedFile.name;

    reader.onload = function(event) {
        imgtag.src = event.target.result;
    };

    reader.readAsDataURL(selectedFile);
    document.getElementById('myimage').style.display = ""
    }

    function resetImage() {
    document.getElementById('event_image').value = null;
    document.getElementById('myimage').style.display = "none"; 
    }
    
  </script>
</head>

<?php
    require_once("db-connector.php");

    // Fetch data from the Orgs table
    $query = "SELECT * FROM volunteer_events WHERE volunteer_id = $volunteer_id";
    $stmt = $pdo_obj->query($query);
    $my_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = "SELECT event_id FROM volunteer_events WHERE volunteer_id = $volunteer_id";
    $stmt = $pdo_obj->query($query);

    $event_ids = array();
    while ($row = $stmt->fetch(PDO::FETCH_COLUMN)) {
    $event_ids[] = $row; // Add event_id to the array
    }
?>


<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top header-inner-pages">
    <div class="container d-flex align-items-center">

    <a href="index.php" class="logo me-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>
      <nav id="navbar" class="navbar">
        <ul>
        <li><a class="nav-link scrollto" href="index.php#hero">Home</a></li>
          <li><a class="nav-link scrollto" href="index.php#about">About</a></li>
          <li><a class="nav-link scrollto" href="index.php#activities">Activities</a></li>
          <li><a class="nav-link scrollto" href="index.php#events">Events</a></li>
          <?php
            if(!isset($_SESSION["user"])){
            } else {
              echo "<li><a class='nav-link scrollto active' href='my-events.php'>My Events</a></li>";
            }
          ?>
          <li><a class="nav-link scrollto" href="index.php#team">Team</a></li>
          <li><a class="nav-link scrollto" href="index.php#contact">Contact</a></li>
          <?php
            if(!isset($_SESSION["user"])){
              echo "<li><a class='login' href='authentication/login.php'>LOGIN</a></li>";
            } else {
              echo "<li><a class='nav-link scrollto' href='account-details.php'>Account</a></li>";
              echo "<li><a class='login' href='authentication/logout.php'>LOGOUT</a></li>";
            }
          ?>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
      <div class="container">
        <h2 class="header-text-2">My Events</h2>
        <?php
            echo "Events you are Registered, <b>".$username."</b>";
        ?>
      </div>
    </section><!-- End Breadcrumbs -->

    <section class="inner-page">
      <div class="container">
      <div class="line"></div>

    <!-- Table for Events -->
    <div class="container table-container">
<br>
<br>
  <div class="d-flex justify-content-between align-items-center">
    <h2 style="color:#e78000">All Events</h2>
    <a class="btn btn-success" href="index.php#events">Register to an Event</a>
  </div>
  <br>
  <table style="margin-bottom:60px;" class="table">
    <thead>
      <tr>
        <th>Event Name</th>
        <th>Date</th>
        <th>Time</th>
        <th style="text-align: center;">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php 

      

$all_events = array(); // Store fetched events for efficient display

    foreach ($event_ids as $id) {
      $query = "SELECT * FROM events WHERE event_id = :event_id"; // Use prepared statement

      $stmt = $pdo_obj->prepare($query);
      $stmt->bindValue(':event_id', $id, PDO::PARAM_INT); // Bind ID as integer
      $stmt->execute();

      $event = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch single event

      if ($event) { // Check if event was found
        $all_events[] = $event; // Add event to array for efficient display
      } 
    }
    foreach ($all_events  as $event): ?>
        <tr>
          <td><?php echo $event['event_name']; ?></td>
          <td><?php echo date("F j, Y", strtotime($event['event_date_start'])); ?> - <?php echo date("F j, Y", strtotime($event['event_date_end'])); ?></td>
          <td><?php echo date("h:i A", strtotime($event['event_time_start'])); ?> - <?php echo date("h:i A", strtotime($event['event_time_end'])); ?></td>
          <td>
          <a href="userAction/unregister.php?id=<?php echo $event['event_id']; ?>"><button style="margin-bottom:10px; display: flex; justify-content: center; align-items: center;" class="btn btn-outline-danger dash-button">Unregister</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>


      <div class="line"></div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php
          include 'db-connector.php';

          $query = "SELECT * FROM org_info";
          $statement = $pdo_obj->query($query);
          $rowsReturned = $statement->rowCount();

          if ($rowsReturned > 0) {
              while ($org_details = $statement->fetch(PDO::FETCH_ASSOC)) {
                  $org_addressnum = $org_details["org_addressnum"];
                  $org_street = $org_details["org_street"];
                  $org_brgy_mncplty = $org_details["org_brgy_mncplty"];
                  $org_city_state_province = $org_details["org_city_state_province"];
                  $org_country = $org_details["org_country"];
                  $org_email = $org_details["org_email"];
                  $org_map = $org_details["org_map"];
                  $org_contactnum = $org_details["org_contactnum"];
              }
          } else {
              $org_addressnum = "";
              $org_street = "";
              $org_brgy_mncplty = "";
              $org_city_state_province = "";
              $org_country = "";
              $org_email = "";
              $org_map = "";
              $org_contactnum = "";
          }
    ?>

<footer id="footer">


<div class="footer-top">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-6 footer-contact">
        <h3>Tanglaw University</h3>
        <?php
          echo  "<p>
                  ".$org_addressnum." ".$org_street."<br>
                  ".$org_brgy_mncplty."<br>
                  ".$org_city_state_province."<br>
                  ".$org_country."<br>
                  <strong>Phone:</strong>".$org_contactnum."<br>
                  <strong>Email:</strong>".$org_email."<br>
                </p>";
        ?>
      </div>

      <div class="col-lg-3 col-md-6 footer-links">
            <h4>Our Social Networks</h4>
            <p>Feel free to reach us using our socials!</p>
            <div class="social-links mt-3">
              <a href="https://www.facebook.com/TanglawUniversityCenter/" target="_blank" class="facebook"><i class="bx bxl-facebook"></i></a>
            </div>
          </div>

    </div>
  </div>
</div>

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
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  
</body>
</html>