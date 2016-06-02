<?Php

namespace Core\Utils\Translator;

abstract class AbstractTranslator
{
		protected function explodeDate($date)
		{
				$exp = explode(' ', $date);
				$exp = explode('-', $exp[0]);
				$array = array(
					'ano' => $exp[0],
					'mes' => $exp[1],
					'dia' => $exp[2],
				);
				return $array;
		}

}
