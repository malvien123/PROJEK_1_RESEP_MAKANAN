<?php
class m_koneksi {
    private $host = "localhost";
    private $username = "root";
    private $pass = "";
    private $db = "resep_makanan"; // NAMA DATABASE 
    
    public $koneksi; 

    function __construct() {
        // Menggunakan koneksi 
        $this->koneksi = new mysqli($this->host, $this->username, $this->pass, $this->db);

        
        if ($this->koneksi->connect_error) {
            // Menghentikan eksekusi dan menampilkan pesan error yang spesifik
            die("Koneksi database gagal: " . $this->koneksi->connect_error);
        }
        
       
    }

    public function __destruct() {
        if ($this->koneksi && $this->koneksi->ping()) {
            $this->koneksi->close();
        }
    }
}

