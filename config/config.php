<?php

use Slim\App;

return static function (App $app) {
    // CONFIG OF DATABASE
    putenv('DBDRIVE=mysql');
    putenv('DBHOST=localhost');
    putenv('DBNAME=gsoBackHomologacao');
    putenv('DBUSER=root');
    putenv('DBPASS=170286P@ra');

    // /CONFIG LOGIN JWT
    putenv('JWT_KEY_SECRET=Ronyc0d3');

    // / CONFIG PHPMAILER
    putenv('HOST_MAIL=smtp.gmail.com');
    putenv('PORT_MAIL=587');
    putenv('USER_MAIL=espaco.educar.palmas@gmail.com');
    putenv('PASS_MAIL=eyjouwmrxvvjxllb');
    putenv('FROM_NAME_MAIL=Espaço Educar');
    putenv('FROM_EMAIL_MAIL=espaco.educar.palmas@gmail.com');
    putenv('SUBJET_MAIL=Email de solicitação para recuperação de senha.');
    putenv('ALT_BODY=Email solicitação recuperação de senha.Caso o remetente não use HTML');

    // DIRETORIES
    putenv('ROOT='.dirname(getcwd(), 1));
    putenv('DIR_IMG='.getenv('ROOT').'/app/storage/photos');
    putenv("ISS={$_SERVER['DOCUMENT_ROOT']}");
};
