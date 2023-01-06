<?php

require_once './app/Email.php';

// *********************

echo '<pre>';
print_r($_POST);
echo '</pre>';

// LOGIN
$usuario    = '';
$senha      = '';

// ENDEREÇOS
$destinos   = [];

$cadastros  = json_decode(file_get_contents('./emails/emails.json'));
foreach ($cadastros as $cadastro) {
    array_push($destinos, $cadastro->email);
};

// CONTEÚDO
$titulo     = @$_POST['title'];
$corpo      = @$_POST['body'];
$corpoAlt   = @$_POST['altBody'];
$anexos     = @$_FILES['attachments'];

// CONFIGURAÇÕES
$alvosVisiveis  = @$_POST['showAddresses'] ? true : false; 
$autorEmail     = @$_POST['fromEmail'];
$autorNome      = @$_POST['fromName'];
$respondaEmail  = @$_POST['replyEmail'];
$respondaNome   = @$_POST['replyName'];

// DEPOIS DE ENVIAR
$emSucesso  = function ($adds) {
    echo "Email enviado com sucesso: <b>" . implode(', ', $adds) . "</b>";
};
$emFalha    = function ($err) {
    echo "Não foi possível concluir esta ação: <b>" . $err . "</b>";
};

// *********************

$email = new Email;

$email->login($usuario, $senha);
$email->setFrom($autorEmail, $autorNome);
$email->setReply($respondaEmail, $respondaNome);
$email->send(
    $destinos,
    $titulo,
    $corpo,
    $corpoAlt,
    $alvosVisiveis,
    $anexos,
    $emSucesso,
    $emFalha
);


// Com um laço é possível enviar emails individuais
// Dessa forma é possível criar configurações dinâmicas, para cada destinário
// Neste exemplo, apenas o título e o conteúdo são dinâmicos
// Porém todas as configurações anteriores podem ser feitas dentro do loop

// foreach ($destinos as $add) {
//     $titulo     = "Aviso para $add";
//     $body       = "Olá, $add, este é um email individual.";

//     $email->send(
//         $add,
//         $titulo,
//         $body,
//         $conteudoAlternativo,
//         $alvosVisiveis,
//         $anexos,
//         $emSucesso,
//         $emFalha
//     );
// }
