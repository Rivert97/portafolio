<?php
include "../objects/DB.php";
include "../objects/Message.php";

$data = array();
$flgok = False;
if (!empty($_POST['name'])
	&& !empty($_POST['mailphone'])
	&& !empty($_POST['message'])
	&& !empty($_POST['g-recaptcha-response'])
	&& verify_captcha()) {
	$db = new DB();
	$conn = $db->get_connection();
	if ($conn != null) {
		$message = new Message($conn);
		$message->set_name($_POST['name']);
		$message->set_mailphone($_POST['mailphone']);
		$message->set_message($_POST['message']);
		$flgok = $message->save();
		$message->send_email();
	}
}

if ($flgok) {
	$html_mensaje = "<h2>¡Gracias por tu mensaje!</h2>" .
					"<p>Me pondré en contacto lo más rápido posible.</br>No olvides revisar tu bandeja de Spam.</p>";
} else {
	$html_mensaje = "<h2>Hubo un error al enviar el mensaje</h2>" .
					"<p>Por favor, int&eacute;ntalo m&aacute;s tarde.</br>O env&iacute;ame un mensaje a contacto@rgarciag.com</p>";
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
	error_log("JSON: " . $result);
	if ($result === FALSE) {
		return False;
	} else {
		$response = json_decode($result);
		if ($response->success) {
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
						<a href="/">Inicio</a>
					</div>
					<div>
						<a href="/#proyectos">Proyectos</a>
					</div>
					<div>
						<a href="/#contacto">Contacto</a>
					</div>
					<div>
						<a href="/">Blog</a>
					</div>
				</div>
			</div>
		</header>

		<main>
			<div class="contenedor">
				<div class="encabezado">
					<?php echo $html_mensaje;?>
				</div>
			</div>
		</main>
		<footer class="site-footer">
			<div class="copy">
				<p>&#169; Copyright 2021 - Roberto Garc&iacute;a</p>
				<p class="disclaimer"><a href="aviso.html">Aviso de Privacidad</a></p>
			</div>
		</footer>
	</body>
</html>
