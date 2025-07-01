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

  // Fetch report data
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

  $sales_sql = "SELECT 
                COALESCE(SUM(sheets_sold), 0) AS total_sold,
                COALESCE(SUM(total), 0) AS total_income
                FROM sales s" . ($where_clause ? " WHERE " . str_replace('re.entry_date', 's.sale_date', $where_clause) : "");
  $stmt = $conn->prepare($sales_sql);
  $stmt->execute($params);
  $sales_data = $stmt->fetch(PDO::FETCH_ASSOC);

  $employee_sql = "SELECT 
                  COALESCE(SUM(amount), 0) AS total_employee_payments
                  FROM employee_payments ep" . ($where_clause ? " WHERE " . str_replace('re.entry_date', 'ep.payment_date', $where_clause) : "");
  $stmt = $conn->prepare($employee_sql);
  $stmt->execute($params);
  $employee_data = $stmt->fetch(PDO::FETCH_ASSOC);

  $expenditure_sql = "SELECT 
                     COALESCE(SUM(amount), 0) AS total_expenditures
                     FROM expenditures e WHERE e.type = 'Expenditure'" . ($where_clause ? " AND " . str_replace('re.entry_date', 'e.entry_date', $where_clause) : "");
  $stmt = $conn->prepare($expenditure_sql);
  $stmt->execute($params);
  $expenditure_data = $stmt->fetch(PDO::FETCH_ASSOC);

  $total_costs = $rubber_data['production_cost'] + $employee_data['total_employee_payments'] + $expenditure_data['total_expenditures'];
  $profit_loss = $sales_data['total_income'] - $total_costs;

  // Export type (CSV or PDF)
  $export_type = isset($_GET['export_type']) ? $_GET['export_type'] : 'csv';

  if ($export_type == 'csv') {
    // CSV Export
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="rubber_report_' . date('Ymd_His') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Metric', 'Value']);
    fputcsv($output, ['Total Milk Used (Liters)', number_format($rubber_data['total_milk'], 2)]);
    fputcsv($output, ['Total Acid Used (Liters)', number_format($rubber_data['total_acid'], 2)]);
    fputcsv($output, ['Total Sheets Produced', $rubber_data['total_produced']]);
    fputcsv($output, ['Total Sheets Sold', $sales_data['total_sold']]);
    fputcsv($output, ['Total Income', '₹' . number_format($sales_data['total_income'], 2)]);
    fputcsv($output, ['Total Employee Payments', '₹' . number_format($employee_data['total_employee_payments'], 2)]);
    fputcsv($output, ['Other Expenditures', '₹' . number_format($expenditure_data['total_expenditures'], 2)]);
    fputcsv($output, ['Wastage (Sheets)', $rubber_data['wastage']]);
    fputcsv($output, ['Profit/Loss Estimation', '₹' . number_format($profit_loss, 2)]);
    fclose($output);
  } else {
    // PDF Export (using TCPDF)
    require_once('tcpdf/tcpdf.php');
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('AgriCulture');
    $pdf->SetTitle('Rubber Plantation Report');
    $pdf->SetHeaderData('', 0, 'Rubber Plantation Report', '');
    $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
    $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setFont('helvetica', '', 12);
    $pdf->AddPage();

    $html = '<h1>Rubber Plantation Report</h1>';
    $html .= '<table border="1" cellpadding="5">';
    $html .= '<tr><th>Metric</th><th>Value</th></tr>';
    $html .= '<tr><td>Total Milk Used (Liters)</td><td>' . number_format($rubber_data['total_milk'], 2) . '</td></tr>';
    $html .= '<tr><td>Total Acid Used (Liters)</td><td>' . number_format($rubber_data['total_acid'], 2) . '</td></tr>';
    $html .= '<tr><td>Total Sheets Produced</td><td>' . $rubber_data['total_produced'] . '</td></tr>';
    $html .= '<tr><td>Total Sheets Sold</td><td>' . $sales_data['total_sold'] . '</td></tr>';
    $html .= '<tr><td>Total Income</td><td>₹' . number_format($sales_data['total_income'], 2) . '</td></tr>';
    $html .= '<tr><td>Total Employee Payments</td><td>₹' . number_format($employee_data['total_employee_payments'], 2) . '</td></tr>';
    $html .= '<tr><td>Other Expenditures</td><td>₹' . number_format($expenditure_data['total_expenditures'], 2) . '</td></tr>';
    $html .= '<tr><td>Wastage (Sheets)</td><td>' . $rubber_data['wastage'] . '</td></tr>';
    $html .= '<tr><td>Profit/Loss Estimation</td><td>₹' . number_format($profit_loss, 2) . '</td></tr>';
    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('rubber_report_' . date('Ymd_His') . '.pdf', 'D');
  }

} catch (PDOException $e) {
  echo 'Error: ' . htmlspecialchars($e->getMessage());
}
$conn = null;
?>