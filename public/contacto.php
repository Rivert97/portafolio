<?php
$data = array();
$flgerror = True;
if (!empty($_POST['name'])
	&& !empty($_POST['mailphone'])
	&& !empty($_POST['message'])
	&& !empty($_POST['g-recaptcha-response'])
	&& verify_captcha()) {
	$name = htmlspecialchars(strip_tags($_POST['name']));
	$mailphone = htmlspecialchars(strip_tags($_POST['mailphone']));
	$message = htmlspecialchars(strip_tags($_POST['message']));
	require_once '../conf/db.php';
	try {
		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		);
		$dsn = "mysql:host=localhost;dbname=$dbname";
		$db = new PDO($dsn, $user, $password, $options);
		$stmt = $db->prepare("INSERT INTO messages (name, mailphone, message) VALUES (:name, :mailphone, :message)");
		$stmt->bindParam(":name", $name);
		$stmt->bindParam(":mailphone", $mailphone);
		$stmt->bindParam(":message", $message);
		if ($stmt->execute()) {
			$flgerror = False;
		}
	} catch (PDOException $e){
		error_log($e->getMessage());
	}

}
if ($flgerror) {
	$html_mensaje = "<h2>Hubo un error al enviar el mensaje</h2>" .
					"<p>Por favor, int&eacute;ntalo m&aacute;s tarde.</p>";
} else {
	$html_mensaje = "<h2>¡Gracias por tu mensaje!</h2>" .
					"<p>Me pondré en contacto lo más rápido posible.</br>No olvides revisar tu bandeja de Spam.</p>";
}

function verify_captcha() {
	require_once '../conf/captcha.php';
	$url = "https://www.google.com/recaptcha/api/siteverify";
	$data = array('secret' => $secret_key, 'response' => $_POST['g-recaptcha-response']);

	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE) {
		return False;
	} else {
		$response = json_decode($result);
		if ($response['success']) {
			return True;
		} else {
			return False;
		}
	}
}
?>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@100;400;700&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" href="css/main.css"/>

		<title>Contacto</title>
	</head>
	<body class="body-contacto">
		<header>
			<div class="barra contacto">
				<div class="logo">
					<span>Roberto Garc&iacute;a</span>
				</div>
				<div class="navegacion">
					<div>
						<a href="/portafolio/">Inicio</a>
					</div>
					<div>
						<a href="/portafolio/#proyectos">Proyectos</a>
					</div>
					<div>
						<a href="/portafolio/#contacto">Contacto</a>
					</div>
					<div>
						<a href="/portafolio/">Blog</a>
					</div>
				</div>
			</div>
		</header>

		<main>
			<div class="contenedor">
				<div class="encabezado">
					<?php echo $html_mensaje;?>
			</div>
		</main>
	</body>
</html>
