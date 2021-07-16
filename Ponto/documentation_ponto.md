#	Discovery PONTO

![image-20210716100413677](C:\Users\07624990440\AppData\Roaming\Typora\typora-user-images\image-20210716100413677.png)

####	*inserir option no select Justificava

A tabela justificativa_ajuste DB[ponto] armazena os options;



#	Cadastro Afastamento



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

