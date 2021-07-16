<?php
//Lista de requisitos

//Criar menu Cerberus 
/*
192.168.0.78
Ponto->
*/
log_cadastro_afastamento
  
apontar_usuario
  bloqueado;

//Criando ROTINA MySQL
CREATE EVENT IF NOT EXISTS _nome_

ON SCHEDULE EVERY 6 HOUR

STARTS CURRENT_TIMESTAMP

DO

INSERT INTO `evento_afastamento`(`user_domain`, `user_afastado`, `data_inicio`, `data_final`, `tipo_afastamento`, `starting_ending`) VALUES (12,23,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,4,0)


/*
CREATE EVENT IF NOT EXISTS event_3
ON SCHEDULE EVERY 2 SECOND

START CURRENT_TIMESTAMP

ENDS CURRENT_TIMESTAMP + INTERVAL 20 SECOND

DO
INSERT INTO `evento_afastamento`(`user_domain`, `user_afastado`, `data_inicio`, `data_final`, `tipo_afastamento`, `starting_ending`) VALUES (12,23,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,4,0)
*/
  
  /* Atualize situation pessoa in dado_funcinal / bloqueado=1 
UPDATE dado_funcional SET `bloqueado` = 1 where id_pessoa in (select user_afastado from evento_afastamento where evento_int in (SELECT evento_int from evento_afastamento where starting_ending=0 and data_inicio<CURRENT_TIMESTAMP and data_final>CURRENT_TIMESTAMP));
*/
select user_afastado from evento_afastamento where evento_int in (SELECT evento_int from evento_afastamento where starting_ending=0 and data_inicio<CURRENT_TIMESTAMP and data_final>CURRENT_TIMESTAMP)


/*insert log action
INSERT into log_cadastro_afastamento (`data_log`, `user`, `tipo_afastamento`, `user_afastado`, `evento`) 
VALUES
(CURRENT_TIMESTAMP,"rotina afastamento",*/
?>
