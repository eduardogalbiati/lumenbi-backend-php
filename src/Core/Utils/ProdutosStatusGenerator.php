<?php

namespace Core\Utils;


class StatusProdutos
{
	protected $idStatus;
	protected $periodo;

	function __construct($idStatus, $periodo = null)
	{
		$this->idStatus = $idStatus;
		$this->periodo = $periodo;
	}

	public function getArray()
	{
		return array(
			'idStatus' => $this->idStatus,
			'periodoStatus' => $this->periodo,
			);
	}
}


class ProdutosStatusGenerator

{
	protected $ano = '2015';
	protected $mes = '9';
	protected $int = '3';

	public $nPositivos;
	public $nNegativos;
	public $nRecuperados;
	public $nNovos;
	public $nRegulares;


	public function setParams(array $params)
	{
		$this->ano = $params['ano'];
		$this->mes = $params['mes'];
		$this->int = $params['int'];
		return $this;
	}

	public function setAno($ano)
	{
		$this->ano = $ano;
		return $this;
	}

	public function setMes($mes)
	{
		$this->mes = $mes;
		return $this;
	}

	public function checkPositivoCliente($produto)
	{
		//Inciando variáveis
		$mesContinuo = 0;
		$positivo = false;

		//checando se já possui status atribuido
		if($produto['idStatus']!=''){
			return false;
		}

		//Construindo a data Alvo
        $dateOp = new DateOperation(new \DateTime($this->ano.'-'.$this->mes.'-01'));
        $status = 'Positivo';

		foreach($produto['vendas'] as $i => $infoCli){

			

			$ano = $dateOp->getYear();
			$mes = $dateOp->getMonth();

			if($i == 0){
				$mesAlvo = false;
				if($infoCli['ano'] == $ano && $infoCli['mes'] == $mes){
					$mesAlvo = true;
				}else{
					$mesContinuo ++;
					$dateOp->subMonth(1);
				}
			}

			if($infoCli['ano'] == $ano && $infoCli['mes'] == $mes){
				$mesContinuo ++;
				if($mesContinuo >= $this->int){
					$positivo = true;
				}
			}
			
			$dateOp->subMonth(1);
		}

		if($positivo){
			$this->nPositivos++;

			$status = new StatusProdutos($positivo = 1, $periodo = $mesContinuo);
		}else{
			$status = false;
		}

		return $status;

	}

	public function checkNegativosCliente($produto)
	{

		if($produto['idStatus'] != ''){
			return false;
		}

		$dataAlvo = new \DateTime( $this->ano.'-'.$this->mes.'-01');
		$ultimaData = new \DateTime($produto['vendas'][0]['ano'].'-'.$produto['vendas'][0]['mes'].'-01');

		$intervalo = $ultimaData->diff($dataAlvo);
		$int = $intervalo->m;

		$intY = $intervalo->y;

		if($int > $this->int || $intY > 0){
			$this->nNegativos++;
			if($intY > 0){
				$int += 12*$intY;
			}
			 return new StatusProdutos($negativo = 2, $periodo = $int);

		}else{
			return false;
		}

	}

	public function checkNovosCliente($produto)
	{
		$ano = $this->ano;
		$mes = $this->mes;

		if($produto['idStatus']!=''){
			return false;
		}
		//if($produto['idProduto'] == '1446'){
				//echo '<br>Ano ('.$ano.'='.$produto['vendas'][0]['ano'].') Mes ('.$mes.'='.$infoCli['mes'].')';
		//}
		if($produto['vendas'][0]['ano'] == $ano && $produto['vendas'][0]['mes'] == $mes && count($produto['vendas']) == 1){
			$this->nNovos++;
			return new StatusProdutos($novo = 3);
		}
		
		return false;

	}

	public function checkRecuperadosCliente($produto)
	{
		$ano = $this->ano;
		$mes = ($this->mes);

		if($produto['idStatus']!=''){
			return false;
		}

		if($produto['vendas'][0]['ano'] == $ano && $produto['vendas'][0]['mes'] == $mes){
			
			$dataAlvo = new \DateTime( $this->ano.'-'.$this->mes.'-01');
			$ultimaData = new \DateTime($produto['vendas'][1]['ano'].'-'.$produto['vendas'][1]['mes'].'-01');
			
			$intervalo = $ultimaData->diff($dataAlvo);
			$int = $intervalo->m;
			if($int > $this->int){
				$this->nRecuperados++;
				return new StatusProdutos($recuperado = 4);
			}else{
				return false;
			}
		}
		
		return false;

	}

