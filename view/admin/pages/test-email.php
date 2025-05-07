<?php 
$active_menu = 'email';
include_once __DIR__ . '/../layout.php';
?>

<div class="content" style="margin-left: 260px; padding: 20px;">
    <h1>📧 Vérification de la configuration email</h1>

    <form method="POST" action="/2A27/admin/test-email" class="email-form">
        <div class="form-group">
            <label for="email">Adresse email de vérification</label>
            <input type="email" id="email" name="email" required class="form-control" 
                   placeholder="Entrez l'adresse email pour la vérification">
        </div>

        <button type="submit" class="btn btn-primary">Vérifier la configuration</button>
    </form>
</div>

<style>
    .email-form {
        max-width: 600px;
        margin: 20px 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-control {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }

    .btn-primary {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #45a049;
    }
</style>