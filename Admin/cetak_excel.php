<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'aroma_isuk';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM orders ORDER BY created_at DESC";
$result = $conn->query($sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Daftar Pesanan');

$sheet->setCellValue('A1', 'No.')
      ->setCellValue('B1', 'Alamat')
      ->setCellValue('C1', 'Kurir')
      ->setCellValue('D1', 'Total Harga')
      ->setCellValue('E1', 'Status')
      ->setCellValue('F1', 'Tanggal');

if ($result->num_rows > 0) {
    $rowNumber = 2;
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $counter++)
              ->setCellValue('B' . $rowNumber, htmlspecialchars($row['alamat']))
              ->setCellValue('C' . $rowNumber, htmlspecialchars($row['kurir']))
              ->setCellValue('D' . $rowNumber, 'Rp ' . number_format($row['total_harga'], 0, ',', '.'))
              ->setCellValue('E' . $rowNumber, htmlspecialchars($row['status']))
              ->setCellValue('F' . $rowNumber, $row['created_at']);
        $rowNumber++;
    }
}

$writer = new Xlsx($spreadsheet);
$filename = 'daftar_pesanan.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
?>
