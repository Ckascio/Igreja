<?php
class atualconta {

	function __construct($codigo='',$ultimolanc='',$creditar='') {

		$cod = explode(".",$codigo);
			$this->cod1 = $cod[0];
			$this->cod2 = $cod[0].'.'.$cod[1];
			$this->cod3 = $cod[0].'.'.$cod[1].'.'.$cod[2];
			$this->cod4 = $cod[0].'.'.$cod[1].'.'.$cod[2].'.'.$cod[3];
			$this->cod5 = $cod[0].'.'.$cod[1].'.'.$cod[2].'.'.$cod[3].'.'.$cod[4];

	$this->ultimolanc = $ultimolanc;//id deste lan�amento
	$this->creditar = $creditar;

	}

	function atualizar($valor,$dc,$igreja,$histLanc) {
		//Faz o lan�amento na tabela lan�amento e atualiza os dados da tabela contas
		$data = br_data($_POST['data'], 'Data do lan�amento inv�lida!');

		for ($i = 1; $i < 6; $i++) { //atualiza os dados da tabela contas
			$acesso = "cod$i";
			$result = mysql_query("SELECT id FROM contas WHERE codigo = '{$this->$acesso}'") or mysql_error(' - Em atualizar conta');
			$row = mysql_fetch_array($result);
			$linha = $row['id'];
			$atualizar = new DBRecord ('contas',$linha,'id'); //retrona os dados da tabela contas com este id
			if ($atualizar->tipo == strtoupper($dc)){
				$ValorAtual = $atualizar->saldo() + $valor;
			} else {
				$ValorAtual = $atualizar->saldo() - $valor;
			}
			/* printf( '<br/>Linha: %s **** C�digo: %s ---->Vlr Lan�.: %s ====> Vlr ant.: %s --- Valor Atual: %s
					**** N� do Lan�. %s *** Data: %s',$linha,$this->$acesso,$valor,$atualizar->saldo(),$ValorAtual,$this->ultimolanc,$data); */
			$atualizar->saldo = $ValorAtual;
			$atualizar->UpdateID();
			if ($i=='5') {//Faz o lan�amento
				$InsertLanc = sprintf("'','%s','%s','%s','%s','%s','%s','%s','%s'",$this->ultimolanc,
						$linha,strtoupper($dc),$valor,$igreja,$convencao,$data,$_SESSION['valid_user']);
				//echo '<br />'.$InsertLanc;//Exibi lan�amento no banco
				$lancamento = new incluir($InsertLanc, 'lancamento');
				$lancamento->inserir();
				//Nova vers�o de tabela
				if ($this->creditar >'0' ) {
				$histLancamento = mysql_escape_string($histLanc);
				$insertLancNova = sprintf("'','%s','%s','%s','%s','%s','%s','%s','%s'",$this->ultimolanc,
						$linha,$this->creditar,$valor,$igreja,$histLancamento,$data,$_SESSION['valid_user']);
				$lancNovaVersao = new incluir($insertLancNova, 'lanc');
				$lancNovaVersao->inserir();
				}
			}

		}

	}

}
