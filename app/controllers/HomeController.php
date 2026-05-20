<?php
class HomeController {
    public function index(): void { authUser() ? redirect('/dashboard') : redirect('/login'); }
}
