<?php

namespace Core\Utils;


class Status
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


class ClientesStatusGenerator
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

	public function checkPositivoCliente($cliente)
	{
		//Inciando variáveis
		$mesContinuo = 0;
		$positivo = false;

		//checando se já possui status atribuido
		if($cliente['idStatus'] != ''){
			return false;
		}

		//Construindo a data Alvo
        $dateOp = new DateOperation(new \DateTime($this->ano.'-'.$this->mes.'-01'));
        $status = 'Positivo';

		foreach($cliente['vendas'] as $i => $infoCli){
	
			$ano = $dateOp->getYear();
			$mes = $dateOp->getMonth();

			//Caso seja a primeira vez que ele passa, mesmo nao comprando o cliente pode ser considerado positivo
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
			if($mesAlvo == false){
				//$mesContinuo--;
			}
			$status = new Status($positivo = 1, $periodo = $mesContinuo);
		}else{
			$status = false;
		}

		return $status;

	}

	public function checkNegativosCliente($cliente)
	{

		if($cliente['idStatus'] != ''){
			return false;
		}

		$dataAlvo = new \DateTime( $this->ano.'-'.$this->mes.'-01');
		$ultimaData = new \DateTime($cliente['vendas'][0]['ano'].'-'.$cliente['vendas'][0]['mes'].'-01');

		$intervalo = $ultimaData->diff($dataAlvo);
		$int = $intervalo->m;
		$intY = $intervalo->y;
		//var_dump($intervalo);die;
		if($int > $this->int || $intY > 0){
			$this->nNegativos++;
			if($intY > 0){
				$int += 12*$intY;
			}
			return new Status($negativo = 2, $periodo = $int);
			
		}else{
			return false;
		}

	}

	public function checkNovosCliente($cliente)
	{
		$ano = $this->ano;
		$mes = $this->mes;

		if($cliente['idStatus']!=''){
			return false;
		}
		//if($cliente['idCliente'] == '1446'){
				//echo '<br>Ano ('.$ano.'='.$cliente['vendas'][0]['ano'].') Mes ('.$mes.'='.$infoCli['mes'].')';
		//}
		if($cliente['vendas'][0]['ano'] == $ano && $cliente['vendas'][0]['mes'] == $mes && count($cliente['vendas']) == 1){
			$this->nNovos++;
			return new Status($novo = 3);
		}
		
		return false;

	}

	public function checkRecuperadosCliente($cliente)
	{
		$ano = $this->ano;
		$mes = ($this->mes);

		if($cliente['idStatus']!=''){
			return false;
		}

		if($cliente['vendas'][0]['ano'] == $ano && $cliente['vendas'][0]['mes'] == $mes){
			
			$dataAlvo = new \DateTime( $this->ano.'-'.$this->mes.'-01');
			$ultimaData = new \DateTime($cliente['vendas'][1]['ano'].'-'.$cliente['vendas'][1]['mes'].'-01');
			
			$intervalo = $ultimaData->diff($dataAlvo);
			$int = $intervalo->m;
			if($int > $this->int){
				$this->nRecuperados++;
				return new Status($recuperado = 4);
			}else{
				return false;
			}
		}
		
		return false;

	}

	public function checkPositivos($clientes)
	{

		foreach($clientes as $k => $cliente){
			
			$status = $this->checkPositivoCliente($cliente);

			if($status !== false){
				$status = $status->getArray(); 
				$clientes[$k]['idStatus'] = $status['idStatus'];
				$clientes[$k]['periodoStatus'] = $status['periodoStatus'];
				unset($clientes[$k]['vendas']);
			}
		}
		return $clientes;

	}

	public function checkNegativos($clientes)
	{

		foreach($clientes as $k => $cliente){
			
			$status = $this->checkNegativosCliente($cliente);

			if($status !== false){
				$status = $status->getArray(); 
				$clientes[$k]['idStatus'] = $status['idStatus'];
				$clientes[$k]['periodoStatus'] = $status['periodoStatus'];
				unset($clientes[$k]['vendas']);
			}
		}
		return $clientes;

	}

	public function checkNovos($clientes)
	{

		foreach($clientes as $k => $cliente){
			
			$status = $this->checkNovosCliente($cliente);

			if($status !== false){
				$status = $status->getArray(); 
				$clientes[$k]['idStatus'] = $status['idStatus'];
				$clientes[$k]['periodoStatus'] = $status['periodoStatus'];
				unset($clientes[$k]['vendas']);
			}
		}
		return $clientes;

	}

	public function checkRecuperados($clientes)
	{

		foreach($clientes as $k => $cliente){
			
			$status = $this->checkRecuperadosCliente($cliente);

			if($status !== false){
				$status = $status->getArray(); 
				$clientes[$k]['idStatus'] = $status['idStatus'];
				$clientes[$k]['periodoStatus'] = $status['periodoStatus'];
				unset($clientes[$k]['vendas']);
			}
		}
		return $clientes;

	}

	public function fillRegulares($clientes)
	{
		$status = new Status($regular = 5);
		$statusDesc = $status->getArray(); 
		foreach($clientes as $k => $cliente){
			if($cliente['idStatus']!=''){
				continue;
			}
			if($cliente['vendas'][0]['ano'] != ''){
				$this->nRegulares++;
				
				$clientes[$k]['idStatus'] = $statusDesc['idStatus'];
				$clientes[$k]['periodoStatus'] = $statusDesc['periodoStatus'];
				unset($clientes[$k]['vendas']);
			}
		}
		return $clientes;

	}


	public function prepareArray(array $clientes)
	{
		$idCliente = '';
		$arr = array();
		$temp = array(
					'qtdPeriodo' => 0,
					'valorPeriodo' => 0,
					);
		foreach($clientes as $k => $cliente)
		{
			
			if($idCliente != $cliente['idCliente'] && $idCliente != ''){
				$arr[$idCliente] = $temp;
				$temp = array(
					'qtdPeriodo' => 0,
					'valorPeriodo' => 0,
					);
			}

			$temp['qtdPeriodo'] += $cliente['qtdTotal'];
			$temp['valorPeriodo'] += $cliente['valorTotal'];
			$temp['ultimaData'] = $cliente['ultimaData'];
			$temp['nomeCliente'] = $cliente['nomeCliente'];
			$temp['idCliente'] = $cliente['idCliente'];
			$temp['idStatus'] = '';
			$temp['vendas'][] = array(
				'ano' => $cliente['ano'],
				'mes' => $cliente['mes'],
				);
			$idCliente = $cliente['idCliente'];
		}
		
		$arr[$idCliente] = $temp;
		$temp = array();

		return $arr;

	}

	public function generate(array $clientes)
	{

		
		
		$arr = $this->prepareArray($clientes);

		$clientes = $this->checkPositivos($arr);
		$clientes = $this->checkNegativos($clientes);
		$clientes = $this->checkNovos($clientes);
		$clientes = $this->checkRecuperados($clientes);
		$clientes = $this->fillRegulares($clientes);
		
		return array(
        'ano' => $this->ano,
        'mes' => $this->mes,
        'intervalo' => $this->int,
        'itens' => $clientes,
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