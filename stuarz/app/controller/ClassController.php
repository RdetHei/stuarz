<?php
class ClassController {
    public function class() {
        $title = "Stuarz — Find and Join us in Stuarz";
        $description = "Blablabla blebleble blublublu";

        // Tentukan file view utama
        $content = '../view/landing/page/class.php';
        
        include '../view/dLayout.php';
    }
}