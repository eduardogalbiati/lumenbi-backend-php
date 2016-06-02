<?Php
namespace Core\Utils;

class ResponseObject
{
	private $alertas = array();

	public function addResponse($status, $alerta, $data='', $funcao='')
	{
		$arr = array(
				'status' => $status,
				'alerta' => $alerta,
				'data'   => $data,
				'funcao' => $funcao
			);
		$this->alertas[] = $arr;
	}
	public function getArray()
	{
		return $this->alertas;

	}
	public function getJson()
	{
		return json_encode($this->alertas);
		
	}

	public function hasNoResponses()
	{
		return ((empty($this->alertas))?true:false);
	}

	public function hasResponses()
	{
		return ((empty($this->alertas))?false:true);
	}

}