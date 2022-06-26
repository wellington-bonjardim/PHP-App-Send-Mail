<?php

    require "./bibliotecas/PHPMailer/Exception.php";
    require "./bibliotecas/PHPMailer/OAuth.php";
    require "./bibliotecas/PHPMailer/PHPMailer.php";
    require "./bibliotecas/PHPMailer/POP3.php"; //RECEBIMENTO DE EMAIL
    require "./bibliotecas/PHPMailer/SMTP.php"; // ENVIO DE EMAIL

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // PRIMEIRO VAMOS CRIAR UM OBJETO DEFINIDO COMO Mensagem

    class Mensagem {
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        public $status = [
            'codigo_status' => null,
            'descricao_status' => ''
        ];

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

        public function mensagemValida() {
            //lógica aplicada para verificar se a mensagem é válida
            /* Neste caso, vamos apenas verificar se os atributos estão preenchidos */
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
                return false;
            }
            return true;
        }
    }
    
    $mensagem = new Mensagem();

    /*Agora, iremos recuperar os dados da superglobal $_POST e fazer o nosso objeto MENSAGEM recebê-los em seus respectivos lugares a partir da variável que contém a instância do objeto:
        $mensagem -> __set('variável do objeto que vai receber o valor da superglobal', $_POST['name do input']);
    */

    $mensagem -> __set('para', $_POST['para']);
    $mensagem -> __set('assunto', $_POST['assunto']);
    $mensagem -> __set('mensagem', $_POST['mensagem']);

    //tomando decisões com base no respectivo retorno:
    if(!$mensagem -> mensagemValida()) {
        echo 'MENSAGEM INVÁLIDA';
        header('Location: index.html');
    }

    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp-mail.outlook.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'phptestewb@outlook.com';                     //SMTP username
        $mail->Password   = 'W123456&*';                               //SMTP password
        $mail->SMTPSecure = 'tls';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('phptestewb@outlook.com', 'Bonjardim');
        $mail->addAddress($mensagem -> __get('para'));     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem -> __get('assunto');
        $mail->Body    = $mensagem -> __get('mensagem');
        $mail->AltBody = 'É necessário utilizar um client que suporte HTML para ter acesso total ao conteúdo dessa mensagem.';

        $mail->send();

        $mensagem -> status['codigo_status'] = 1;
        $mensagem -> status['descricao_status'] = 'E-mail enviado com SUCESSO!!';

    } catch (Exception $e) {

        $mensagem -> status['codigo_status'] = 2;
        $mensagem -> status['descricao_status'] = "Não foi possível enviar este e-mail! Por favor, tente mais tarde. Detalhes do erro: {$mail->ErrorInfo}";
    }
?>

<html>
    <head>
        <meta charset="utf-8" />
        <title>App Mail Send</title>
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	</head>

    <body>
        <div class="container">

            <div class="py-3 text-center">
                    <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
                    <h2>Send Mail</h2>
                    <p class="lead">Seu app de envio de e-mails particular!</p>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <?php 
                        if($mensagem->status['codigo_status'] == 1) {
                    ?>

                            <div class="container">

                                <h1 class="d-block mx-auto text-center display-4 text-success">Sucesso</h1>

                                <p class="text-center font-weight-bold"><?= $mensagem->status['descricao_status'] ?></p>

                                <div class="row">
                                    <div class="col-md-4 mx-auto">
                                    <a href="index.php" class="d-block btn btn-success btn-lg text-white ">Voltar</a>
                                    </div>
                                </div>                                
                                
                            </div>

                    <?php } ?>

                    <?php 
                        if($mensagem->status['codigo_status'] == 2) {
                    ?>

                             <div class="container">
                                
                                <h1 class="d-block mx-auto text-center display-4 text-danger">OPS!</h1>

                                <p class="text-center font-weight-bold"><?= $mensagem->status['descricao_status'] ?></p>

                                <div class="row">
                                    <div class="col-md-4 mx-auto">
                                    <a href="index.php" class="d-block btn btn-danger btn-lg text-white ">Voltar</a>
                                    </div>
                                </div>

                            </div>

                    <?php } ?>

                </div>
            </div>
        </div>
    </body>

</html>
