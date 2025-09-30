<?php
class ClassController {
    public function class() {
        $title = "Stuarz — Find and Join us in Stuarz";
        $description = "Blablabla blebleble blublublu";

        // Tentukan file view utama
        $content = '../app/views/pages/class.php';
        
        include '../app/views/layouts/dLayout.php';
    }
}