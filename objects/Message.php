<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once PATH_RAIZ . "/vendor/autoload.php";

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

	public function send_email() {
        require_once PATH_RAIZ . '/conf/mail.php';
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		$mail->SMTPAuth = true;
		$mail->Username = $username;
		$mail->Password = $password;
		$mail->SetFrom($username, $fromName);
		$mail->AddAddress($notificationsEmail, $notificationsName);
		$mail->Subject = "Mensaje en rgarciag.com";
		$mail->IsHTML(true);
		$content = "<b>Mensaje recibido</b><ul><li>Nombre: " . $this->name . "</li><li>Coreo/Telefono: " . $this->mailphone . "</li><li>Mensaje: " . $this->message . "</li></ul>";
		$mail->MsgHTML($content);
		if (!$mail->Send()) {
			error_log("Se intentó enviar un mensaje pero fallo");
		}
	}
}
?>
