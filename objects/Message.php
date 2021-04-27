<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once "../vendor/autoload.php";

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
        require_once '../conf/email.php';
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 587;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->SMTPAuth = True;
		$mail->Username = "rgarciag.contacto@gmail.com";
		$mail->Password = "Huevonivia97";
		$mail->SetFrom("rgarciag.contacto@gmail.com", "Roberto Garcia");
		$mail->AddAddress("betogarcia97@live.com.mx", "Roberto Garcia");
		$mail->Subject = "Test is Test Email sent via Gmail SMTP Server using PHP Mailer";
		$mail->IsHTML(true);
		$content = "<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b>";
		$mail->MsgHTML($content);
		if (!$mail->Send()) {
			echo "Error while sending Email.";
			var_dump($mail);
		} else {
			echo "Email sent successfully";
		}
	}
}
?>
