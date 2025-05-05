<?php
require_once __DIR__ . '/../model/Evenement.php';

class EvenementController {
    private $evenementModel;

    public function __construct() {
        $this->evenementModel = new Evenement();
    }

    public function index() {
        $evenements = $this->evenementModel->getAll();
        require_once __DIR__ . '/../view/evenements/list.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $date_evenement = $_POST['date_evenement'] ?? '';
            $lieu = $_POST['lieu'] ?? '';

            if ($this->evenementModel->add($titre, $description, $date_evenement, $lieu)) {
                header('Location: /evenements');
                exit;
            }
        }
        require_once __DIR__ . '/../view/evenements/create.php';
    }

    public function edit($id) {
        $evenement = $this->evenementModel->getById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $date_evenement = $_POST['date_evenement'] ?? '';
            $lieu = $_POST['lieu'] ?? '';

            if ($this->evenementModel->update($id, $titre, $description, $date_evenement, $lieu)) {
                header('Location: /evenements');
                exit;
            }
        }
        require_once __DIR__ . '/../view/evenements/edit.php';
    }

    public function delete($id) {
        if ($this->evenementModel->delete($id)) {
            header('Location: /evenements');
            exit;
        }
    }
} 