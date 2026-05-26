<?php
class HomeController {
    public function index(): void {
        if (authUser()) {
            redirect('/dashboard');
        } else {
            require_once __DIR__ . '/../views/landing.php';
        }
    }
}
