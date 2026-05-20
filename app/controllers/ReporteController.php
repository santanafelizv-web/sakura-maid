<?php
class ReporteController {
    public function index(): void {
        requireLogin();
        require __DIR__.'/../views/shared/reportes.php';
    }
}
