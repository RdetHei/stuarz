<?php
class CompanyController {
    public function company() {
        $title = "Stuarz — Find and Join us in Stuarz";
        $description = "Blablabla blebleble blublublu";

        // Tentukan file view utama
        $content = '../view/landing/page/company.php';
        
        include '../view/layout.php';
    }
}