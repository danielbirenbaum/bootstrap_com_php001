<?php
//PHP FEITO POR DANIEL BIRENBAUM E CARLOS FREDERICO
//USO DA BIBLIOTECA PHP MAILER
//VALIDAÇÃO DE DADOS DE CYBERSEGURANÇA

//Bibliotecas:
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



/*CÓDIGO DE VALIDAÇÃO */ 
function tratamentoPost($content){ /*Validação Primitiva dos dados*/
    $content = trim($content); /*Retirando espaços em branco do ínicio e do final */
    $content = stripslashes($content); /*Retirando barras */
    $content = htmlspecialchars($content); /*Retirando caracteres especiais */ 
    return $content;
}
/* Erros possíveis */
$erro_nome = '';
$erro_email = '';
$erro_mensagem = '';

/*Início da validação */ 
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    
    //VALIDANDO O POST NOME
    if (empty($_POST['name'])){
        $erro_nome = "É necessário colocar um nome.";   
    }else{
        $nome = tratamentoPost($_POST['name']); /*Atribuindo o dado nome o tratamento e salvando em váriavel */
        if(!preg_match("/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/", $nome)){
            $erro_nome = "É apenas permitido letras e espaços em branco";
        }
    }
   
    // VALIDANDO O POST EMAIL
    if (!empty($_POST['email'])){
        $email = tratamentoPost($_POST['email']);  /*Atribuindo o dado email o tratamento e salvando em váriavel */
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $erro_email = "Este e-mail é inválido";
        } 
    }else{
        $erro_email = "É necessário informar um e-mail";
    }

    //VALIDANDO O POST MENSAGEM
    if (!empty($_POST['message'])){
        $mensagem = tratamentoPost($_POST['message']);  /*Atribuindo o dado mensagem o tratamento e salvando em váriavel */
        
    }else{
        $erro_mensagem = "É necessário informar alguma mensagem";
    }


}

/* CÓDIGO DE ENVIO DE E-MAIL */ 

if (($erro_nome == '') && ($erro_email == '') && ($erro_mensagem == '')){
    require 'vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = '';                     
        $mail->Password   = '';                               
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
        $mail->Port       = 465;                                    

        //Recipients
        $mail->setFrom('', 'F1 Mania');
        $mail->addAddress("$email", "$nome");    
        $mail->addReplyTo('', 'Information');
        $mail->isHTML(true);                                  
        $mail->Subject = "Cadastro em F1 Mania!";
        $body = "Você enviou um feedback para F1 Mania, seguem aqui seus dados: 
        Seu nome: $nome 
        Seu email: $email

        ESSE E-MAIL NÃO É DE UMA EMPRESA VERÍDICA, APENAS UM TRABALHO ESCOLAR.
        ";
        $mail->Body  = $body;
        $mail->send();
    } catch (Exception $e) {
        echo "<h1>ERROR</h1>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content />
        <meta name="author" content />
        <title>F1 Mania | Cadastro</title>

        <link rel="icon" type="image/x-icon" href="assets/favicon.png" />
        <!-- Ícones do bootstrap-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- CSS-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body class="d-flex flex-column">
        <main class="flex-shrink-0">
            <!-- Nav-->
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container px-5">
                    <a class="navbar-brand" href="index.html">F1 Mania</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="about.html">Sobre</a></li>
                            <li class="nav-item"><a class="nav-link" href="contact.php">Contatos</a></li>
                            <li class="nav-item"><a class="nav-link" href="pricing.html">Premium</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdownBlog" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Blog</a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownBlog">
                                    <li><a class="dropdown-item" href="blog-home.html">Blog Home</a></li>
                                    <li><a class="dropdown-item" href="blog-post.html">Blog Post</a></li>
                                </ul>
                            </li>
                    </div>
                </div>
            </nav>      
                <?php  
                /*Mostrando a página de obrigado caso dados sejam válidos*/
                if (($erro_nome == '') && ($erro_email == '') && ($erro_mensagem == '')){ 
                    echo require('obrigado.php');    
                }else{
                    echo require('erro.php');
                }
                ?>

    </div>
</html>
