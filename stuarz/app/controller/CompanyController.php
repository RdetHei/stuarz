<?php
class CompanyController {
    public function company() {
        $title = "Stuarz — Find and Join us in Stuarz";
        $description = "Blablabla blebleble blublublu";

        // Tentukan file view utama
        $content = '../app/views/pages/company.php';
        
        include '../app/views/layouts/layout.php';
    }
}