<?php
class AdminController {
    protected function render($view, $data = []) {
        $viewPath = __DIR__ . '/../../../views/' . $view . '.php';
        extract($data);
        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        require __DIR__ . '/../../../views/admin/layout.php';
    }
    
    protected function model($model) {
        require_once __DIR__ . '/../../../domain/' . $model . '.php';
        return new $model();
    }
}