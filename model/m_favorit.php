<?php

class m_favorit {
    private $db; 

    public function __construct($db_connection) {
       
        $this->db = $db_connection;
    }

    
    public function is_favorited($user_id, $resep_id) {
        $count = 0;
        
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM favorit WHERE id_user = ? AND id_resep = ?");
        
        if (!$stmt) {
             error_log("Prepare failed in is_favorited: " . $this->db->error);
             return false;
        }
        
        $stmt->bind_param("ii", $user_id, $resep_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count > 0;
    }

    // TAMBAH FAVORIT
    public function add_favorite($user_id, $resep_id) {
        
        $stmt = $this->db->prepare("INSERT IGNORE INTO favorit (id_user, id_resep) VALUES (?, ?)");
        
        if (!$stmt) {
             error_log("Prepare failed in add_favorite: " . $this->db->error);
             return false;
        }

        $stmt->bind_param("ii", $user_id, $resep_id);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    // HAPUS
    public function remove_favorite($user_id, $resep_id) {
        $stmt = $this->db->prepare("DELETE FROM favorit WHERE id_user = ? AND id_resep = ?");
        
        if (!$stmt) {
             error_log("Prepare failed in remove_favorite: " . $this->db->error);
             return false;
        }

        $stmt->bind_param("ii", $user_id, $resep_id);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    public function get_user_favorites($user_id) {
        $sql = "
            SELECT 
                r.id_resep, r.nama_menu, r.gambar, r.deskripsi, u.username AS nama_pengunggah 
            FROM 
                favorit f 
            JOIN 
                resep r ON f.id_resep = r.id_resep 
            LEFT JOIN 
                user u ON r.id_user = u.id_user
            WHERE 
                f.id_user = ? 
            ORDER BY 
                r.id_resep DESC"; 

        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
             error_log("Prepare failed in get_user_favorites: " . $this->db->error);
             return [];
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $resep_favorit = [];
        
        while ($data = $result->fetch_object()) {
            $resep_favorit[] = $data;
        }
        
        $stmt->close();
        
        return $resep_favorit;
    }
}