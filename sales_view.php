<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Rubber Sales | AgriCulture</title>
  <meta name="description" content="View all rubber sheet sales records for your plantation.">
  <meta name="keywords" content="rubber sales, sales records, agriculture management">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Template Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
  <!-- AOS CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>
<body class="page-services">
  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
      <a href="index.html" class="logo d-flex align-items-center me-auto">
        <img src="assets/img/logo.png" alt="AgriCulture Logo" />
      </a>
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="about.html">About Us</a></li>
          <li><a href="services.html" class="active">Services</a></li>
          <li><a href="testimonials.html">Testimonials</a></li>
          <li><a href="blog.html">Blog</a></li>
          <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li><a href="#">Dropdown 2</a></li>
            </ul>
          </li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header><!-- End Header -->

  <!-- ======= Page Title ======= -->
  <div class="page-title" data-aos="fade">
    <div class="heading">
      <div class="container">
        <div class="row d-flex justify-content-center text-center">
          <div class="col-lg-8">
            <h1>View Rubber Sales</h1>
            <p class="mb-0">Browse all recorded rubber sheet sales.</p>
          </div>
        </div>
      </div>
    </div>
    <nav class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="index.html">Home</a></li>
          <li><a href="services.html">Services</a></li>
          <li class="current">View Rubber Sales</li>
        </ol>
      </div>
    </nav>
  </div><!-- End Page Title -->

  <!-- ======= Sales Table Section ======= -->
  <section id="sales-view" class="section light-background">
    <div class="container" data-aos="fade-up">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <h3 class="text-center mb-4">Rubber Sales Records</h3>
          <a href="sales.php" class="btn btn-primary mb-3" style="background-color: #28a745; border-color: #28a745;">Add New Sale</a>
          <?php
          // Database connection (update with your credentials)
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "rubber_management";

          try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Fetch all sales entries
            $stmt = $conn->prepare("SELECT * FROM sales ORDER BY sale_date DESC");
            $stmt->execute();
            $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($sales) > 0) {
              echo '<div class="table-responsive">';
              echo '<table class="table table-bordered table-hover">';
              echo '<thead class="table-light">';
              echo '<tr>';
              echo '<th scope="col">ID</th>';
              echo '<th scope="col">Date</th>';
              echo '<th scope="col">Buyer Name</th>';
              echo '<th scope="col">Sheets Sold</th>';
              echo '<th scope="col">Rate per Sheet</th>';
              echo '<th scope="col">Total</th>';
              echo '</tr>';
              echo '</thead>';
              echo '<tbody>';
              foreach ($sales as $sale) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($sale['id']) . '</td>';
                echo '<td>' . htmlspecialchars($sale['sale_date']) . '</td>';
                echo '<td>' . htmlspecialchars($sale['buyer_name']) . '</td>';
                echo '<td>' . htmlspecialchars($sale['sheets_sold']) . '</td>';
                echo '<td>' . htmlspecialchars($sale['rate_per_sheet']) . '</td>';
                echo '<td>' . htmlspecialchars($sale['total']) . '</td>';
                echo '</tr>';
              }
              echo '</tbody>';
              echo '</table>';
              echo '</div>';
            } else {
              echo '<div class="alert alert-info">No sales records found. <a href="sales.php">Add a new sale</a>.</div>';
            }
          } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
          }
          $conn = null;
          ?>
        </div>
      </div>
    </div>
  </section><!-- End Sales Table Section -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">AgriCulture</span>
          </a>
          <div class="footer-contact pt-3">
            <p>123 Farm Road</p>
            <p>Rural City, RC 45678</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+1 234 567 8900</span></p>
            <p><strong>Email:</strong> <span>info@agriculture.com</span></p>
          </div>
        </div>
        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="about.html">About us-inc</a></li>
            <li><a href="services.html">Services</a></li>
            <li><a href="#">Terms of service</a></li>
            <li><a href="#">Privacy policy</a></li>
          </ul>
        </div>
        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="rubber_entry.php">Daily Rubber Collection</a></li>
            <li><a href="inventory.php">Inventory Management</a></li>
            <li><a href="sales.php">Rubber Sales</a></li>
            <li><a href="report.php">Reports</a></li>
            <li><a href="loss.php">Loss & Expenditure</a></li>
          </ul>
        </div>
        <div class="col-lg-4 col-md-12 footer-newsletter">
          <h4>Our Newsletter</h4>
          <p>Subscribe to our newsletter and receive the latest updates!</p>
          <form action="forms/newsletter.php" method="post" class="php-email-form">
            <div class="newsletter-form">
              <input type="email" name="email"><input type="submit" value="Subscribe">
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">AgriCulture</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> & <a href="https://themewagon.com">ThemeWagon</a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <!-- AOS JS -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script>
    AOS.init();
  </script>
</body>
</html>