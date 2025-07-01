<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Operation Reports | AgriCulture</title>
  <meta name="description" content="View daily or monthly summaries of rubber plantation operations.">
  <meta name="keywords" content="rubber reports, plantation summary, agriculture management">

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
            <h1>Operation Reports</h1>
            <p class="mb-0">View summaries of rubber plantation operations by date range or month/year.</p>
          </div>
        </div>
      </div>
    </div>
    <nav class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="index.html">Home</a></li>
          <li><a href="services.html">Services</a></li>
          <li class="current">Operation Reports</li>
        </ol>
      </div>
    </nav>
  </div><!-- End Page Title -->

  <!-- ======= Reports Section ======= -->
  <section id="reports" class="section light-background">
    <div class="container" data-aos="fade-up">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <h3 class="text-center mb-4">Operation Summary</h3>
          <!-- Filter Form -->
          <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="php-email-form mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4">
              <div class="col-md-4">
                <label for="filter_type" class="form-label">Filter Type <span class="text-danger">*</span></label>
                <select name="filter_type" id="filter_type" class="form-select" required onchange="toggleFilterFields()">
                  <option value="date_range" <?php echo (isset($_GET['filter_type']) && $_GET['filter_type'] == 'date_range') ? 'selected' : ''; ?>>Date Range</option>
                  <option value="monthly" <?php echo (isset($_GET['filter_type']) && $_GET['filter_type'] == 'monthly') ? 'selected' : ''; ?>>Monthly</option>
                  <option value="yearly" <?php echo (isset($_GET['filter_type']) && $_GET['filter_type'] == 'yearly') ? 'selected' : ''; ?>>Yearly</option>
                </select>
              </div>
              <div class="col-md-4" id="date_range_fields">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">
                <label for="end_date" class="form-label mt-2">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">
              </div>
              <div class="col-md-4" id="month_year_fields" style="display: none;">
                <label for="month" class="form-label">Month</label>
                <select name="month" id="month" class="form-select">
                  <option value="">Select Month</option>
                  <?php
                  $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                  foreach ($months as $i => $m) {
                    $month_num = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
                    echo '<option value="' . $month_num . '" ' . (isset($_GET['month']) && $_GET['month'] == $month_num ? 'selected' : '') . '>' . $m . '</option>';
                  }
                  ?>
                </select>
                <label for="year" class="form-label mt-2">Year</label>
                <select name="year" id="year" class="form-select">
                  <option value="">Select Year</option>
                  <?php
                  for ($y = date('Y'); $y >= 2020; $y--) {
                    echo '<option value="' . $y . '" ' . (isset($_GET['year']) && $_GET['year'] == $y ? 'selected' : '') . '>' . $y . '</option>';
                  }
                  ?>
                </select>
              </div>
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary" style="background-color: #28a745; border-color: #28a745;">Apply Filter</button>
                <a href="export.php?<?php echo htmlspecialchars(http_build_query($_GET)); ?>" class="btn btn-secondary">Export Report</a>
              </div>
            </div>
          </form>

          <?php
          // Database connection (update with your credentials)
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "rubber_management";

          try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Build query based on filter
            $where_clause = '';
            $params = [];
            if (isset($_GET['filter_type'])) {
              if ($_GET['filter_type'] == 'date_range' && !empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                $where_clause = 're.entry_date BETWEEN :start_date AND :end_date';
                $params[':start_date'] = $_GET['start_date'];
                $params[':end_date'] = $_GET['end_date'];
              } elseif ($_GET['filter_type'] == 'monthly' && !empty($_GET['month']) && !empty($_GET['year'])) {
                $where_clause = 'MONTH(re.entry_date) = :month AND YEAR(re.entry_date) = :year';
                $params[':month'] = $_GET['month'];
                $params[':year'] = $_GET['year'];
              } elseif ($_GET['filter_type'] == 'yearly' && !empty($_GET['year'])) {
                $where_clause = 'YEAR(re.entry_date) = :year';
                $params[':year'] = $_GET['year'];
              }
            }

            // Query for rubber_entry data
            $sql = "SELECT 
                    COALESCE(SUM(milk_liters), 0) AS total_milk,
                    COALESCE(SUM(acid_used), 0) AS total_acid,
                    COALESCE(SUM(actual_sheets), 0) AS total_produced,
                    COALESCE(SUM(expected_sheets - actual_sheets), 0) AS wastage,
                    COALESCE(SUM(actual_sheets * rate_per_sheet), 0) AS production_cost
                    FROM rubber_entry re" . ($where_clause ? " WHERE $where_clause" : "");
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $rubber_data = $stmt->fetch(PDO::FETCH_ASSOC);

            // Query for sales data
            $sales_sql = "SELECT 
                         COALESCE(SUM(sheets_sold), 0) AS total_sold,
                         COALESCE(SUM(total), 0) AS total_income
                         FROM sales s" . ($where_clause ? " WHERE " . str_replace('re.entry_date', 's.sale_date', $where_clause) : "");
            $stmt = $conn->prepare($sales_sql);
            $stmt->execute($params);
            $sales_data = $stmt->fetch(PDO::FETCH_ASSOC);

            // Query for employee payments
            $employee_sql = "SELECT 
                            COALESCE(SUM(amount), 0) AS total_employee_payments
                            FROM employee_payments ep" . ($where_clause ? " WHERE " . str_replace('re.entry_date', 'ep.payment_date', $where_clause) : "");
            $stmt = $conn->prepare($employee_sql);
            $stmt->execute($params);
            $employee_data = $stmt->fetch(PDO::FETCH_ASSOC);

            // Query for other expenditures
            $expenditure_sql = "SELECT 
                               COALESCE(SUM(amount), 0) AS total_expenditures
                               FROM expenditures e WHERE e.type = 'Expenditure'" . ($where_clause ? " AND " . str_replace('re.entry_date', 'e.entry_date', $where_clause) : "");
            $stmt = $conn->prepare($expenditure_sql);
            $stmt->execute($params);
            $expenditure_data = $stmt->fetch(PDO::FETCH_ASSOC);

            // Calculate profit/loss
            $total_costs = $rubber_data['production_cost'] + $employee_data['total_employee_payments'] + $expenditure_data['total_expenditures'];
            $profit_loss = $sales_data['total_income'] - $total_costs;

            // Display summary
            echo '<div class="table-responsive" data-aos="fade-up" data-aos-delay="200">';
            echo '<table class="table table-bordered table-hover">';
            echo '<thead class="table-light">';
            echo '<tr>';
            echo '<th scope="col">Metric</th>';
            echo '<th scope="col">Value</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr><td>Total Milk Used (Liters)</td><td>' . htmlspecialchars(number_format($rubber_data['total_milk'], 2)) . '</td></tr>';
            echo '<tr><td>Total Acid Used (Liters)</td><td>' . htmlspecialchars(number_format($rubber_data['total_acid'], 2)) . '</td></tr>';
            echo '<tr><td>Total Sheets Produced</td><td>' . htmlspecialchars($rubber_data['total_produced']) . '</td></tr>';
            echo '<tr><td>Total Sheets Sold</td><td>' . htmlspecialchars($sales_data['total_sold']) . '</td></tr>';
            echo '<tr><td>Total Income</td><td>₹' . htmlspecialchars(number_format($sales_data['total_income'], 2)) . '</td></tr>';
            echo '<tr><td>Total Employee Payments</td><td>₹' . htmlspecialchars(number_format($employee_data['total_employee_payments'], 2)) . '</td></tr>';
            echo '<tr><td>Other Expenditures</td><td>₹' . htmlspecialchars(number_format($expenditure_data['total_expenditures'], 2)) . '</td></tr>';
            echo '<tr><td>Wastage (Sheets)</td><td>' . htmlspecialchars($rubber_data['wastage']) . '</td></tr>';
            echo '<tr><td>Profit/Loss Estimation</td><td>₹' . htmlspecialchars(number_format($profit_loss, 2)) . '</td></tr>';
            echo '</tbody>';
            echo '</table>';
            echo '</div>';

          } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
          }
          $conn = null;
          ?>
        </div>
      </div>
    </div>
  </section><!-- End Reports Section -->

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
            <li><a href="about.html">About us</a></li>
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
            <li><a href="employee.php">Employee Management</a></li>
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
    function toggleFilterFields() {
      const filterType = document.getElementById('filter_type').value;
      document.getElementById('date_range_fields').style.display = filterType === 'date_range' ? 'block' : 'none';
      document.getElementById('month_year_fields').style.display = filterType === 'date_range' ? 'none' : 'block';
    }
    window.onload = toggleFilterFields;
  </script>
</body>
</html>