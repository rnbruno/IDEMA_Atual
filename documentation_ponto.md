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

`INSERT INTO log_cadastro_afastamento (data_log,user_afastado, evento, tipo_afastamento,data_faixa) select CURRENT_TIMESTAMP, user_afastado,'rotina_afastamento_entrada',tipo_afastamento,data_inicio from evento_afastamento where evento_id in (SELECT evento_id from evento_afastamento where starting_ending=0 and data_inicio<CURRENT_TIMESTAMP and data_final>CURRENT_TIMESTAMP)`

Log criado de 00:00:00

a) data_log -> o momento atual do registro da rotina;
b) user_afastado -> usuario de cadastro, porém nulo devido a rotina ser automática;
c) evento -> descreve o tipo de rotina (rotina_afastamento_entrada e rotina_afastamento_saida);
d) Tipo de afastamento -> resgata o tipo de afastamento da tabela Evento Afastamento que representa o afastamento lançado no sistema;
e) data_faixa -> a descricao da faixa de horarios inicio e fim do lançamento, porém na tabela de log apenas a data da rotina é capturada.

``INSERT INTO log_cadastro_afastamento (data_log,user_afastado, evento, tipo_afastamento,data_faixa) select CURRENT_TIMESTAMP, user_afastado,'rotina_afastamento_saida',tipo_afastamento,data_final from evento_afastamento where evento_id in (SELECT evento_id from evento_afastamento where starting_ending=1 and data_inicio<CURRENT_TIMESTAMP and data_final<CURRENT_TIMESTAMP)``

A distinção entre as duas rotians.
1) Na primeira existe o movimento de entrada para a rotina. A coluna starting_ending=0 aponta que não entrou. A data_inicio é menor que a data atual na rotina e a data_final é menor que a data da rotina;
2) Na segunda existe o movimento para saída da rotina. A coluna starting_ending=1 aponta que o usuário ainda não saiu da rotina. A data_inicio e a data_final é menor que a data final.

select users que se enquadram na condição
* data_inicial
* data_final

cadastrados 

####	Update table DADO_FUNCIONAL
###Rotina_afastamento_entrada
``UPDATE dado_funcional SET `bloqueado` = 1 where id_pessoa in (select user_afastado from evento_afastamento where evento_id in (SELECT evento_id from evento_afastamento where starting_ending=0 and excluido=0 and data_inicio<CURRENT_TIMESTAMP and data_final>CURRENT_TIMESTAMP))``

start 00:01:00

1) SELECT evento_id from evento_afastamento where starting_ending=0 and excluido=0 and data_inicio<CURRENT_TIMESTAMP and data_final>CURRENT_TIMESTAMP. Seleciona os ids do evento que ainda não entraram na rotina (starting_ending=0, data_inicio é menor que a data atual e a data_final é maior que a data atual;
2) select user_afastado from evento_afastamento where evento_id in [...]. Seleciona os ids do usuário registrados nos id_evento.
3) Atualiza a tabela dado_funcional no campo bloqueado=1 dos usuário listados na subquery.

Resumo: * update bloqueado=1 
###Rotina_afastamento_saida

``UPDATE dado_funcional SET `bloqueado` = 0 where id_pessoa in (select user_afastado from evento_afastamento where evento_id in (SELECT evento_id from evento_afastamento where starting_ending=1 and excluido=0 and data_inicio<CURRENT_TIMESTAMP and data_final<CURRENT_TIMESTAMP))``
1) Procedimento semelhante apenas alterando a coluna starting_ending.
Resumo: * update bloqueado=0

Depois de alterar a coluna bloqueado vamos adentrar na tabela afastamento para gerenciar os afastamento agendados, alterando o status.
####	Update table EVENTO_AFASTAMENTO
###Rotina_evento_afastamento_entrada
update evento_afastamento set starting_ending=1 where starting_ending=0 and data_inicio<CURRENT_TIMESTAMP and data_final>CURRENT_TIMESTAMP
1)Selecionar todos os evento_afastamento que tenham a coluna startin_ending=0 e as faixas de datas;

###Rotina_evento_afastamento_saida
update evento_afastamento set starting_ending=2 where starting_ending=1 and data_inicio<CURRENT_TIMESTAMP and data_final<CURRENT_TIMESTAMP

1) Comando semelhante.
start 00:02:00

### Configuração das rotinas

Verificar as rotinas
SELECT * FROM INFORMATION_SCHEMA.events

## Demanda de criação de link para baixar arquivo anexado
### Reconhecendo as tabelas
#### Tabela Abono
`nr_abono`, `id_pessoa`, `matricula`, `nr_vinculo`, `data_abono`, `periodo_abono`, `justificativa`, `id_pessoa_certificacao`, `data_hora_certificacao`, `indicador_certificado`, `id_pessoa_registro`, `data_hora_registro`, `ip_registro`, `justificativa_certificacao`, `nr_justificativa`, `indicador_excluido`
#### Tabela Ajuste
#### Tabela Arquivo_ajuste
A tabela referece aos dados tanto dos Ajustes e do Abonos.
São armazenados dois id distintos, se um é registrado o outro é Null.

`
SELECT * from (
                                    SELECT a.nr_ajuste,matricula,data_hora_ponto,entrada_saida, id_pessoa_registro,data_hora_registro, justificativa,data_hora_certificacao,aa.cod_repositorio, aa.descricao_arquivo,
                                    p.nome_pessoa,indicador_certificado,texto_justificativa,a.nr_justificativa,indicador_excluido,
                                            year(data_hora_ponto) as ano, month(data_hora_ponto) as mes, day(data_hora_ponto) as dia 
                                            FROM `ajuste` a left join pessoa p on p.id_pessoa=a.id_pessoa_certificacao 
                                            left join justificativa_ajuste ja on ja.nr_justificativa=a.nr_justificativa
   											left join arquivo_ajuste aa on a.nr_ajuste=aa.nr_ajuste
                                                WHERE a.id_pessoa= 1 and a.nr_vinculo= 1) 
                                            as b where ano= 2021 and mes= 9 order by dia
`


`
SELECT * from (
                                    SELECT a.nr_abono,matricula,data_abono,periodo_abono DIV 60 as hora_abono, id_pessoa_registro,data_hora_registro, justificativa,data_hora_certificacao,aa.cod_repositorio, aa.descricao_arquivo,
                                    p.nome_pessoa,indicador_certificado,texto_justificativa,a.nr_justificativa,indicador_excluido,
                                            year(data_abono) as ano, month(data_abono) as mes, day(data_abono) as dia 
                                            FROM `abono` a left join pessoa p on p.id_pessoa=a.id_pessoa_certificacao 
                                            left join justificativa_ajuste ja on ja.nr_justificativa=a.nr_justificativa
   											left join arquivo_ajuste aa on a.nr_abono=aa.nr_abono
                                                WHERE a.id_pessoa= 1 and a.nr_vinculo= 1) 
                                            as b where ano= 2021 and mes= 9 order by dia
                                            
`   
                                            
                                         
