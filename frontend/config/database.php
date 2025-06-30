<?php
class Database
{
    protected $dbh;
    protected $error;

    public function __construct()
    {
        // Thông tin kết nối (có thể di chuyển vào config/database.php)
        $host = 'db';       // Thay bằng 'localhost' nếu chạy local
        $user = 'root';
        $pass = 'root';
        $dbname = 'php-project';
        $port = '3306';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";

        try {
            $this->dbh = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Đặt mặc định fetch là ASSOCIATIVE array
            ]);
            $this->error = null; // Xóa lỗi nếu kết nối thành công
        } catch (PDOException $e) {
            $this->error = "Database Connection Failed: " . $e->getMessage();
            error_log($this->error); // Ghi log lỗi
            $this->dbh = null; // Đặt $dbh là null nếu lỗi
        }
    }

    // Phương thức để lấy đối tượng PDO
    public function getDbh()
    {
        return $this->dbh;
    }

    // Phương thức để kiểm tra lỗi
    public function getError()
    {
        return $this->error;
    }
}