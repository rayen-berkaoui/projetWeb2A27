<?php
class PageController {
    public function showBlankPage() {
        $data = [
            'content' => 'This is dynamically loaded content!',
        ];
        
        // Include the view
        include_once __DIR__ . '/../../../view/blank_template.php';
    }
}

// Usage (call this from your router or entry point)
// (new PageController())->showBlankPage();