<?php
require_once "ProduitC.php";
$active_menu = 'produits';
include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php'; ?>
$prods = ProduitC::getProduits();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Produits</title>

    <!-- Feuilles de style -->
    <link href="http://<?= $_SERVER['HTTP_HOST'] ?>/lezm/view/assets/css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <!-- Scripts JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="/lezm/view/assets/js/script.js" defer></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            padding-top: 20px;
            padding-left: 20px;
        }

        .content-area {
            margin-left: 270px;
            padding: 20px;
            flex: 1;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 2px solid #000;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #343a40;
            color: #fff;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<?php include_once 'C:\xampp\htdocs\lezm\view\admin\partials\sidebar.php'; ?>

<!-- Contenu principal -->
<div class="content-area">
    <h2>Liste des Produits</h2>

    <table id="productsTable">
        <thead>
            <tr>
                <th>Image</th>
                <th>Nom</th>
                <th>Marque</th>
                <th>Matériel</th>
                <th>Prix (Dt)</th>
                <th>Stock</th>
                <th>Pays</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prods as $prod): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($prod['cimg']) ?>" alt="<?= htmlspecialchars($prod['nom']) ?>"></td>
                    <td><?= htmlspecialchars($prod['nom']) ?></td>
                    <td><?= htmlspecialchars($prod['marque']) ?></td>
                    <td><?= htmlspecialchars($prod['materiel']) ?></td>
                    <td><?= number_format($prod['prix'], 2) ?> Dt</td>
                    <td><?= htmlspecialchars($prod['stock']) ?></td>
                    <td><?= htmlspecialchars($prod['pays']) ?></td>
                    <td>
                        <form action="/lezm/supprimerProduit" method="POST">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($prod['id']) ?>">
                            <button type="submit" class="btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Script DataTables -->
<script>
$(document).ready(function() {
    $('#productsTable').DataTable({
        "language": {
            "search": "Rechercher :",
            "lengthMenu": "Afficher _MENU_ entrées",
            "info": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
            "paginate": {
                "first": "Premier",
                "last": "Dernier",
                "next": "Suivant",
                "previous": "Précédent"
            }
        }
    });
});
</script>

</body>
</html>
