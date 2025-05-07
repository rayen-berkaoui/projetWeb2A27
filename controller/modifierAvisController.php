<?php
require_once __DIR__ . '/../model/AvisModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['avis_id'])) {
    try {
        $avisModel = new AvisModel();
        $result = $avisModel->updateAvis($_POST['avis_id'], [
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'email' => $_POST['email'],
            'contenu' => $_POST['contenu'],
            'note' => $_POST['note']
        ]);

        if ($result) {
            header('Location: ../view/back/modifier_avis.php?id=' . $_POST['avis_id'] . '&success=1');
        } else {
            header('Location: ../view/back/modifier_avis.php?id=' . $_POST['avis_id'] . '&error=Échec de la modification');
        }
    } catch (Exception $e) {
        header('Location: ../view/back/modifier_avis.php?id=' . $_POST['avis_id'] . '&error=' . urlencode($e->getMessage()));
    }
    exit;
}