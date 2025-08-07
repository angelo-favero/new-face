<?php
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Configurações iniciais
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Configurações de email - Altere para seu endereço
$to = "angelo-favero@hotmail.com"; // Email onde você deseja receber as mensagens
$site_name = "New Face";

// Inicializa variáveis
$form_type = "Contato";
$redirect_page = "contato.html";
$success_message = "Mensagem enviada com sucesso! Entraremos em contato em breve.";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug
    error_log("POST recebido: " . print_r($_POST, true));
    
    // Para teste no XAMPP, vamos registrar os dados recebidos em um log
    $log_dir = __DIR__ . '/logs/';
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    file_put_contents($log_dir . 'form_log.txt', date('Y-m-d H:i:s') . " - " . print_r($_POST, true) . "\n\n", FILE_APPEND);
    
    // Identifica o tipo de formulário enviado (basead  o na URL de referência)
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    if (strpos($referer, 'silver.html') !== false) {
        $form_type = "Pacote Silver";
        $redirect_page = "silver.html";
    } elseif (strpos($referer, 'gold.html') !== false) {
        $form_type = "Pacote Gold";
        $redirect_page = "gold.html";
    } elseif (strpos($referer, 'platinum.html') !== false) {
        $form_type = "Pacote Platinum";
        $redirect_page = "platinum.html";
    }
    
    // Sanitiza e captura dados do formulário
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $whatsapp = filter_input(INPUT_POST, 'whatsapp', FILTER_SANITIZE_STRING);
    $empresa = filter_input(INPUT_POST, 'nome-empresa', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    // Campos específicos do formulário de contato geral
    $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);
    
    // Captura interesses (checkboxes) - se presentes
    $interesses = [];
    $checkbox_fields = [
        'posts' => 'Posts',
        'stories' => 'Stories',
        'identidade' => 'Identidade Visual',
        'uxui' => 'UX/UI',
        'portifolio' => 'Portifólio',
        'ecommerce' => 'E-commerce',
        'edicao' => 'Edição de Vídeos',
        'animado' => 'Vídeos Animados'
    ];
    
    foreach ($checkbox_fields as $field => $label) {
        if (isset($_POST[$field])) {
            $interesses[] = $label;
        }
    }
    
    // Validação básica
    $errors = [];
    
    if (empty($nome)) {
        $errors[] = "Nome é obrigatório";
    }
    
    if (empty($whatsapp)) {
        $errors[] = "WhatsApp é obrigatório";
    }
    
    // Se não houver erros, envia o email
    if (empty($errors)) {
        // Assunto do email
        $subject = "[{$site_name}] Novo contato - {$form_type}: {$nome}";
        
        // Corpo do email em HTML
        $message = "<html><body>";
        $message .= "<h2>Novo contato via site - {$form_type}</h2>";
        $message .= "<p><strong>Nome:</strong> {$nome}</p>";
        $message .= "<p><strong>WhatsApp:</strong> {$whatsapp}</p>";
        
        if (!empty($empresa)) {
            $message .= "<p><strong>Empresa:</strong> {$empresa}</p>";
        }
        
        if (!empty($email)) {
            $message .= "<p><strong>Email:</strong> {$email}</p>";
        }
        
        // Adiciona interesses se existirem
        if (!empty($interesses)) {
            $message .= "<p><strong>Interesses:</strong></p><ul>";
            foreach ($interesses as $interesse) {
                $message .= "<li>{$interesse}</li>";
            }
            $message .= "</ul>";
        }
        
        // Adiciona mensagem se for do formulário de contato
        if (!empty($mensagem)) {
            $message .= "<p><strong>Mensagem:</strong><br>" . nl2br($mensagem) . "</p>";
        }
        
        $message .= "</body></html>";
        
// Criar nova instância do PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // Configurações do servidor
            $mail->Timeout = 60; // Timeout maior
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'destructionofgamers@gmail.com';
            $mail->Password = 'mbjlukygststyenr'; // Sem espaços
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;                            // Porta TCP para conexão
            
            // Habilitar debug para ver os erros detalhados
            $mail->SMTPDebug = 0;                                // 0 = off, 1 = mensagens cliente, 2 = cliente e servidor
            
            // Destinatários
            $mail->setFrom('destructionofgamers@gmail.com', $site_name);
            $mail->addAddress($to);                               // Adicionar destinatário
            
            if (!empty($email)) {
                $mail->addReplyTo($email, $nome);                 // Responder para
            }
            
            // Conteúdo
            $mail->isHTML(true);                                  // Formato do email é HTML
            $mail->CharSet = 'UTF-8';                             // Codificação UTF-8 para acentos
            $mail->Subject = "[{$site_name}] Novo contato - {$form_type}: {$nome}";
            $mail->Body    = $message;
            $mail->AltBody = strip_tags(str_replace("<br>", "\n", $message));
            
            // Registra tentativa no log
            file_put_contents('email_log.txt', date('Y-m-d H:i:s') . " - Tentativa de envio via PHPMailer para: {$to}\n", FILE_APPEND);
            
            // Envia o email
            $mail->send();
            
            // Registra sucesso no log
            file_put_contents('email_log.txt', date('Y-m-d H:i:s') . " - Email enviado com sucesso via PHPMailer\n\n", FILE_APPEND);
            
            // Para teste e debug, mostra mensagem antes de redirecionar
            echo "<h1>Email enviado com sucesso!</h1>";
            echo "<p>Clique <a href='{$redirect_page}?status=success'>aqui</a> para voltar.</p>";
            
            // Depois de testar com sucesso, descomente a linha abaixo e remova o echo acima
            header("Location: {$redirect_page}?status=success");
            exit;
        } catch (Exception $e) {
            // Registra erro no log
            file_put_contents('email_log.txt', date('Y-m-d H:i:s') . " - Erro PHPMailer: {$mail->ErrorInfo}\n\n", FILE_APPEND);
            
            // Para teste e debug, mostra o erro
            echo "<h1>Erro ao enviar email</h1>";
            echo "<p>Mensagem: {$mail->ErrorInfo}</p>";
            echo "<p>Clique <a href='{$redirect_page}?status=error'>aqui</a> para voltar.</p>";
            
            // Depois de resolver os problemas, descomente a linha abaixo e remova o echo acima
            // header("Location: {$redirect_page}?status=error&msg=" . urlencode($mail->ErrorInfo));
            // exit;
        }
    } else {
        // Há erros de validação
        $error_string = implode(", ", $errors);
        header("Location: {$redirect_page}?status=error&msg=" . urlencode($error_string));
        exit;
    }
} else {
    error_log("Nenhum POST recebido");
    // Acesso direto ao script não é permitido
    header("Location: index.html");
    exit;
}
?>