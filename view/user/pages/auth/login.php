<?php
$pageTitle = "Connexion";
?>

<div class="login-container">
    <h1>Connexion</h1>
    <form action="<?= base_url('auth/login') ?>" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Se connecter</button>
    </form>
</div>