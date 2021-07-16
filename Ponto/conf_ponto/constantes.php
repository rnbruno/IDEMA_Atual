<?php
//define('DB_DSN_CERBERUS', 'sqlsrv:Server=localhost;Database=cerberus_2002');
define('DB_DSN_CERBERUS', 'odbc:DRIVER={SQL Server};SERVER=192.168.0.78;Database=cerberus_2002;charset=iso-8859-1');
define('DB_DSN_CERBERUS_TESTE', 'odbc:DRIVER={SQL Server};SERVER=localhost;Database=sislia_simulador2;charset=iso-8859-1');

define('DB_USER_PARRACHOS', 'visitante_zona_protegida');
define('DB_PWD_PARRACHOS', '$@qu3l4^');

define('DB_USER_CERBERUS', 'user_cerberus_2002');
define('DB_PWD_CERBERUS', 'c3rb3rus_2002');

define('DB_DSN_PONTO', 'mysql:host=192.168.0.7;dbname=ponto_copy');
define('DB_USER_PONTO', 'ponto_copy');
define('DB_PWD_PONTO', 'meuponto@123');

define('DB_DSN_PONTO_C', 'mysql:host=192.168.0.7;dbname=carbox');
define('DB_USER_PONTO_C', 'ponto_copy');
define('DB_PWD_PONTO_C', 'meuponto@123');

define('DB_USER_CHAT', 'chat');
define('DB_PWD_CHAT', 'ch4t');

define('APP_ENDERECO', '10.3.12.9');

define('DB_DSN_CARBOX', 'mysql:host=192.168.0.7;dbname=carbox');
define('DB_USER_CARBOX', 'carbox');
define('DB_PWD_CARBOX', 'D@t@B@s3_C4RB0X');

define('DB_DSN_REDESIM', 'mysql:host=192.168.0.7;dbname=redesim_integracao');
define('DB_USER_REDESIM', 'integracao');
define('DB_PWD_REDESIM', 'int3@int3#');

define('SRC_BOOTSTRAP_CSS', '../../apis/bootstrap-3.3.1-dist/css/bootstrap.min.css');
define('SRC_JQUERY', '../js/jquery/jquery.js');
define('SRC_BOOTSTRAP_JS', '../../apis/bootstrap-3.3.1-dist/js/bootstrap.min.js');
define('MSG_ERRO_INESPERADO', 'Ocorreu um erro inesperado.');
?>