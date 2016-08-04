<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ClienteFoto
{
    use Dta;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="clientesFoto")
     * @ORM\JoinColumn(name="id_cliente", nullable=false, referencedColumnName="id_cliente")
     */
    protected $clienteFoto;
	
    /**
     * @ORM\Column(type="datetime")
     */
    protected $dataHora;
	
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $imagem;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $token;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $ativo;

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
	
	/**
     * Set clienteFoto
     *
     * @param Cliente $clienteFoto
     *
     * @return ClienteFoto
     */
    public function setClienteFoto(Cliente $clienteFoto)
    {
        $this->clienteFoto = $clienteFoto;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getToken()
    {
    	return $this->token;
    }
    
    /**
     * @param string $token
     *
     * @return ClienteFoto
     */
    public function setToken($token)
    {
    	$this->token = $token;
    
    	return $this;
    }

    /**
     * Get clienteFoto
     *
     * @return Cliente
     */
    public function getClienteFoto()
    {
        return $this->clienteFoto;
    }
    
    /**
     * Set dataHora
     *
     * @param \DateTime $dataHora
     *
     * @return ClienteFoto
     */
    public function setDataHora($dataHora)
    {
    	$this->dataHora = $dataHora;
    
    	return $this;
    }
    
    /**
     * Get dataHora
     *
     * @return \DateTime
     */
    public function getDataHora()
    {
    	return $this->dataHora;
    }
	
    /**
     * Set imagem
     *
     * @param text $imagem
     *
     * @return ClienteFoto
     */
    public function setImagem($imagem)
    {
            $this->imagem = $imagem;

            return $this;
    }

    /**
     * Get imagem
     *
     * @return text
     */
    public function getImagem()
    {
            return $this->imagem;
    }

    /**
     * Set ativo
     *
     * @param boolean $ativo
     *
     * @return ClienteFoto
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * Get ativo
     *
     * @return float
     */
    public function getAtivo()
    {
        return $this->ativo;
    }
    
    public function __toString() {
            return $this->imagem;
    }
}