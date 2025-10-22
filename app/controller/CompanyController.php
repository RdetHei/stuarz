<?php
class CompanyController {
    public function company() {
        $title = "Stuarz — Find and Join us in Stuarz";
        $description = "Blablabla blebleble blublublu";

        // Tentukan file view utama
        $content = dirname(__DIR__) . '/views/pages/organization/company.php';
        
        include dirname(__DIR__) . '/views/layouts/layout.php';
    }
}