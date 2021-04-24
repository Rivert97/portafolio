<?php
class Message {
	private $name;
	private $mailphone;
	private $message;

	private $conn;
	private $table_name = "messages";

	function __construct($conn)
	{
		$this->conn = $conn;
	}

	public function set_name($name)
	{
		$this->name = htmlspecialchars(strip_tags($name));
	}
	
	public function set_mailphone($mailphone)
	{
		$this->mailphone = htmlspecialchars(strip_tags($mailphone));
	}

	public function set_message($message)
	{
		$this->message = htmlspecialchars(strip_tags($message));
	}

	public function save()
	{
		$retval = False;
		$query = "INSERT INTO "	. $this->table_name . " (" .
			"name, ".
			"mailphone, " .
			"message" .
			") VALUES (" .
			":name, " .
			":mailphone, " .
			":message)";
		try {
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(":name", $this->name);
			$stmt->bindParam(":mailphone", $this->mailphone);
			$stmt->bindParam(":message", $this->message);
			$stmt->execute();
			$retval = True;
		} catch (PDOException $exception) {
			error_log($exception->getMessage());
		}
		return $retval;

	}
}
?>
