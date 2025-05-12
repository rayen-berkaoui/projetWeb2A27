<?php
require_once "C:/xampp/htdocs/lezm/fpdf/fpdf.php";
$active_menu = 'produit';
include_once __DIR__ . '/../layout.php';
include_once __DIR__ . '/../partials/sidebar.php';
require_once "C:/xampp/htdocs/lezm/view/user/pages/produits/ProduitC.php";

$prods = ProduitC::getProduits();

if (isset($_GET['telecharger_pdf'])) {
    ob_clean();
    $produits = ProduitC::getProduits();

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Liste des Produits', 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(20, 10, 'ID', 1);
    $pdf->Cell(30, 10, 'Nom', 1);
    $pdf->Cell(30, 10, 'Marque', 1);
    $pdf->Cell(30, 10, 'Materiel', 1);
    $pdf->Cell(20, 10, 'Prix', 1);
    $pdf->Cell(20, 10, 'Stock', 1);
    $pdf->Cell(30, 10, 'Pays', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    foreach ($produits as $p) {
        $pdf->Cell(20, 10, $p['id'], 1);
        $pdf->Cell(30, 10, $p['nom'], 1);
        $pdf->Cell(30, 10, $p['marque'], 1);
        $pdf->Cell(30, 10, $p['materiel'], 1);
        $pdf->Cell(20, 10, $p['prix'], 1);
        $pdf->Cell(20, 10, $p['stock'], 1);
        $pdf->Cell(30, 10, $p['pays'], 1);
        $pdf->Ln();
    }

    $pdf->Output('D', 'liste_produits.pdf');
    exit;
}
?>
<head>
    <style>
        .content-area {
            margin-left: 270px;
            padding: 20px;
            flex: 1;
        }

        .search-bar {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .search-bar input {
            padding: 10px;
            width: 200px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-bar button {
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #218838;
        }

        th {
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="content-area">
    <a href="/lezm/view/user/pages/produits/AjoutProduitForm.php">
        <button type="button" class="btn btn-primary">Ajouter un produit</button>
    </a>
    <a href="?telecharger_pdf=1" class="btn btn-success mt-3">📄 Télécharger la liste PDF</a>
    <h1 class="text-center my-4">Nos Produits</h1>

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Rechercher un produit...">
        <button id="searchBtn">Rechercher</button>
    </div>
<!-- 🔽 BOUTONS DE TRI PAR PRIX -->
<div style="margin-bottom: 20px;">
    <button onclick="sortByPrice('asc')" class="btn btn-info">Trier par prix ⬆️</button>
    <button onclick="sortByPrice('desc')" class="btn btn-info">Trier par prix ⬇️</button>
</div>

    <table id="productsTable" class="table table-bordered table-hover" style="width: 100%; border: 2px solid #000;">
        <thead class="thead-dark" style="background-color: #343a40; color: #fff;">
        <tr>
            <th style="border: 2px solid #000;">Image</th>
            <th style="border: 2px solid #000;">Nom</th>
            <th style="border: 2px solid #000;">Marque</th>
            <th style="border: 2px solid #000;">Matériel</th>
            <th style="border: 2px solid #000;">Prix (Dt)</th>
            <th style="border: 2px solid #000;">Stock</th>
            <th style="border: 2px solid #000;">Pays</th>
            <th style="border: 2px solid #000;">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($prods as $prod): ?>
            <tr>
                <td style="border: 2px solid #000;">
                    <img src="<?= htmlspecialchars($prod['cimg']) ?>" alt="<?= htmlspecialchars($prod['nom']) ?>"
                         style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                </td>
                <td style="border: 2px solid #000;"><?= htmlspecialchars($prod['nom']) ?></td>
                <td style="border: 2px solid #000;"><?= htmlspecialchars($prod['marque']) ?></td>
                <td style="border: 2px solid #000;"><?= htmlspecialchars($prod['materiel']) ?></td>
                <td style="border: 2px solid #000;"><?= number_format($prod['prix'], 2) ?> Dt</td>
                <td style="border: 2px solid #000;"><?= htmlspecialchars($prod['stock']) ?></td>
                <td style="border: 2px solid #000;"><?= htmlspecialchars($prod['pays']) ?></td>
                <td style="border: 2px solid #000;">
                    <form action="/lezm/admin/supprimerProduit" method="POST">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($prod['id']) ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                    <br>
                    <form action="/lezm/admin/modifierProduit" method="get" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $prod['id'] ?>">
                        <button type="submit" class="btn btn-warning btn-sm">Modifier</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // Recherche dynamique
        document.getElementById('searchBtn').addEventListener('click', function () {
            let searchTerm = document.getElementById('searchInput').value.toLowerCase();
            let rows = document.querySelectorAll('#productsTable tbody tr');

            rows.forEach(function (row) {
                let columns = row.querySelectorAll('td');
                let nom = columns[1].textContent.toLowerCase();
                let marque = columns[2].textContent.toLowerCase();
                let materiel = columns[3].textContent.toLowerCase();
                let pays = columns[6].textContent.toLowerCase();

                if (nom.includes(searchTerm) || marque.includes(searchTerm) || materiel.includes(searchTerm) || pays.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Tri avec flèches et alternance
        const sortDirections = {};
        const table = document.getElementById('productsTable');
        const headers = table.querySelectorAll('th');

        headers.forEach((th, index) => {
            if (index !== 0 && index !== 7) {
                th.addEventListener('click', () => {
                    const tbody = table.querySelector('tbody');
                    const rows = Array.from(tbody.querySelectorAll('tr'));
                    const isNumeric = index === 4 || index === 5;
                    const currentDirection = sortDirections[index] || 'asc';

                    headers.forEach((h, i) => {
                        if (i !== index && i !== 0 && i !== 7) {
                            h.innerHTML = h.textContent;
                            sortDirections[i] = 'asc';
                        }
                    });

                    rows.sort((a, b) => {
                        let valA = a.cells[index].textContent.trim().replace('Dt', '');
                        let valB = b.cells[index].textContent.trim().replace('Dt', '');

                        if (isNumeric) {
                            valA = parseFloat(valA);
                            valB = parseFloat(valB);
                        }

                        if (valA < valB) return currentDirection === 'asc' ? -1 : 1;
                        if (valA > valB) return currentDirection === 'asc' ? 1 : -1;
                        return 0;
                    });

                    rows.forEach(row => tbody.appendChild(row));
                    const arrow = currentDirection === 'asc' ? ' ▲' : ' ▼';
                    th.innerHTML = th.textContent.trim() + arrow;
                    sortDirections[index] = currentDirection === 'asc' ? 'desc' : 'asc';
                });
            }
        });
        function sortByPrice(order) {
    const table = document.getElementById('productsTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    const priceIndex = 4; // index colonne "Prix"

    rows.sort((a, b) => {
        let priceA = parseFloat(a.cells[priceIndex].textContent.replace('Dt', '').trim());
        let priceB = parseFloat(b.cells[priceIndex].textContent.replace('Dt', '').trim());

        return order === 'asc' ? priceA - priceB : priceB - priceA;
    });

    rows.forEach(row => tbody.appendChild(row));
}
    </script>
</div>
</body>
