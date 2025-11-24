<?php
class m_user {
    private $db; 

    // Constructor menerima koneksi database dari Controller 
    public function __construct($db_connection) {
        // Pastikan objek koneksi mysqli diberikan
        if (!$db_connection instanceof mysqli) {
            throw new Exception("Koneksi database tidak valid diberikan ke m_user.");
        }
        $this->db = $db_connection;
    }

    //fungsi login
    public function verify_login($username, $password_input) 
    {
       
        $sql = "SELECT id_user, username, password, role FROM user WHERE username = ?";
        
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
             error_log("Prepare failed in verify_login: " . $this->db->error);
             return false;
        }

        
        $stmt->bind_param("s", $username); 
        
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        $stmt->close();
        
        if ($user_data) {
            // Verifikasi Password (menggunakan password_verify)
            if (password_verify($password_input, $user_data['password'])) { 
                // Mengembalikan data sebagai ARRAY ASOSIATIF (untuk c_proses_login.php)
                return [
                    'id_user' => $user_data['id_user'],
                    'username' => $user_data['username'],
                    'role' => $user_data['role']
                ]; 
            }
        }
        
        return false; // Login gagal
    }
    
   
    public function tampil_data() 
    {
        $sql = "SELECT * FROM user";
        $result = $this->db->query($sql);
        
        $data = []; 
        if ($result && $result->num_rows > 0) {
             // Mengembalikan data sebagai objek (fetch_object) untuk v_tampil_user.php
             while ($row = $result->fetch_object()) {
                 $data[] = $row;
             }
        } 
        return $data; 
    }

   
    public function tampil_data_by_id($id_user) 
    {
        $sql = "SELECT * FROM user WHERE id_user = ?";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return null;

        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_object();
        $stmt->close();
        
        return $data;
    }

    // fungsi tambah
    public function tambah_data($username, $password_hash, $role) 
    {
        $sql = "INSERT INTO user(username, password, role) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return false;

        $stmt->bind_param("sss", $username, $password_hash, $role);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    } 

    // fungsi update
    public function ubah_data($id_user, $username, $password_hash = null) 
    {
        if ($password_hash) {
            // Jika password diubah
            $sql = "UPDATE user SET username = ?, password = ? WHERE id_user = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssi", $username, $password_hash, $id_user);
        } else {
            // Jika hanya username yang diubah (Hapus password dari SET)
            $sql = "UPDATE user SET username = ? WHERE id_user = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("si", $username, $id_user);
        }
        
        if (!$stmt) return false;
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    // fungsi hapus
    public function hapus_data($id_user) 
    {
        $sql = "DELETE FROM user WHERE id_user = ?";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return false;

        $stmt->bind_param("i", $id_user);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
}
