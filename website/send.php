<?php

header('Content-Type: application/json');

// Função para letra minuscula
function fullLower($str){
   // convert to entities
   $subject = htmlentities($str,ENT_QUOTES);
   $pattern = '/&([a-z])(uml|acute|circ';
   $pattern.= '|tilde|ring|elig|grave|slash|horn|cedil|th);/e';
   $replace = "'&'.strtolower('\\1').'\\2'.';'";
   $result = preg_replace($pattern, $replace, $subject);
   // convert from entities back to characters
   $htmltable = get_html_translation_table(HTML_ENTITIES);
   foreach($htmltable as $key => $value) {
      $result = ereg_replace(addslashes($value),$key,$result);
   }
   return(strtolower($result));
}

$filename = $_POST['usuario_foto'];
if( $filename || $filename != "" ){
	list($width, $height) = getimagesize($filename);

	// Resample
	$image_p = imagecreatetruecolor(300, 225);
	$image = imagecreatefromjpeg($filename);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, 300, 225, $width, $height);

	ob_start();
	imagejpeg($image_p, null, 100);
	$image_p = ob_get_clean();
	$foto = /*"data:image/jpg;base64,".*/ base64_encode($image_p);
}else{
	$foto = "/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAA8AAD/4QMqaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjUtYzAyMSA3OS4xNTQ5MTEsIDIwMTMvMTAvMjktMTE6NDc6MTYgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RTc1ODQ4QUM3RjBBMTFFNDlBODRDODIxOTgxOEEzMjYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RTc1ODQ4QUQ3RjBBMTFFNDlBODRDODIxOTgxOEEzMjYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpFNzU4NDhBQTdGMEExMUU0OUE4NEM4MjE5ODE4QTMyNiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpFNzU4NDhBQjdGMEExMUU0OUE4NEM4MjE5ODE4QTMyNiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pv/uAA5BZG9iZQBkwAAAAAH/2wCEAAYEBAQFBAYFBQYJBgUGCQsIBgYI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";
}

$expireMonth = time() + (30 * 24 * 60 * 60);
$dataExpiracao = date('d/m/Y', $expireMonth);
$senha = sha1($_POST['usuario_senha']);
$email = fullLower($_POST['usuario_email']);

$postUrl = '#';
$fields = array(
	"entidade_tipo_id"			=> $_POST['entidade_tipo_id'],
	"entidade_nome"				=> $_POST['entidade_nome'],
	"entidade_numero"			=> $_POST['entidade_numero'],
	"entidade_endereco"			=> $_POST['entidade_endereco'],
	"entidade_bairro"			=> $_POST['entidade_bairro'],
	"entidade_estado"			=> $_POST['estado'],
	"entidade_cidade"			=> $_POST['cidade'],
	"entidade_data_expiracao"	=> $dataExpiracao,
	"usuario_nome"				=> $_POST['usuario_nome'],
	"usuario_email"				=> $email,
	"usuario_senha"				=> $senha,
	"usuario_genero_id"			=> $_POST['usuario_genero_id'],
	"usuario_telefone"			=> $_POST['usuario_telefone'],
	"usuario_foto"				=> $foto
);

$check = array();

foreach ($fields as $key => $value) {
	if( $key == "" || $key == null || $key == "undefined" ){
		array_push($check, $key);
	}
}


if( count($check) != 0 ){
	echo json_encode( array('sucesso'=> false) );
}else{
	//echo json_encode( array('sucesso'=> true) );
	//echo json_encode($fields);

	$cURL = curl_init( $postUrl );
	curl_setopt($cURL, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36');
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($cURL, CURLOPT_POST, true);
	curl_setopt($cURL, CURLOPT_POSTFIELDS, $fields);

	$result = curl_exec($cURL);

	curl_close($cURL);
	
}

$statusRequest = json_decode($result);

if( !$statusRequest ){
	echo json_encode( array('sucesso'=> false) );
}else{
	print_r($result);
	
	/* Envia relatório para o ELI NAHUM */
	include "sendMailCheckout.php";

	/* Envia email para o cliente(Download App) */
	include "thank-you/send.php";

	/* Envia backup dos dados para o site */
	include "send_backup/index.php";

	/* Envia link do boleto para o cliente */
	//include "sendMailBoleto.php";

	//OBS, não pode imprimir nada além do $result, pois a leitura do Json pode ser danificada 
}

?>