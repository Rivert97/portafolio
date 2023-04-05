<?php
class DB {
	private $conn;

	public function get_connection()
    {
        require_once PATH_RAIZ . '/conf/db.php';
		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		);
        try{
			$dns = "mysql:host=localhost;dbname=$dbname";
			$this->conn = new PDO($dns, $user, $password, $options);
        }catch(PDOException $exception){
			$this->conn = null;
        }
		return $this->conn;
    }
}
?>
