#	Discovery PONTO

![image-20210716100413677](C:\Users\07624990440\AppData\Roaming\Typora\typora-user-images\image-20210716100413677.png)

####	*inserir option no select Justificava

A tabela justificativa_ajuste DB[ponto] armazena os options;



#	Cadastro Afastamento

##	CERBERUS

Criando menu PONTO in table MENU and subitem Cadastrar Afastamento

######	menu_id,titulo,script,requer_id,entidade,menu_pai_id



* PONTO 
  * Cadastrar Afastamento; ponto/cadastrar_afastamento/cadastrar_afastamento.php	

##	PHP e AJAX

Tratar post data para posterior inserção no BD

Recebe post in arquive ajax.recebe_afastamento.php

explode("/",safepost('data'));

print_r={

​	[0]= dia;

​	[1]=mes;

​	[2]=ano

}

formato necessário

*`YYYY-MM-DD`*

##	Tabelas MySQL Criadas



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

