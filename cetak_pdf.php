<?php
session_start();
require_once('tcpdf/tcpdf.php'); 

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'aroma_isuk';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$items = [];
$totalHargaKeseluruhan = 0;

if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $sql = "SELECT * FROM menu WHERE id IN ($ids)";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['quantity'] = $cart[$row['id']];
            $items[] = $row;
            $totalHargaKeseluruhan += $row['price'] * $row['quantity'];
        }
    }
}

// Buat objek TCPDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Aroma Isuk');
$pdf->SetTitle('Nota Belanja');
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

// Tambahkan judul
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Nota Belanja - Aroma Isuk', 0, 1, 'C');

// Tambahkan garis
$pdf->SetLineWidth(0.5);
$pdf->Line(10, 25, 200, 25);

// Tambahkan detail keranjang
$pdf->SetFont('helvetica', '', 12);
$pdf->Ln(10);
$html = '<table border="1" cellpadding="5">
    <tr style="background-color:#ddd;">
        <th width="50">No</th>
        <th width="200">Nama Menu</th>
        <th width="80">Harga</th>
        <th width="50">Qty</th>
        <th width="80">Subtotal</th>
    </tr>';

$no = 1;
foreach ($items as $item) {
    $subtotal = $item['price'] * $item['quantity'];
    $html .= '<tr>
        <td align="center">' . $no++ . '</td>
        <td>' . htmlspecialchars($item['name']) . '</td>
        <td align="right">Rp ' . number_format($item['price'], 0, ',', '.') . '</td>
        <td align="center">' . $item['quantity'] . '</td>
        <td align="right">Rp ' . number_format($subtotal, 0, ',', '.') . '</td>
    </tr>';
}

$html .= '<tr style="font-weight:bold;">
    <td colspan="4" align="right">Total Harga:</td>
    <td align="right">Rp ' . number_format($totalHargaKeseluruhan, 0, ',', '.') . '</td>
</tr>';

$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Tambahkan catatan
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'I', 10);
$pdf->Cell(0, 10, 'Terima kasih telah berbelanja di Aroma Isuk!', 0, 1, 'C');

// Output PDF
$pdf->Output('nota_belanja.pdf', 'I');
?>