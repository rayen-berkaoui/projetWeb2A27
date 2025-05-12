<?php
class AdminController {
    protected function render($view, $data = []) {
        function view($path) {
            return __DIR__ . '/../../../views/' . $path . '.php';
            require $this->view('admin/layout');
            extract($data);
            $content = view($view, $data);
            require view('admin/layout');
        }
    }
    
    protected function model($model) {
        require_once __DIR__ . '/../../../domain/' . $model . '.php';
        return new $model();
    }
}