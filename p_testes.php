<?php
	/* pagina de testes */
	
	// chamar o arquivo de configuração que contém a nossa classe de setup
	require_once ("config.php");
	// criar instância para acessar a classe Setup já criando a conexão com os meus dados
	// parametros do construtor ($host, $dbname, $user, $password, $table)
	$bd = new Setup("localhost","aluno","root","","tb_aluno");

	// fazer uma busca no pdo ------------------------------------------------------------------
	// variável id que sei que tenho no meu banco para fazer o teste
	$id = 67; 
	// buscar uma id no banco, chamando o método da minha classe "search"
	$bd->search($id) retorna $sql->rowCount() e se for maior que 0, então ele já tem algum registro com esse id
	if ($bd->search($id) > 0){
		// diz que tem e mostra quantos
		echo "já tem ".$bd->search($id);
	} else echo "não tem";

	// variável que vou colocar como parâmetro da minha chamada de função
	$nome = "rodrigo barboza";
	// inserir no banco com pdo -----------------------------------------------------------------
	// quero inserir $nome no meu banco na coluna de nome_aluno
	// o método inserir retorna um valor booleano, se for verdadeiro a operação foi um sucesso
	
	if ($bd->insert($nome)) echo "<br/>adicionado com sucesso!";
	else echo "<br/>ocorreu algum erro.";

	//$bd->searchValue();
	if (($v = $bd->update($id, "", "")) != "void") echo "<br/>atualizado ".$v;
	else if ($v == "void") echo "<br/>nada alterado";
	else echo "<br/>erro";

	if ($bd->delete($id)) echo "<br/>deletado com sucesso. ";
	else echo "</br>usuario não encontrado.";
?>