	public function checkPositivos($produtos)
	{

		foreach($produtos as $k => $produto){
			
			$status = $this->checkPositivoCliente($produto);

			if($status !== false){
				$status = $status->getArray(); 
				$produtos[$k]['idStatus'] = $status['idStatus'];
				$produtos[$k]['periodoStatus'] = $status['periodoStatus'];
				unset($produtos[$k]['vendas']);
			}
		}
		return $produtos;

	}

	public function checkNegativos($produtos)
	{

		foreach($produtos as $k => $produto){
			
			$status = $this->checkNegativosCliente($produto);

			if($status !== false){
				$status = $status->getArray(); 
				$produtos[$k]['idStatus'] = $status['idStatus'];
				$produtos[$k]['periodoStatus'] = $status['periodoStatus'];
				unset($produtos[$k]['vendas']);
			}
		}
		return $produtos;

	}

	public function checkNovos($produtos)
	{

		foreach($produtos as $k => $produto){
			
			$status = $this->checkNovosCliente($produto);

			if($status !== false){
				$status = $status->getArray(); 
				$produtos[$k]['idStatus'] = $status['idStatus'];
				$produtos[$k]['periodoStatus'] = $status['periodoStatus'];
				unset($produtos[$k]['vendas']);
			}
		}
		return $produtos;

	}

	public function checkRecuperados($produtos)
	{

		foreach($produtos as $k => $produto){
			
			$status = $this->checkRecuperadosCliente($produto);

			if($status !== false){
				$status = $status->getArray(); 
				$produtos[$k]['idStatus'] = $status['idStatus'];
				$produtos[$k]['periodoStatus'] = $status['periodoStatus'];
				unset($produtos[$k]['vendas']);
			}
		}
		return $produtos;

	}

	public function fillRegulares($produtos)
	{
		$status = new StatusProdutos($regular = 5);
		$status = $status->getArray();
		foreach($produtos as $k => $produto){
			if($produto['idStatus']!=''){
				continue;
			}
			if($produto['vendas'][0]['ano'] != ''){
				$this->nRegulares++;
				 
				$produtos[$k]['idStatus'] = $status['idStatus'];
				$produtos[$k]['periodoStatus'] = $status['periodoStatus'];
				unset($produtos[$k]['vendas']);
			}
		}
		return $produtos;

	}

	public function prepareArray(array $produtos)
	{
		$idProduto = '';
		$arr = array();
		$temp = array(
					'qtdPeriodo' => 0,
					'valorPeriodo' => 0,
					);
		foreach($produtos as $k => $produto)
		{
			
			if($idProduto != $produto['idProduto'] && $idProduto != ''){
				$arr[$idProduto] = $temp;
				$temp = array(
					'qtdPeriodo' => 0,
					'valorPeriodo' => 0,
					);
			}

			$temp['qtdPeriodo'] += $produto['qtdTotal'];
			$temp['valorPeriodo'] += $produto['valorTotal'];
			$temp['ultimaData'] = $produto['ultimaData'];
			$temp['nomeProduto'] = $produto['nomeProduto'];
			$temp['idProduto'] = $produto['idProduto'];
			$temp['idStatus'] = '';
			$temp['vendas'][] = array(
				'ano' => $produto['ano'],
				'mes' => $produto['mes'],
				);
			$idProduto = $produto['idProduto'];
		}
		
		$arr[$idProduto] = $temp;
		$temp = array();

		return $arr;

	}

	public function generate(array $produtos)
	{

		
		
		$arr = $this->prepareArray($produtos);

		$produtos = $this->checkPositivos($arr);
		$produtos = $this->checkNegativos($produtos);
		$produtos = $this->checkNovos($produtos);
		$produtos = $this->checkRecuperados($produtos);
		$produtos = $this->fillRegulares($produtos);
		
		return array(
        'ano' => $this->ano,
        'mes' => $this->mes,
        'intervalo' => $this->int,
        'itens' => $produtos,
        'resumo' => array(
          'nPos' => $this->nPositivos,
          'nNeg' => $this->nNegativos,
          'nRec' => $this->nRecuperados,
          'nNov' => $this->nNovos,
          'nReg' => $this->nRegulares,
          ),
        );
		//die;
		//var_dump($cliPos);die;
	}
		


}