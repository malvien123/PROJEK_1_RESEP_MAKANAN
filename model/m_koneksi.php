<?php
class m_koneksi {
    private $host = "localhost";
    private $username = "root";
    private $pass = "";
    private $db = "resep_makanan"; // NAMA DATABASE ANDA
    
    public $koneksi; // Properti publik untuk menampung objek koneksi mysqli

    function __construct() {
        // Menggunakan koneksi berorientasi objek (new mysqli)
        $this->koneksi = new mysqli($this->host, $this->username, $this->pass, $this->db);

        // Periksa error koneksi
        if ($this->koneksi->connect_error) {
            // Menghentikan eksekusi dan menampilkan pesan error yang spesifik
            die("Koneksi database gagal: " . $this->koneksi->connect_error);
        }
        
        // Hapus: return $this->koneksi; -> Constructor tidak perlu mengembalikan nilai
    }

    // Method penghancur koneksi (opsional)
    public function __destruct() {
        if ($this->koneksi && $this->koneksi->ping()) {
            $this->koneksi->close();
        }
    }
}

