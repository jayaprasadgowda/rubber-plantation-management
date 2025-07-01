<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rubber Sales Entry | AgriCulture</title>
  <meta name="description" content="Record rubber sheet sales for your plantation.">
  <meta name="keywords" content="rubber sales, sheet sales, agriculture management">

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
            <h1>Rubber Sales Entry</h1>
            <p class="mb-0">Record details of rubber sheet sales for your plantation.</p>
          </div>
        </div>
      </div>
    </div>
    <nav class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="index.html">Home</a></li>
          <li><a href="services.html">Services</a></li>
          <li class="current">Rubber Sales Entry</li>
        </ol>
      </div>
    </nav>
  </div><!-- End Page Title -->

  <!-- ======= Sales Form Section ======= -->
  <section id="sales-entry" class="section light-background">
    <div class="container" data-aos="fade-up">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <?php
          // Database connection (update with your credentials)
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "rubber_management";

          try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Handle form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $sale_date = $_POST['sale_date'];
              $buyer_name = $_POST['buyer_name'];
              $sheets_sold = $_POST['sheets_sold'];
              $rate_per_sheet = $_POST['rate_per_sheet'];
              $total = $sheets_sold * $rate_per_sheet;

              // Validate inputs
              if (empty($sale_date) || empty($buyer_name) || empty($sheets_sold) || empty($rate_per_sheet)) {
                echo '<div class="alert alert-danger">All required fields must be filled.</div>';
              } elseif ($sheets_sold <= 0 || $rate_per_sheet <= 0) {
                echo '<div class="alert alert-danger">Sheets sold and rate per sheet must be positive.</div>';
              } else {
                // Check available stock
                $stmt = $conn->prepare("SELECT SUM(CASE WHEN type = 'IN' THEN quantity ELSE 0 END) - SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END) AS current_stock FROM inventory");
                $stmt->execute();
                $current_stock = $stmt->fetch(PDO::FETCH_ASSOC)['current_stock'] ?? 0;

                if ($sheets_sold > $current_stock) {
                  echo '<div class="alert alert-danger">Error: Not enough sheets in stock. Current stock: ' . htmlspecialchars($current_stock) . ' sheets.</div>';
                } else {
                  // Insert into sales table
                  $stmt = $conn->prepare("INSERT INTO sales (sale_date, buyer_name, sheets_sold, rate_per_sheet, total) VALUES (:sale_date, :buyer_name, :sheets_sold, :rate_per_sheet, :total)");
                  $stmt->execute([
                    ':sale_date' => $sale_date,
                    ':buyer_name' => $buyer_name,
                    ':sheets_sold' => $sheets_sold,
                    ':rate_per_sheet' => $rate_per_sheet,
                    ':total' => $total
                  ]);

                  // Update inventory table
                  $description = "$sheets_sold sheets sold to $buyer_name on $sale_date";
                  $stmt = $conn->prepare("INSERT INTO inventory (entry_date, type, quantity, description) VALUES (:entry_date, 'OUT', :quantity, :description)");
                  $stmt->execute([
                    ':entry_date' => $sale_date,
                    ':quantity' => $sheets_sold,
                    ':description' => $description
                  ]);

                  echo '<div class="alert alert-success">Sale recorded successfully. <a href="sales_view.php">View Sales</a></div>';
                }
              }
            }
          } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
          }
          $conn = null;
          ?>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="php-email-form" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4">
              <div class="col-md-6">
                <label for="sale_date" class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" name="sale_date" id="sale_date" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label for="buyer_name" class="form-label">Buyer Name <span class="text-danger">*</span></label>
                <input type="text" name="buyer_name" id="buyer_name" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label for="sheets_sold" class="form-label">Sheets Sold <span class="text-danger">*</span></label>
                <input type="number" name="sheets_sold" id="sheets_sold" class="form-control" min="1" required>
              </div>
              <div class="col-md-6">
                <label for="rate_per_sheet" class="form-label">Rate per Sheet <span class="text-danger">*</span></label>
                <input type="number" name="rate_per_sheet" id="rate_per_sheet" class="form-control" step="0.01" min="0" required>
              </div>
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary" style="background-color: #28a745; border-color: #28a745;">Record Sale</button>
                <a href="sales_view.php" class="btn btn-secondary">View Sales</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section><!-- End Sales Form Section -->

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