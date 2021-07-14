<?php
define('DB_DSN_CERBERUS', 'sqlsrv:Server=localhost;Database=cerberus_2002');

//Servidor SQL Server
define('DB_DSN_CERBERUS', 'odbc:DRIVER={SQL Server};SERVER=192.168.0.78;Database=cerberus_2002;charset=iso-8859-1');
define('DB_DSN_ALO_IDEMA', 'odbc:DRIVER={SQL Server};SERVER=192.168.0.78;Database=Alo_Idema;charset=iso-8859-1');

define('DB_DSN_ALO_IDEMA_XAMPP', 'mysql:host=localhost;dbname=Alo_Idema');


define('DB_USER_PARRACHOS', 'visitante_zona_protegida');
define('DB_PWD_PARRACHOS', '$@qu3l4^');

define('DB_USER_ALO_IDEMA_XAMPP', 'user');
define('DB_PWD_ALO_IDEMA_XAMPP', 'user');

define('DB_USER_CERBERUS', 'user_cerberus_2002');
define('DB_PWD_CERBERUS', 'c3rb3rus_2002');

define('DB_USER_CHAT', 'chat');
define('DB_PWD_CHAT', 'ch4t');

define('APP_ENDERECO', '10.3.12.9');

define('DB_DSN_CARBOX', 'mysql:host=192.168.0.22;dbname=carbox');
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