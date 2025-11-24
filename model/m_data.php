<?php
// model/m_data.php

include_once 'm_koneksi.php'; 

/**
 * Class Abstrak mData (Model Data Dasar)
 * Berfungsi sebagai parent class yang menyediakan koneksi dan menetapkan 
 * method dasar (getAll, getById) untuk semua model di aplikasi.
 */
abstract class m_data {
    
    // PERBAIKAN: Ubah dari 'protected' menjadi 'public'
    // Ini memungkinkan Controller untuk mengakses koneksi yang sudah dibuat.
    public $koneksi; 
    
    public function __construct() {
        // Membuat object dari class m_koneksi
        $this->koneksi = new m_koneksi(); 
    }
    
    abstract protected function getAll(); 
    
    abstract protected function getById($id);
}
// Setelah perubahan ini, baris $dbConnection = $resep_model->koneksi->koneksi;
// di c_resep.php akan berhasil dijalankan!






















