<?php
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

class sendEmail
{
public function getAPI(){
try{
    $curl = curl_init();

curl_setopt_array($curl, [
CURLOPT_URL => "https://liturgia.up.railway.app/v2/",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_HTTPHEADER => [
"Accept: */*",
"User-Agent: Thunder Client (https://www.thunderclient.com)",
],
]);

$response = curl_exec($curl);

curl_close($curl);

return $response;
}catch(Exception $e){
echo "Erro ao obter dados da API: " . $e->getMessage();
}
}

public function SendEmail($apiResponse){
$mail = new PHPMailer\PHPMailer\PHPMailer(true);
$data = json_decode($apiResponse, true);
try{

$mailer = $_ENV['MAIL_MAILER'];
$scheme = $_ENV['MAIL_SCHEME'];
$host = $_ENV['MAIL_HOST'];
$port = $_ENV['MAIL_PORT'];
$username = $_ENV['MAIL_USERNAME'];
$password = $_ENV['MAIL_PASSWORD'];
$fromAddress = $_ENV['MAIL_FROM_ADDRESS'];
$fromName = $_ENV['MAIL_FROM_NAME'];

$mail->isSMTP();
$mail->Host = $host;
$mail->SMTPAuth = true;
$mail->Username = $username;
$mail->Password = $password;
$mail->SMTPSecure = $scheme;
$mail->Port = $port;    
$mail->setFrom($fromAddress, $fromName);
$mail->addAddress('matheusgaia33@gmail.com','Matheus Gaia');

$mail->isHTML(true);
$mail->Subject = 'Liturgia do dia ' . date('d/m/Y');
$salmo = $data['leituras']['salmo'][0] ?? [];
$referencia = htmlspecialchars($salmo['referencia'] ?? 'Sem referencia', ENT_QUOTES, 'UTF-8');
$refrao = htmlspecialchars($salmo['refrao'] ?? 'Sem refrao', ENT_QUOTES, 'UTF-8');
$textoSalmo = nl2br(htmlspecialchars($salmo['texto'] ?? 'Salmo nao encontrado', ENT_QUOTES, 'UTF-8'));

$mail->Body = "<h2>Salmo do dia</h2><p><strong>{$referencia}</strong></p><p><em>{$refrao}</em></p><p>{$textoSalmo}</p>";
$mail->send();
echo 'Email enviado com sucesso!';

}catch (Exception $e){
echo "Erro ao enviar email: {$mail->ErrorInfo}";
}
}
}

$sendEmail = new sendEmail();
$apiResponse = $sendEmail->getAPI();
$sendEmail->SendEmail($apiResponse);
