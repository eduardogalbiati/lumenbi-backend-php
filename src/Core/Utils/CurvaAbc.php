<?php

namespace Core\Utils;

/*
foreach ($produtos as $produto) {
    //1. adicionando os produtos com suas quantidade e valores
    $curvaAbc->addRow($produto['Descricao'], $produto['Qtde'], ($produto['vlrtotal'] / $produto['Qtde']));
}



*/
class CurvaAbc
{

    protected $valorTotal = 0;
    protected $qtdTotal = 0;
    protected $linhas;
    protected $linhasResumidas;
    protected $abc;
    protected $pcLimiteMin = 0;
    protected $pcLimiteMax = 100;
    protected $limit = false;

    protected $header;
    protected $historico = false;

    protected $qtdA;
    protected $qtdB;
    protected $prcA;
    protected $prcB;

    public $intervalo;

    public function __construct($int)
    {

       $this->intervalo = $int;
        $this->header = array(
            'A' => Array(
                'qtdItens' => 0,
                'valorTotal' => 0,
                'qtd' => 0,
                ),
            'B' => Array(
                'qtdItens' => 0,
                'valorTotal' => 0,
                'qtd' => 0,
                ),
            'C' => Array(
                'qtdItens' => 0,
                'valorTotal' => 0,
                'qtd' => 0,
                ),
            );

        //Implentar os outros casos!
    }


    protected function addPorcentagem()
    {
        foreach ($this->linhas as $index => $linha) {
            $linha['prcValor'] = round(($linha['valorTotal'] / $this->valorTotal) *100, 2) ;
            $linha['prcQtd'] = round(($linha['qtd'] / $this->qtdTotal) *100, 2) ;
            $this->linhas[$index] = $linha;
        }
    }

    protected function orderByValorProduto()
    {
        $linhasResumidas = $this->linhasResumidas;
        arsort($linhasResumidas);
        $c = 0;
        foreach ($linhasResumidas as $index => $valorTotal) {
            $c++;
            $arr = $this->linhas[$index];
            $arr['pos'] = $c;
            $novasLinhas[] = $arr;
        }


        $this->linhas = $novasLinhas;

    }

    protected function dentroDoIntervalo($prc)
    {
        /*
        if ($this->limit == false){
            return true;
        }
        if ($prc < $this->pcLimiteMin)  {
            return false;
        }

        if ($prc > $this->pcLimiteMax) {
            return false;
        }
*/
        return true;

    }

    protected function dentroDoLimite($index)
    {
        /*
        if($this->limit == false) {
            return true;
        }
        if ($index >= $this->limit) {
            return false;
        }
        */
        return true;
    }

    protected function addPorcentagemAcumulada()
    {
        $prcValor = 0;
        $prcQtd = 0;
        foreach ($this->linhas as $index => $linha) {
            $prcAcum = $prcValor + $linha['prcValor'];
            if ($this->dentroDoIntervalo($prcAcum) && $this->dentroDoLimite($index)) {
                $prcValor += $linha['prcValor'];
                $linha['prcValorAcumulada'] = $prcValor;
                $prcQtd += $linha['prcQtd'];
                $linha['prcQtdAcumulada'] = $prcQtd;
                $linhasFiltradas[] = $linha;
            }
        }

        $this->linhas = $linhasFiltradas;
    }

    public function addUltimaPosicao()
    {
        foreach ($this->linhas as $k => $linha) {
            
            $hist = $this->historico[$linha['item']]['detalhe'][ (count($this->historico[$linha['item']]['detalhe']))-2 ]['pos'];
            $pos = '';
            if($hist != ''){
                $pos = $linha['pos'] - ($hist);
                $pos = $pos*-1;
            }else{
                $pos = 0;
            }
            $this->linhas[$k]['difPos'] = $pos;
        }
    }

    public function getClassForItem($sItem)
    {
        $table = $this->getLinhas();

        foreach ($table as $item) {
            if($item['item'] == $sItem)
                return $item['classe'];
        }
        throw new \Exception("Produto não encontrado");
        
    }

    public function getTable()
    {

        $this->addPorcentagem();

        $this->orderByValorProduto();

        $this->addPorcentagemAcumulada();

        $this->addUltimaPosicao();

        $this->addClasseABC();

        return $this->getLinhas();
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getLinhas()
    {
        return $this->linhas;
    }

    public function sumHeader($classe, $index, $arg)
    {
        $this->header[$classe][$index] += $arg;

    }

    public function addHeader($classe, $index, $arg)
    {
        $this->header[$classe][$index] = $arg;

    }

    public function addHistorico(array $historico)
    {
        $this->historico = $historico;
    }

    public function addClasseABC()
    {
        $classe = 'A';
        $cont = array(
            'A' => 0,
            'B' => 0,
            'C' => 0
            );

        foreach ($this->linhas as $k => $linha) {
           $cont[$classe] += 1;

           $this->linhas[$k]['classe'] = $classe;

           $this->sumHeader($classe, 'qtdItens', $linha['qtd']);
           $this->sumHeader($classe, 'valorTotal', $linha['valorTotal']);
           $this->addHeader($classe, 'qtdItensDistintos', $cont[$classe]);

           if( ( $linha['prcValorAcumulada'] >= 65) && $classe=='A') {
                $classe = 'B';
           }else{
                if( ($linha['prcValorAcumulada'] >= 90) && $classe=='B' ){
                        break;
                }
           }
           
        }

        // Gerando o C por exclusão
        $totalQtdC = $this->qtdTotal - ($this->header['A']['qtd'] + $this->header['B']['qtd']);
        $totalValorC = $this->valorTotal - ($this->header['A']['valorTotal'] + $this->header['B']['valorTotal']);
        $totalQtdItensC = count($this->linhas) - $cont['A'] - $cont['B'];

        $this->addHeader('C', 'qtdItens', $totalQtdC);
        $this->addHeader('C', 'valorTotal', $totalValorC);
        $this->addHeader('C', 'qtdItensDistintos', $totalQtdItensC);

        //Gerando as Porcentagens
        $this->addHeader('A', 'prcValor', ($this->header['A']['valorTotal'] / $this->valorTotal)*100);
        $this->addHeader('B', 'prcValor', ($this->header['B']['valorTotal'] / $this->valorTotal)*100);
        $this->addHeader('C', 'prcValor', ($this->header['C']['valorTotal'] / $this->valorTotal)*100);

    }

    public function addLinha($item, $qtd, $valorUnit, $moreInfo)
    {
            $valorTotal = ($qtd * $valorUnit);

            $linha = array(
                'item' => trim($item),
                'qtd' => round($qtd),
                'valorUnit' => round($valorUnit, 2),
                'valorTotal' => round($valorTotal, 3),
                'classe' => 'C',
                'prcValor' => '0',
            );

            $linha += $moreInfo;

            if($this->historico != false){
               
                $linha['historico'] = $this->historico[$item];
            }
                
            $linhaResumida = $valorTotal;

            //Atualizando as propriedades
            $this->valorTotal += $valorTotal;
            $this->qtdTotal += round($qtd);

            $linhas = $this->linhas;
            $linhas[] = $linha;
            $this->linhas = $linhas;

            $linhasResumidas = $this->linhasResumidas;
            $linhasResumidas[] = $linhaResumida;
            $this->linhasResumidas = $linhasResumidas;

    }



    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function checkRange($qtd,$prc)
    {

    }
    public function setRanges(array $array)
    {
        $this->qtdA = $array['qtdA'];
        $this->qtdB = $array['qtdB'];
        $this->prcA = $array['prcA'];
        $this->prcB = $array['prcB'];
    }
}
