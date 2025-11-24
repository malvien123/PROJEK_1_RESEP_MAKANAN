 <?php
include_once 'm_data.php'; 
class m_resep extends m_data {
    
    public function ambilSemuaResepDenganPengunggah() {
        return $this->getAll(); 
    }

    public function ambilResepById($id_resep) {
        return $this->getById($id_resep); 
    }
    

    
    protected function getAll() {
        $conn = $this->koneksi->koneksi; 

        $sql = "
            SELECT r.id_resep, r.nama_menu, r.gambar, r.deskripsi, r.tipe_gambar, r.id_user, u.username AS nama_pengunggah 
            FROM resep r
            LEFT JOIN user u ON r.id_user = u.id_user 
            ORDER BY r.id_resep DESC
        ";
        
        $query = $conn->query($sql); 
        $result = [];

        if (!$query) {
            // Jika query gagal (misalnya, koneksi terputus atau nama tabel salah)
            error_log("SQL Error in m_resep->getAll(): " . $conn->error);
            // Tambahkan die() sementara di sini jika Anda ingin melihat error langsung di layar
            // die("Query Gagal: " . $conn->error); 
            return [];
        }
        
        if ($query->num_rows > 0) {
            while ($data = $query->fetch_object()) {
                $result[] = $data;
            }
        }
        
        
        return $result;
    }

    protected function getById($id_resep) {
        $conn = $this->koneksi->koneksi;

        $sql = "SELECT id_resep, nama_menu, deskripsi, gambar, tipe_gambar, id_user FROM resep WHERE id_resep = ?";
        
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            error_log("Prepare failed in m_resep->getById: " . $conn->error);
            return false;
        }
        
        $stmt->bind_param("i", $id_resep);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_object();
        }
        return false;
    }
}











