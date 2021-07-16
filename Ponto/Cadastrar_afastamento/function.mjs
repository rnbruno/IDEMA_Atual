/**
 * recebe por parâmetro uma quantidade indefinida de números e efetua a soma dos
 * mesmos.
 *
 * @param {data,data} params
 * @returns {msg[]} valor da data parâmetros
 */

export function data_compare(data_i,data_f){
		msg = new Array();
	if(data_i>data_f){
			msg["alert"]="A data inicial e maior que a final";
			msg["status"]=1;
		
	}else{
		msg["alert"]="Data Correta";
		msg["status"]=0;
		
	}
	return msg;
	
}