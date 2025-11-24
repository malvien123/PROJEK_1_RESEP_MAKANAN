<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Aplikasi Resep</title>
     <link rel="stylesheet" href="../asset/style_login.css">
</head>

</style>
<body>
  <div class="login-container">
        
        

        <h2>Login</h2>
        
        <form action="../controller/c_proses_login.php" method="POST">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div style="margin-top: 15px;">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" style="margin-top: 20px;">
                Login
            </button>
             <p> Belum punya akun? <a href="v_register.php">Daftar di sini.</a></p>
        </form>
    </div>
</body>
</html>