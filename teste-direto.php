<?php
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>Teste de conexão SMTP</h2>";

// Criar nova instância do PHPMailer
$mail = new PHPMailer(true);

// Configurações do servidor
$mail->SMTPDebug = 3; // Nível máximo de debug
$mail->Debugoutput = 'html';
$mail->Timeout = 60; // Timeout maior
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'destructionofgamers@gmail.com';
$mail->Password = 'mbjlukygststyenr'; // Sem espaços
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

try {
    echo "<p>Tentando conectar ao servidor SMTP...</p>";
    
    // Apenas tenta conectar sem enviar email
    if ($mail->smtpConnect()) {
        echo "<p style='color:green'>Conexão SMTP bem-sucedida!</p>";
        $mail->smtpClose();
        
        // Agora tenta enviar um email simples
        echo "<p>Tentando enviar email de teste...</p>";
        
        // Destinatários
        $mail->setFrom('destructionofgamers@gmail.com', 'Teste XAMPP');
        $mail->addAddress('angelo-favero@hotmail.com');
        
        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = 'Teste de Email via XAMPP - ' . date('H:i:s');
        $mail->Body = '<h1>Teste de Email</h1><p>Este é um email de teste enviado às ' . date('H:i:s') . '</p>';
        
        if ($mail->send()) {
            echo "<p style='color:green'>Email enviado com sucesso!</p>";
        } else {
            echo "<p style='color:red'>Falha ao enviar: " . $mail->ErrorInfo . "</p>";
        }
    } else {
        echo "<p style='color:red'>Falha na conexão SMTP.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>Erro: " . $mail->ErrorInfo . "</p>";
}
?>