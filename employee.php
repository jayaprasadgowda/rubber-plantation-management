<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Employee Management | AgriCulture</title>
  <meta name="description" content="Manage employee details and payment records for your rubber plantation.">
  <meta name="keywords" content="employee management, wages, agriculture management">

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
            <h1>Employee Management</h1>
            <p class="mb-0">Add employee details and record payments for your rubber plantation.</p>
          </div>
        </div>
      </div>
    </div>
    <nav class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="index.html">Home</a></li>
          <li><a href="services.html">Services</a></li>
          <li class="current">Employee Management</li>
        </ol>
      </div>
    </nav>
  </div><!-- End Page Title -->

  <!-- ======= Employee Form and Table Section ======= -->
  <section id="employees" class="section light-background">
    <div class="container" data-aos="fade-up">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <h3 class="text-center mb-4">Add Employee and Payments</h3>
          <!-- Add Employee Form -->
          <?php
          // Database connection (update with your credentials)
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "rubber_management";

          try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Handle employee form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_employee'])) {
              $name = $_POST['name'];
              $role = $_POST['role'];

              if (empty($name) || empty($role)) {
                echo '<div class="alert alert-danger">Name and role are required.</div>';
              } else {
                $stmt = $conn->prepare("INSERT INTO employees (name, role) VALUES (:name, :role)");
                $stmt->execute([':name' => $name, ':role' => $role]);
                echo '<div class="alert alert-success">Employee added successfully.</div>';
              }
            }

            // Handle payment form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_payment'])) {
              $employee_id = $_POST['employee_id'];
              $payment_date = $_POST['payment_date'];
              $amount = $_POST['amount'];
              $remarks = $_POST['remarks'];

              if (empty($employee_id) || empty($payment_date) || empty($amount)) {
                echo '<div class="alert alert-danger">Employee, payment date, and amount are required.</div>';
              } elseif ($amount <= 0) {
                echo '<div class="alert alert-danger">Amount must be positive.</div>';
              } else {
                // Insert into employee_payments
                $stmt = $conn->prepare("INSERT INTO employee_payments (employee_id, payment_date, amount, remarks) VALUES (:employee_id, :payment_date, :amount, :remarks)");
                $stmt->execute([
                  ':employee_id' => $employee_id,
                  ':payment_date' => $payment_date,
                  ':amount' => $amount,
                  ':remarks' => $remarks
                ]);

                // Insert into expenditures
                $stmt = $conn->prepare("INSERT INTO expenditures (entry_date, type, reason, lost_sheets, amount, remarks) VALUES (:entry_date, 'Expenditure', 'Employee Wages', NULL, :amount, :remarks)");
                $stmt->execute([
                  ':entry_date' => $payment_date,
                  ':amount' => $amount,
                  ':remarks' => "Payment to employee ID $employee_id: $remarks"
                ]);

                echo '<div class="alert alert-success">Payment recorded successfully.</div>';
              }
            }
          } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
          }
          ?>
          <h4 class="mb-3">Add New Employee</h4>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="php-email-form mb-5" data-aos="fade-up" data-aos-delay="100">
            <input type="hidden" name="add_employee" value="1">
            <div class="row gy-4">
              <div class="col-md-6">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required aria-label="Employee Name" maxlength="100">
              </div>
              <div class="col-md-6">
                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                <input type="text" name="role" id="role" class="form-control" required aria-label="Employee Role" maxlength="50">
              </div>
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary" style="background-color: #28a745; border-color: #28a745;">Add Employee</button>
              </div>
            </div>
          </form>

          <!-- Add Payment Form -->
          <h4 class="mb-3">Record Payment</h4>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="php-email-form mb-5" data-aos="fade-up" data-aos-delay="200">
            <input type="hidden" name="add_payment" value="1">
            <div class="row gy-4">
              <div class="col-md-6">
                <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                <select name="employee_id" id="employee_id" class="form-select" required aria-label="Select Employee">
                  <option value="">Select Employee</option>
                  <?php
                  try {
                    $stmt = $conn->prepare("SELECT id, name, role FROM employees ORDER BY name");
                    $stmt->execute();
                    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($employees as $employee) {
                      echo '<option value="' . htmlspecialchars($employee['id']) . '">' . htmlspecialchars($employee['name']) . ' (' . htmlspecialchars($employee['role']) . ')</option>';
                    }
                  } catch (PDOException $e) {
                    echo '<option value="">Error loading employees</option>';
                  }
                  ?>
                </select>
              </div>
              <div class="col-md-6">
                <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                <input type="date" name="payment_date" id="payment_date" class="form-control" required aria-label="Payment Date">
              </div>
              <div class="col-md-6">
                <label for="amount" class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0" required aria-label="Payment Amount">
              </div>
              <div class="col-md-6">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea name="remarks" id="remarks" class="form-control" rows="3" aria-label="Remarks"></textarea>
              </div>
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary" style="background-color: #28a745; border-color: #28a745;">Record Payment</button>
              </div>
            </div>
          </form>

          <!-- Payment Table -->
          <h4 class="text-center mb-4">Payment History</h4>
          <?php
          try {
            $stmt = $conn->prepare("SELECT ep.id, ep.employee_id, e.name, e.role, ep.payment_date, ep.amount, ep.remarks 
                                    FROM employee_payments ep 
                                    JOIN employees e ON ep.employee_id = e.id 
                                    ORDER BY ep.payment_date DESC");
            $stmt->execute();
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($payments) > 0) {
              echo '<div class="table-responsive" data-aos="fade-up" data-aos-delay="300">';
              echo '<table class="table table-bordered table-hover">';
              echo '<thead class="table-light">';
              echo '<tr>';
              echo '<th scope="col">ID</th>';
              echo '<th scope="col">Employee</th>';
              echo '<th scope="col">Role</th>';
              echo '<th scope="col">Payment Date</th>';
              echo '<th scope="col">Amount (₹)</th>';
              echo '<th scope="col">Remarks</th>';
              echo '</tr>';
              echo '</thead>';
              echo '<tbody>';
              foreach ($payments as $payment) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($payment['id']) . '</td>';
                echo '<td>' . htmlspecialchars($payment['name']) . '</td>';
                echo '<td>' . htmlspecialchars($payment['role']) . '</td>';
                echo '<td>' . htmlspecialchars($payment['payment_date']) . '</td>';
                echo '<td>' . htmlspecialchars(number_format($payment['amount'], 2)) . '</td>';
                echo '<td>' . (empty($payment['remarks']) ? '-' : htmlspecialchars($payment['remarks'])) . '</td>';
                echo '</tr>';
              }
              echo '</tbody>';
              echo '</table>';
              echo '</div>';
            } else {
              echo '<div class="alert alert-info">No payment records found.</div>';
            }
          } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
          }
          $conn = null;
          ?>
        </div>
      </div>
    </div>
  </section><!-- End Employee Form and Table Section -->

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
  </script>
</body>
</html>