<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Loss & Expenditure | AgriCulture</title>
  <meta name="description" content="Track losses and expenditures in rubber plantation operations.">
  <meta name="keywords" content="rubber losses, expenditures, agriculture management">

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
            <h1>Loss & Expenditure</h1>
            <p class="mb-0">Track losses and expenditures in your rubber plantation.</p>
          </div>
        </div>
      </div>
    </div>
    <nav class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="index.html">Home</a></li>
          <li><a href="services.html">Services</a></li>
          <li class="current">Loss & Expenditure</li>
        </ol>
      </div>
    </nav>
  </div><!-- End Page Title -->

  <!-- ======= Expenditure Form and Table Section ======= -->
  <section id="expenditures" class="section light-background">
    <div class="container" data-aos="fade-up">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <h3 class="text-center mb-4">Record and View Losses/Expenditures</h3>
          <!-- Add Expenditure Form -->
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
              $entry_date = $_POST['entry_date'];
              $type = $_POST['type'];
              $reason = $_POST['reason'];
              $lost_sheets = $_POST['lost_sheets'] ?: null;
              $amount = $_POST['amount'] ?: null;
              $remarks = $_POST['remarks'];

              // Validate inputs
              if (empty($entry_date) || empty($type) || empty($reason)) {
                echo '<div class="alert alert-danger">Date, type, and reason are required.</div>';
              } elseif ($type == 'Loss' && (empty($lost_sheets) || $lost_sheets < 0)) {
                echo '<div class="alert alert-danger">Lost sheets must be zero or positive for losses.</div>';
              } elseif ($type == 'Expenditure' && (empty($amount) || $amount <= 0)) {
                echo '<div class="alert alert-danger">Amount must be positive for expenditures.</div>';
              } else {
                // Insert into expenditures table
                $stmt = $conn->prepare("INSERT INTO expenditures (entry_date, type, reason, lost_sheets, amount, remarks) VALUES (:entry_date, :type, :reason, :lost_sheets, :amount, :remarks)");
                $stmt->execute([
                  ':entry_date' => $entry_date,
                  ':type' => $type,
                  ':reason' => $reason,
                  ':lost_sheets' => $lost_sheets,
                  ':amount' => $amount,
                  ':remarks' => $remarks
                ]);
                echo '<div class="alert alert-success">Record added successfully.</div>';
              }
            }
          } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
          }
          ?>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="php-email-form mb-5" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4">
              <div class="col-md-6">
                <label for="entry_date" class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" name="entry_date" id="entry_date" class="form-control" required aria-label="Entry Date">
              </div>
              <div class="col-md-6">
                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                <select name="type" id="type" class="form-select" required aria-label="Type" onchange="toggleFields()">
                  <option value="Loss">Loss</option>
                  <option value="Expenditure">Expenditure</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                <input type="text" name="reason" id="reason" class="form-control" required aria-label="Reason" maxlength="100">
              </div>
              <div class="col-md-6" id="lost_sheets_field">
                <label for="lost_sheets" class="form-label">Lost Sheets</label>
                <input type="number" name="lost_sheets" id="lost_sheets" class="form-control" min="0" aria-label="Lost Sheets">
              </div>
              <div class="col-md-6" id="amount_field" style="display: none;">
                <label for="amount" class="form-label">Amount (₹)</label>
                <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0" aria-label="Amount">
              </div>
              <div class="col-md-12">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea name="remarks" id="remarks" class="form-control" rows="3" aria-label="Remarks"></textarea>
              </div>
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary" style="background-color: #28a745; border-color: #28a745;">Record Entry</button>
              </div>
            </div>
          </form>

          <!-- Expenditure Table -->
          <h4 class="text-center mb-4">Loss and Expenditure Records</h4>
          <?php
          try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Fetch all expenditure entries
            $stmt = $conn->prepare("SELECT * FROM expenditures ORDER BY entry_date DESC");
            $stmt->execute();
            $expenditures = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($expenditures) > 0) {
              echo '<div class="table-responsive" data-aos="fade-up" data-aos-delay="200">';
              echo '<table class="table table-bordered table-hover">';
              echo '<thead class="table-light">';
              echo '<tr>';
              echo '<th scope="col">ID</th>';
              echo '<th scope="col">Date</th>';
              echo '<th scope="col">Type</th>';
              echo '<th scope="col">Reason</th>';
              echo '<th scope="col">Lost Sheets</th>';
              echo '<th scope="col">Amount (₹)</th>';
              echo '<th scope="col">Remarks</th>';
              echo '</tr>';
              echo '</thead>';
              echo '<tbody>';
              foreach ($expenditures as $exp) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($exp['id']) . '</td>';
                echo '<td>' . htmlspecialchars($exp['entry_date']) . '</td>';
                echo '<td>' . ($exp['type'] == 'Loss' ? '<span class="badge bg-danger">Loss</span>' : '<span class="badge bg-warning">Expenditure</span>') . '</td>';
                echo '<td>' . htmlspecialchars($exp['reason']) . '</td>';
                echo '<td>' . ($exp['lost_sheets'] !== null ? htmlspecialchars($exp['lost_sheets']) : '-') . '</td>';
                echo '<td>' . ($exp['amount'] !== null ? '₹' . htmlspecialchars(number_format($exp['amount'], 2)) : '-') . '</td>';
                echo '<td>' . (empty($exp['remarks']) ? '-' : htmlspecialchars($exp['remarks'])) . '</td>';
                echo '</tr>';
              }
              echo '</tbody>';
              echo '</table>';
              echo '</div>';
            } else {
              echo '<div class="alert alert-info">No loss or expenditure records found.</div>';
            }
          } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
          }
          $conn = null;
          ?>
        </div>
      </div>
    </div>
  </section><!-- End Expenditure Form and Table Section -->

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
    function toggleFields() {
      const type = document.getElementById('type').value;
      document.getElementById('lost_sheets_field').style.display = type === 'Loss' ? 'block' : 'none';
      document.getElementById('amount_field').style.display = type === 'Expenditure' ? 'block' : 'none';
    }
    window.onload = toggleFields;
  </script>
</body>
</html>