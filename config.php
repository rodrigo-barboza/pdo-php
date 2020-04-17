<?php
	/* PARA FINS DE ESTUDO E REVISÃO */
/*
	SERVIDOR: XAMPP
	PHP: 7.2
	ESTRUTURA DO BANCO MYSQL
	BANCO  = aluno
	TABELA = tb_aluno [
						id_aluno: primary, int[11], auto_incremento;
						nome_aluno: var_char[150];
						email_aluno: var_char[150];
					  ]
	AUTOR: RODRIGO BARBOZA
	CRIADO: 17/04/2020 
*/

class Setup {
	// variáveis
	private $host;
	private $dbname;
	private $user;
	private $password;
	private $table;
	private $pdo;
	
	// construtor para criar conexão
	function __construct ($host, $dbname, $user, $password, $table){
		// inserir valores da minha variável
		$this->host     = $host;
		$this->dbname   = $dbname;
		$this->user     = $user;
		$this->password = $password;  
		$this->table 	= $table;
		// fazer conexão -------- ------------------------------------------------------------------
		// estrutura try/ catch é a estrutura de controle utilizada para prever erros de conexão 
		// tente fazer a conexão
		try {
			// new PDO ("tipo-de-banco:host="localhost";dbname=nome-do-banco","usuário","senha");
			$this->pdo = new PDO ("mysql:dbname=".$this->dbname.";host=".$this->host, $this->user, $this->password);
		// se não deu para fazer a conexão
		} catch (PDOException $e) {
			// vai atribuir à variável $e o erro ocorrido
			echo $e->getMessage(); // retorna a mensagem do erro
		}
	}

	public function search ($id){
		// fazer uma busca no pdo ------------------------------------------------------------------
		// acessar a conexão feita na variável pdo
		// busca segura com pdo prepare com pseudonomes :val
		// SELECIONE TODOS DA tabela ONDE val_tabela = pseudo_val
		$sql = $this->pdo->prepare ("SELECT * FROM $this->table WHERE id_aluno = :e");
		// atribui ao pseudonome o seu valor
		$sql->bindValue(":e", $id);
		// executar a query
		$sql->execute();
		/*	metódo alternativo para substituir o acima
			$sql = $this->pdo->prepare ("SELECT * FROM tb_aluno WHERE id_aluno = ?");
			$sql->execute (array($id));
		*/
		return $sql->rowCount();
	}
	
	public function insert ($nome){
		// inserir no banco com pdo -----------------------------------------------------------------
		// INSERIR NA tabela (val_tabela) OS VALORES (pseudo_val)
		$sql = $this->pdo->prepare ("INSERT INTO $this->table (nome_aluno) VALUES (:n)");
		// atribui ao pseudonome o seu valor
		$sql->bindValue (":n",$nome);
		// executa a query
		return $sql->execute()?true:false;
	}

	public function searchValue (){
		// fazer uma busca no pdo ------------------------------------------------------------------
		// SELECIONE TODOS DA tabela 
		$sql = $this->pdo->prepare ("SELECT * FROM $this->table");
		// executar a query
		$sql->execute();
		// método fetch vai retornar o que foi selecionado 
		// retorna um array associativo (PDO::FETCH_ASSOC)
		// dentro do while eu pegarei todos os registros encontrados, vou armazenar de um por um na minha variável data em formato de array associativo e vou imprimir
		// ao invés de usar o fetch (que pega só um registro completo), podemos usar o fetchAll que vai me retornar todos registros encontrados de uma vez
		/* exemplo alternativo
			$data = $sql->fetchAll(PDO::FETCH_ASSOC);
			// a variável $listar está recebendo os dados da variável $data
			foreach($data as $listar){
				echo "<br/>Nome: ".$listar["nome_aluno"];
			}
		*/
		while ($data = $sql->fetch(PDO::FETCH_ASSOC)){
			// vai imprimir em data o valor que está na minha coluna ["nome_aluno"] da tabela do meu banco
			echo "<br/>Nome: ".$data["nome_aluno"];
			// obs: não é obrigatório o PDO:FETCH_ASSOC, ele será necessário apenas se você quiser imprimir a linha do registro em formato de array associativo (para fins de testes, etc)
		}

		//return $sql->rowCount();
	}

	// atualiza os dados passados pelo parâmetro
	public function update ($id, $nome, $email){

		// verifica se o usuário quer alterar os 2 campos
		if ($nome != "" && $email != "") {
			// ATUALIZA DA tabela O val1_tabela = :pseudo_val1, val2_tabela = :pseudo_val2 ONDE val3_tabela = :pseudo_val3
			$sql = $this->pdo->prepare("UPDATE $this->table SET nome_aluno = :n, email_aluno = :e WHERE id_aluno = :i");
			// atribuir os valores dos parâmetros aos pseudo nome
			$sql->bindValue (":n", $nome);
			$sql->bindValue (":e", $email);
			$sql->bindValue (":i", $id);
			// retorna true ou false, se a execução foi feita com sucesso ou não
			return $sql->execute();
		// verifica se o usuário quer alterar somento o email
		} else if ($nome == "" && $email != ""){
			// mesma coisa do primeiro só muda que vai atualizar apenas um campo
			$sql = $this->pdo->prepare("UPDATE $this->table SET email_aluno = :e WHERE id_aluno = :i");
			$sql->bindValue (":e", $email);
			$sql->bindValue (":i", $id);
			return $sql->execute();
		// verifica se o usuário quer alterar somento o nome
		} else if ($nome != "" && $email == ""){
			$sql = $this->pdo->prepare("UPDATE $this->table SET nome_aluno = :n WHERE id_aluno = :i");
			$sql->bindValue (":n", $nome);
			$sql->bindValue (":i", $id);
			return $sql->execute();
		// se não quiser alterar nenhum, não acontece nada
		} else return "void";
	}

	// deleta o registro pelo id
	public function delete ($id){
		// chamo a função search desta mesma classe e verifico se o id existe
		if (Setup::search ($id)){
			// se existe eu DELETO DA tabela ONDE val_tabela = :pseudo_val
			$sql = $this->pdo->prepare ("DELETE FROM $this->table WHERE id_aluno = :i");
			$sql->bindValue(":i", $id);
			// retorno um valor booleano
			return $sql->execute();
		// senão não existe o id, então ele não pode deletar nada
		} else return false;
	}
}

?>