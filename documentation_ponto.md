#	Discovery PONTO no Cerberus



####	*inserir option no select Justificava

A tabela justificativa_ajuste DB[ponto] armazena os options;

##	Como registrar!?

1. Peça ao setor de desenvolvimento liberação do item no menu do Cerberus;
2. Após liberação, o menu Ponto com o submenu cadastro de afastamento será apresentado;
3. Selecione:
 3.1 Tipo de Afastamento;
 3.2 Data Inicial;
 3.3 Data Final;
 3.4 Usuários da lista de ponto.
4. Submeta;
 4.1 Caso ocorra algum erro msg em cor vermelha apresentada;
 4.2 Caso ocorra sucesso mensagem em cor verde é apresentada.

#	Cadastro Afastamento

##	CERBERUS

Criando menu PONTO in table MENU and subitem Cadastrar Afastamento

######	menu_id,titulo,script,requer_id,entidade,menu_pai_id



* PONTO 
  * Cadastrar Afastamento; ponto/cadastrar_afastamento/cadastrar_afastamento.php;

diretório ponto/ fica externo ao cerberus para evitar

##	PHP e AJAX

Tratar post data para posterior inserção no BD

Recebe post in arquive 

####	ajax.recebe_afastamento.php

explode("/",safepost('data'));

print_r={

​	[0]= dia;

​	[1]=mes;

​	[2]=ano

}

formato necessário

*`YYYY-MM-DD`*

####	ajax_verificar_afastamento_cadastrado.php



* SELECT user_afastado FROM `evento_afastamento` WHERE user_afastado=@user_afastado and starting_ending=0 and tipo_afastamento=@tipo_afastamento

  

##	Tabelas MySQL Criadas

####	table log_cadastro_afastamento

* log_int;
* data_log;
* user;
* tipo_afastamento;
* data_rg;
* user_afastado;
* evento.

####	table evento_afastamento

* evento_id;
* user_domain;
* user_afastado;
* data_inicio;
* data_final;
* tipo_afastamento;
* starting_ending.



###	Rotina de afastamento

1. Rotinas são acionadas EVERY 6 HOUR;

2. Imprescindível a ordem da rotina:
   1. Insert LOG_CADASTRO_AFASTAMENTO;
   2. Update DADO_FUNCIONAL;
   3. Update EVENTO_AFASTAMENTO.



#	

####	Insert LOG_CADASTRO_AFASTAMENTO

//INSERT INTO log_cadastro_afastamento (data_log,user_afastado, evento, tipo_afastamento,data_faixa) select CURRENT_TIMESTAMP, //user_afastado,'rotina_afastamento_entrada',tipo_afastamento,data_inicio from evento_afastamento where evento_id in (SELECT evento_id from evento_afastamento where //starting_ending=0 and data_inicio<CURRENT_TIMESTAMP and data_final>CURRENT_TIMESTAMP)
//Log criado de 00:00:00

a) data_log -> o momento atual do registro da rotina;
b)user_afastado -> usuario de cadastro, porém nulo devido a rotina ser automática;
c)evento -> descreve o tipo de rotina (rotina_afastamento_entrada e rotina_afastamento_saida);
d) Tipo de afastamento -> resgata o tipo de afastamento da tabela Evento Afastamento que representa o afastamento lançado no sistema;
e) data_faixa -> a descricao da faixa de horarios inicio e fim do lançamento, porém na tabela de log apenas a data da rotina é capturada.

select users que se enquadram na condição

* data_inicial
* data_final

cadastrados 

####	Update table DADO_FUNCIONAL

start 00:01:00

com select user = condição

alterar

* update bloqueado=1 
* update bloqueado=0

####	Update table EVENTO_AFASTAMENTO

start 00:02:00

Por fim alterar o flag na tabela evento afastamento

