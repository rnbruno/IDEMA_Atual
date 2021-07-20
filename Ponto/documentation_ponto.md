#	Discovery PONTO



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



| evento_id | user_domain | user_afastado | data_inicio | data_final | tipo_afastamento | starting_ending |
| --------- | ----------- | ------------- | ----------- | ---------- | ---------------- | --------------- |
|           |             |               |             |            |                  |                 |

* <b>evento_id</b>	int incremental;
* <b>user_domain</b> int usuário logado do Cerberus;
* <b>user_afastado</b> int usuário do Ponto;
* <b>data_inicio</b> datetime;
* <b>data_final</b> datetime;
* <b>tipo_afastamento</b> - int - os tipos de afastamentos gerados pela table <i>tipo_afastamento</i>;
* <b>starting_ending</b> int que aponta o fluxo do afastamento.



####	table start_end_fluxo 

| id_sef | nome_sef                                                     |
| ------ | ------------------------------------------------------------ |
| 0      | na fila para entrar no fluxo de afastamento (usuário do ponto agendado para status bloqueado = 1) |
| 1      | na fila para sair do fluxo de afastamento (ao esgotar a data final o usuário tem campo bloqueado =0) |
| 2      | saiu do fluxo de afastamento                                 |

id_sef é um int que referencia o nome

nome_sef nomes das entradas e saídas do status do fluxo de afastamento

###	Rotina de afastamento

1. Rotinas são acionadas EVERY 6 HOUR;

2. Imprescindível a ordem da rotina:
   1. Insert LOG_CADASTRO_AFASTAMENTO;
   2. Update DADO_FUNCIONAL;
   3. Update EVENTO_AFASTAMENTO.



#	

####	Insert LOG_CADASTRO_AFASTAMENTO

Log criado de 00:00:00

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
