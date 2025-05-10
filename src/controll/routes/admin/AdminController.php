<?php
class AdminController {
    protected function render($view, $data = []) {
<<<<<<< HEAD
        function view($path) {
            return __DIR__ . '/../../../views/' . $path . '.php';
            require $this->view('admin/layout');
            extract($data);
            $content = view($view, $data);
            require view('admin/layout');
        }
=======
        $viewPath = __DIR__ . '/../../../views/' . $view . '.php';
        extract($data);
        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        require __DIR__ . '/../../../views/admin/layout.php';
>>>>>>> GestionForums
    }
    
    protected function model($model) {
        require_once __DIR__ . '/../../../domain/' . $model . '.php';
        return new $model();
    }
}