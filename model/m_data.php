<?php


include_once 'm_koneksi.php'; 

abstract class m_data {
    
    public $koneksi; 
    
    public function __construct() {
        // Membuat object dari class m_koneksi
        $this->koneksi = new m_koneksi(); 
    }
    
    abstract protected function getAll(); 
    
    abstract protected function getById($id);
}























