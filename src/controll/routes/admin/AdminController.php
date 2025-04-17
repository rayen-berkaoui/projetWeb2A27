<?php
class AdminController {
    protected function render($view, $data = []) {
        extract($data);
        $content = view($view, $data);
        require view('admin/layout');
    }
    
    protected function model($model) {
        require_once __DIR__ . '/../../../domain/' . $model . '.php';
        return new $model();
    }
}