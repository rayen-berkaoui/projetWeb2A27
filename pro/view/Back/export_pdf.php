<?php
// Remove any output before this point to prevent TCPDF errors
ob_start();  // Start output buffering

require_once('C:\xampp\htdocs\2A27\pro\config.php');
require_once('C:\xampp\htdocs\2A27\pro\vendor\autoload.php'); // Include TCPDF

// Database connection
$conn = config::getConnexion();

// Fetch users from database
$query = $conn->query("SELECT u.*, r.name AS nom_role FROM utilisateurs u LEFT JOIN role r ON u.role = r.id");
$utilisateurs = $query->fetchAll(PDO::FETCH_ASSOC);

// Create new TCPDF instance
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Delizar');
$pdf->SetTitle('Liste des Utilisateurs');
$pdf->SetSubject('Export des utilisateurs');
$pdf->SetHeaderData('', 0, 'Liste des Utilisateurs', 'Export PDF');
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Prepare HTML content for the table
$html = '
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom d\'utilisateur</th>
                <th>Mot de passe</th>
                <th>Rôle</th>
                <th>Email</th>
                <th>Numéro</th>
            </tr>
        </thead>
        <tbody>';

foreach ($utilisateurs as $user) {
    $html .= '
        <tr>
            <td>' . htmlspecialchars($user['id_user']) . '</td>
            <td>' . htmlspecialchars($user['username']) . '</td>
            <td>' . htmlspecialchars($user['nom_role'] ?? $user['role']) . '</td>
            <td>' . htmlspecialchars($user['email']) . '</td>
            <td>' . htmlspecialchars($user['numero']) . '</td>
        </tr>';
}

$html .= '
        </tbody>
    </table>';

// Write HTML to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('utilisateurs_list.pdf', 'I'); // 'I' for inline display, 'D' for download

ob_end_flush();  // End output buffering and send output
?>
