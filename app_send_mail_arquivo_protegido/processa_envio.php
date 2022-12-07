<?php

use PHPMailer\PHPMailer\PHPMailer; //apelidando o class para não entrar em conflito (namespace)
use PHPMailer\PHPMailer\Exception; //qualquer coisa pesquisar direito para server o USE (namespace)

//SuperGlobal = POST
    //print_r($_POST);


    class Mensagem { //criando class 
        //atributos da class
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        
        //atributo public contendo duas chaves um com codigo_status value null e outra descricao_status vazio
        public $status = ['codigo_status' => null, 'descricao_status' => ''];

        //metodos da class
        public function __set($atributo, $valor) { //metodo magico set, com os paramatro atributo e valor, para atribuir aqui primeiro é o nome do atributo e depois o valor do atributo
            $this->$atributo = $valor; //atributo recebe valor(value)
        }

        public function __get($atributo) { //metodo magico get, com o parametro atributo que sera recuperado no metodo __set
            return $this->$atributo; //retorna o proprio atributo(value)
        }

        public function mensagemValida() { //metodo criado para valida as mensagens ver se estão validar os email etc

            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) { //condições logica para verificar se algum dos atributos estão vazio
                return false; //se estiver vazio retornar false(falso)
            }

            return true; //caso esteja todos preenchido retornar true(verdadeiro)
        }
    }

    $mensagem = new Mensagem(); //criando instacia(new obj) para class

    $mensagem->__set('para', $_POST['para']); //criando instacia(new obj) para class, recuperando os dados da superglobal post e preencher os atributos, o primeiro indice é para setar o valor, o segundo indice para vem dos campos name do HTML
    $mensagem->__set('assunto', $_POST['assunto']); 
    $mensagem->__set('mensagem', $_POST['mensagem']); 

    if(!$mensagem->mensagemValida()) { //se o retorno do metodo for diferente de $mensagem entrar no IF, caso haja um retorno false(falso) dar echo de mensagem nao é valida
        echo 'Mensagem não é válida';
        header('Location: index.php'); //caso o usuario tente acessar o processa_envio sem fazer o login
    }

    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Qual é o servidor SMTP
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'romenildosilva@gmail.com';                     //SMTP login
        $mail->Password   = 'paaatchxxjiulldg';                               //SMTP senha
        $mail->SMTPSecure = 'tls';                                   //segurança
        $mail->Port       = 587;                                    

        //Remetente
        $mail->setFrom('romenildosilva@gmail.com', 'Romenildo Silva Rementente');    //setando o remetente
        $mail->addAddress($mensagem->__get('para'));          //setando destinatario do e-mail, sendo recuperando do HTML pelo metodo magico __get
        //$mail->addAddress('ellen@example.com');               //destinatario opcional
        //$mail->addReplyTo('info@example.com', 'Information');     //adiciona contato padrao terceria pessoa
        //$mail->addCC('cc@example.com');                          //adiciona destinatario em copia
        //$mail->addBCC('bcc@example.com');                        //adiciona destinatario em copia oculta

        //Anexos
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Conteudo
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto');  //assunto do e-maildo e-mail, sendo recuperando do HTML pelo metodo magico __get
        $mail->Body = $mensagem->__get('mensagem');  //mensagem do e-mail, sendo recuperando do HTML pelo metodo magico __get
        $mail->AltBody = 'É necessario um client que suporte HTML';

        $mail->send();

        $mensagem->status['codigo_status'] = 1; //definindo valor para o array do atributo public status, primeira chave
        $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso'; //definindo valor para o array do atributo public status segunda chave

    } catch (Exception $e) { //capturando erro
        $mensagem->status['codigo_status'] = 2; //definindo valor para o array do atributo public status, primeria chave
        $mensagem->status['descricao_status'] = 'Não foi possivel enviar este e-mail! Por favor tente novamente mais tarde. Detalhes do Erro: ' . $mail->ErrorInfo; //definindo valor para o array do atributo public status, segunda chave

        //alguma lógica que armazene o erro posterior análise por parte do programador, depois com o banco de dados
        
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
        </div>

        <div class="row">
            <div class="col-md-12">

                <? if($mensagem->status['codigo_status'] == 1) { ?> <!-- entrando no codigo php recuperando a instancia $mensagem atributo status que é um array e o codigo_status, sem for o codigo 1 tomar essa decisão -->

                    <div class="container">
                        <h1 class="display-4 text-success">Sucesso</h1>
                        <p><?= $mensagem->status['descricao_status'] ?></p> <!--utilizando a tag de impressão do php para imprimir o resultado no html-->
                        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                    </div>
                
                <? } ?> <!-- fechando o codigo php a } -->

                <? if($mensagem->status['codigo_status'] == 2) { ?> <!-- entrando no codigo php recuperando a instancia $mensagem atributo status que é um array e o codigo_status, sem for o codigo 2 tomar essa decisão (ERROR)-->

                    <div class="container">
                        <h1 class="display-4 text-danger">Ops!</h1>
                        <p><?= $mensagem->status['descricao_status'] ?></p> <!--utilizando a tag de impressão do php para imprimir o resultado no html-->
                        <a href="index.php" class="btn btn-sucess btn-lg mt-5 text-white">Voltar</a>
                    </div>
                
                <? } ?> <!-- fechando o codigo php a }-->
            </div>
        </div>
    </body>    
</html>