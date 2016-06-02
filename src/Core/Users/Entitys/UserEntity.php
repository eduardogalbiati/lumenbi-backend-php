<?Php
namespace Core\Users\Entitys;

class UserEntity 
{
    private $idUsuario;
    private $idEmpresa;
    private $idDepartamento;
    private $idPerfil;
    private $idDiscador;
    private $nomeUsuario;
    private $usuario;
    private $senha;
    private $email;
    private $dtNascimento;
    private $cpf;
    private $rg;
    private $status;
    private $telefone1;
    private $telefone2;
    private $cep;
    private $endereco;
    private $numero;
    private $complemento;
    private $bairro;
    private $uf;
    private $cidade;
    private $observacoes;
    
    public function convertToArray()
    {
        return get_object_vars($this);
    }

    public function getIdUsuario() 
    {
        return $this->idUsuario;
    }

    public function getIdEmpresa() 
    {
        return $this->idEmpresa;
    }

    public function setIdEmpresa($idEmpresa) 
    {
        $this->idEmpresa = $idEmpresa;
        return $this;
    }

    public function getIdDepartamento() 
    {
        return $this->idDepartamento;
    }

    public function setIdDepartamento($idDepartamento) 
    {
        $this->idDepartamento = $idDepartamento;
        return $this;
    }

    public function getIdPerfil() 
    {
        return $this->idPerfil;
    }

    public function setIdPerfil($idPerfil) 
    {
        $this->idPerfil = $idPerfil;
        return $this;
    }

    public function getIdDiscador() 
    {
        return $this->idDiscador;
    }

    public function setIdDiscador($idDiscador) 
    {
        $this->idDiscador = $idDiscador;
        return $this;
    }

    public function getNomeUsuario() 
    {
        return $this->nomeUsuario;
    }

    public function setNomeUsuario($nomeUsuario) 
    {
        $this->nomeUsuario = $nomeUsuario;
        return $this;
    }

    public function getUsuario() 
    {
        return $this->usuario;
    }

    public function setUsuario($usuario) 
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getSenha() 
    {
        return $this->senha;
    }

    public function setSenha($senha) 
    {
       // $this->senha = hash('sha512',$senha);
        $this->senha = sha1($senha);
        return $this;
    }

    public function getEmail() 
    {
        return $this->email;
    }

    public function setEmail($email) 
    {
        $this->email = $email;
        return $this;
    }

    public function getDtNascimento() 
    {

        return $this->dtNascimento;
    }

    public function setDtNascimento($dtNascimento) 
    {
        $this->dtNascimento =  $dtNascimento;
        return $this;
    }

    public function getCpf() 
    {
        return $this->cpf;
    }

    public function setCpf($cpf) 
    {
        $arr = array(".","-");
        $this->cpf = str_replace($arr,"",$cpf);
        return $this;
    }

    public function getRg() 
    {
        return $this->rg;
    }

    public function setRg($rg) 
    {
        $this->rg = $rg;
        return $this;
    }

    public function getStatus() 
    {
        return $this->status;
    }

    public function setStatus($status) 
    {
        $this->status = $status;
        return $this;
    }

    public function getTelefone1() 
    {
        return $this->telefone1;
    }

    public function setTelefone1($telefone1) 
    {
        $this->telefone1 = $telefone1;
        return $this;
    }

    public function getTelefone2() 
    {
        return $this->telefone2;
    }

    public function setTelefone2($telefone2) 
    {
        $this->telefone2 = $telefone2;
        return $this;
    }

    public function getCep() 
    {
        return $this->cep;
    }

    public function setCep($cep) 
    {
        $this->cep = $cep;
        return $this;
    }

    public function getEndereco() 
    {
        return $this->endereco;
    }

    public function setEndereco($endereco) 
    {
        $this->endereco = $endereco;
        return $this;
    }

    public function getNumero() 
    {
        return $this->numero;
    }

    public function setNumero($numero) 
    {
        $this->numero = $numero;
        return $this;
    }

    public function getComplemento() 
    {
        return $this->complemento;
    }

    public function setComplemento($complemento) 
    {
        $this->complemento = $complemento;
        return $this;
    }

    public function getBairro() 
    {
        return $this->bairro;
    }

    public function setBairro($bairro) 
    {
        $this->bairro = $bairro;
        return $this;
    }

    public function getUf() 
    {
        return $this->uf;
    }

    public function setUf($uf) 
    {
        $this->uf = $uf;
        return $this;
    }

    public function getCidade() 
    {
        return $this->cidade;
    }

    public function setCidade($cidade) 
    {
        $this->cidade = $cidade;
        return $this;
    }

    public function getObservacoes() 
    {
        return $this->observacoes;
    }

    public function setObservacoes($observacoes) 
    {
        $this->observacoes = $observacoes;
        return $this;
    }



}