<?php

namespace Core\Utils;


class StatusFornecedores

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


class FornecedoresStatusGenerator


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

	public function checkPositivoFornecedor($fornecedor)

	{
		//Inciando variáveis
		$mesContinuo = 0;
		$positivo = false;

		//checando se já possui status atribuido
		if($fornecedor['idStatus']!=''){
			return false;
		}

		//Construindo a data Alvo
        $dateOp = new DateOperation(new \DateTime($this->ano.'-'.$this->mes.'-01'));
        $status = 'Positivo';

		foreach($fornecedor['vendas'] as $i => $infoCli){

			
		

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

			$status = new StatusFornecedores($positivo = 1, $periodo = $mesContinuo);
		}else{
			$status = false;
		}

		return $status;

	}

	public function checkNegativosFornecedor($fornecedor)

	{

		if($fornecedor['idStatus'] != ''){
			return false;
		}

		$dataAlvo = new \DateTime( $this->ano.'-'.$this->mes.'-01');
		$ultimaData = new \DateTime($fornecedor['vendas'][0]['ano'].'-'.$fornecedor['vendas'][0]['mes'].'-01');

		$intervalo = $ultimaData->diff($dataAlvo);
		$int = $intervalo->m;

		$intY = $intervalo->y;

		if($int > $this->int || $intY > 0){
			$this->nNegativos++;
			if($intY > 0){
				$int += 12*$intY;
			}
			return new StatusFornecedores($negativo = 2, $periodo = $int);

		}else{
			return false;
		}

	}

	public function checkNovosFornecedor($fornecedor)
	{
		$ano = $this->ano;
		$mes = $this->mes;

		if($fornecedor['idStatus']!=''){
			return false;
		}
		//if($fornecedor['idFornecedor'] == '1446'){
				//echo '<br>Ano ('.$ano.'='.$fornecedor['vendas'][0]['ano'].') Mes ('.$mes.'='.$infoCli['mes'].')';
		//}
		if($fornecedor['vendas'][0]['ano'] == $ano && $fornecedor['vendas'][0]['mes'] == $mes && count($fornecedor['vendas']) == 1){
			$this->nNovos++;
			return new StatusFornecedores($novo = 3);
		}
		
		return false;

	}

	public function checkRecuperadosFornecedor($fornecedor)

	{
		$ano = $this->ano;
		$mes = ($this->mes);

		if($fornecedor['idStatus']!=''){
			return false;
		}

		if($fornecedor['vendas'][0]['ano'] == $ano && $fornecedor['vendas'][0]['mes'] == $mes){
			
			$dataAlvo = new \DateTime( $this->ano.'-'.$this->mes.'-01');
			$ultimaData = new \DateTime($fornecedor['vendas'][1]['ano'].'-'.$fornecedor['vendas'][1]['mes'].'-01');
			
			$intervalo = $ultimaData->diff($dataAlvo);
			$int = $intervalo->m;
			if($int > $this->int){
				$this->nRecuperados++;
				return new StatusFornecedores($recuperado = 4);
			}else{
				return false;
			}
		}
		
		return false;

	}

	public function checkPositivos($fornecedores)
	{

		foreach($fornecedores as $k => $fornecedor){
			
			$status = $this->checkPositivoFornecedor($fornecedor);


			if($status !== false){
				$status = $status->getArray(); 
				$fornecedores[$k]['idStatus'] = $status['idStatus'];
				$fornecedores[$k]['periodoStatus'] = $status['periodoStatus'];
				unset($fornecedores[$k]['vendas']);
			}
		}
		return $fornecedores;

	}

	public function checkNegativos($fornecedores)
	{

		foreach($fornecedores as $k => $fornecedor){
			
			$status = $this->checkNegativosFornecedor($fornecedor);



			if($status !== false){
				$status = $status->getArray(); 
				$fornecedores[$k]['idStatus'] = $status['idStatus'];
				$fornecedores[$k]['periodoStatus'] = $status['periodoStatus'];
				unset($fornecedores[$k]['vendas']);
			}
		}
		return $fornecedores;

	}

	public function checkNovos($fornecedores)
	{

		foreach($fornecedores as $k => $fornecedor){
			
			$status = $this->checkNovosFornecedor($fornecedor);

			if($status !== false){
				$status = $status->getArray(); 
				$fornecedores[$k]['idStatus'] = $status['idStatus'];
				$fornecedores[$k]['periodoStatus'] = $status['periodoStatus'];
				unset($fornecedores[$k]['vendas']);
			}
		}
		return $fornecedores;

	}

	public function checkRecuperados($fornecedores)
	{

		foreach($fornecedores as $k => $fornecedor){
			
			$status = $this->checkRecuperadosFornecedor($fornecedor);


			if($status !== false){
				$status = $status->getArray(); 
				$fornecedores[$k]['idStatus'] = $status['idStatus'];
				$fornecedores[$k]['periodoStatus'] = $status['periodoStatus'];
				unset($fornecedores[$k]['vendas']);
			}
		}
		return $fornecedores;

	}

	public function fillRegulares($fornecedores)
	{
		$status = new StatusFornecedores($regular = 5);
		$status = $status->getArray(); 
		foreach($fornecedores as $k => $fornecedor){
			if($fornecedor['idStatus']!=''){
				continue;
			}
			if($fornecedor['vendas'][0]['ano'] != ''){
				$this->nRegulares++;
				
				$fornecedores[$k]['idStatus'] = $status['idStatus'];
				$fornecedores[$k]['periodoStatus'] = $status['periodoStatus'];
				unset($fornecedores[$k]['vendas']);
			}
		}
		return $fornecedores;

	}


	public function prepareArray(array $fornecedores)
	{



		$idFornecedor = '';
		$arr = array();
		$temp = array(
					'qtdPeriodo' => 0,
					'valorPeriodo' => 0,
					);
		foreach($fornecedores as $k => $fornecedor)
		{
			

			if($idFornecedor != $fornecedor['idFornecedor'] && $idFornecedor != ''){
				$arr[$idFornecedor] = $temp;
				$temp = array(
					'qtdPeriodo' => 0,
					'valorPeriodo' => 0,
					);
			}

			$temp['qtdPeriodo'] += $fornecedor['qtdTotal'];
			$temp['valorPeriodo'] += $fornecedor['valorTotal'];
			$temp['ultimaData'] = $fornecedor['ultimaData'];


			$temp['nomeFornecedor'] = $fornecedor['nomeFornecedor'];
			$temp['idFornecedor'] = $fornecedor['idFornecedor'];
			$temp['idStatus'] = '';
			$temp['vendas'][] = array(
				'ano' => $fornecedor['ano'],
				'mes' => $fornecedor['mes'],
				);

			$idFornecedor = $fornecedor['idFornecedor'];
		}
		
		$arr[$idFornecedor] = $temp;
		$temp = array();

		return $arr;

	}

	public function generate(array $fornecedores)
	{

		
		
		$arr = $this->prepareArray($fornecedores);

		$fornecedores = $this->checkPositivos($arr);
		$fornecedores = $this->checkNegativos($fornecedores);
		$fornecedores = $this->checkNovos($fornecedores);
		$fornecedores = $this->checkRecuperados($fornecedores);
		$fornecedores = $this->fillRegulares($fornecedores);
		
		return array(
        'ano' => $this->ano,
        'mes' => $this->mes,
        'intervalo' => $this->int,

        'itens' => $fornecedores,
        'resumo' => array(
          'nPos' => $this->nPositivos,
          'nNeg' => $this->nNegativos,
          'nRec' => $this->nRecuperados,
          'nNov' => $this->nNovos,
          'nReg' => $this->nRegulares,
          ),
        );

	}
}


