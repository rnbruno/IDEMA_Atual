<!--#include file="../conf/conecta_banco.asp"-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<%

	Dim ativar_desativar,mode_a,i
	
	tipo = Request("tipo")
	ativar_desativar = Request("ativar_desativar")
	usuario_ids = split(ativar_desativar,",")
	
	for i=LBound(usuario_ids) to UBound(usuario_ids)
		
		sql_update = "update usuario set excluido = "&tipo&" where usuario_id='"&trim(usuario_ids(i))&"'"

		conn.execute(sql_update)
		
		data = Year(now) &"-"& Month(now) &"-"& Day(now) &" "&  Hour(now) &":"& Minute(now) &":"& Second(now)
		
		sql_insert = "insert into log_ativacao_usuario (usuario_id, usuario_gerenciador_id, data_operacao, tipo_operacao) values ('"&trim(usuario_ids(i))&"', '"&session("usuario_id")&"', '"&data&"', "&tipo&")"
		
		'response.write(sql_insert)
		
		conn.execute(sql_insert)
		
		'Response.Write sql & "<br>"
	next
%>

<script language="javascript" type="text/javascript">
	alert("Atualização de estado de usuário(s) feita com sucesso!");
	document.location.href = "frm_ativar_desativar_usuarios.asp";
</script>
</head>
<body>
</body>
</html>