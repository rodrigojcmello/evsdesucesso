<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ClienteBioimpedancia
{
    use Dta;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="clientesBioimpedancia")
     * @ORM\JoinColumn(name="id_cliente", nullable=false, referencedColumnName="id_cliente")
     */
    protected $bioimpedancia;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $dataHora;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $imc;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $percGordura;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $percMusculo;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $metabolismo;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $gorduraVisceral ;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $idadeCorporal;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $token;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $ativo;


    /**
     * Set id
     *
     * @param integer $id
     *
     * @return ClienteBioimpedancia
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dataHora
     *
     * @param \DateTime $dataHora
     *
     * @return ClienteBioimpedancia
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
     * Set bioimpedancia
     *
     * @param Cliente $bioimpedancia
     *
     * @return ClienteBioimpedancia
     */
    public function setBioimpedancia(Cliente $bioimpedancia)
    {
        $this->bioimpedancia = $bioimpedancia;

        return $this;
    }

    /**
     * Get bioimpedancia
     *
     * @return Cliente
     */
    public function getBioimpedancia()
    {
        return $this->bioimpedancia;
    }
    
    /**
     * @return string
     */
    public function getImc()
    {
        return $this->imc;
    }

    /**
     * @param string $imc
     *
     * @return ClienteBioimpedancia
     */
    public function setImc($imc)
    {
        $this->imc = $imc;

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
     * @return ClienteBioimpedancia
     */
    public function setToken($token)
    {
    	$this->token = $token;
    
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getPercGordura()
    {
    	return $this->percGordura;
    }
    
    /**
     * @param string $percGordura
     *
     * @return ClienteBioimpedancia
     */
    public function setPercGordura($percGordura)
    {
    	$this->percGordura = $percGordura;
    
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getPercMusculo()
    {
    	return $this->percMusculo;
    }
    
    /**
     * @param string $percMusculo
     *
     * @return ClienteBioimpedancia
     */
    public function setPercMusculo($percMusculo)
    {
    	$this->percMusculo = $percMusculo;
    
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getMetabolismo()
    {
    	return $this->metabolismo;
    }
    
    /**
     * @param string $metabolismo
     *
     * @return ClienteBioimpedancia
     */
    public function setMetabolismo($metabolismo)
    {
    	$this->metabolismo = $metabolismo;
    
    	return $this;
    }
    
    /**
     * @return float
     */
    public function getGorduraVisceral()
    {
    	return $this->gorduraVisceral;
    }
    
    /**
     * @param float $gorduraVisceral
     *
     * @return ClienteBioimpedancia
     */
    public function setGorduraVisceral($gorduraVisceral)
    {
    	$this->gorduraVisceral = $gorduraVisceral;
    
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getIdadeCorporal()
    {
    	return $this->idadeCorporal;
    }
    
    /**
     * @param string $idadeCorporal
     *
     * @return ClienteBioimpedancia
     */
    public function setIdadeCorporal($idadeCorporal)
    {
    	$this->idadeCorporal = $idadeCorporal;
    
    	return $this;
    }

    /**
     * Set ativo
     *
     * @param boolean $ativo
     *
     * @return ClienteBioimpedancia
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

}
