<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ClienteMedidas
{
    use Dta;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="clientesMedidas")
     * @ORM\JoinColumn(name="id_cliente", nullable=false, referencedColumnName="id_cliente")
     */
    protected $medidas;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $dataHora;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $peso;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $altura;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $torax;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $cintura;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $barriga;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $quadril;
    
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
     * @return ClienteMedidas
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
     * @return string
     */
    public function getToken()
    {
    	return $this->token;
    }
    
    /**
     * @param string $token
     *
     * @return ClienteMedidas
     */
    public function setToken($token)
    {
    	$this->token = $token;
    
    	return $this;
    }

    /**
     * Set dataHora
     *
     * @param \DateTime $dataHora
     *
     * @return ClienteMedidas
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
     * Set medidas
     *
     * @param Cliente $medidas
     *
     * @return ClienteMedidas
     */
    public function setMedidas(Cliente $medidas)
    {
        $this->medidas = $medidas;

        return $this;
    }

    /**
     * Get medidas
     *
     * @return Cliente
     */
    public function getMedidas()
    {
        return $this->medidas;
    }
    
    /**
     * @return string
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * @param string $peso
     *
     * @return ClienteMedidas
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getAltura()
    {
        return $this->altura;
    }

    /**
     * @param string $altura
     *
     * @return ClienteMedidas
     */
    public function setAltura($altura)
    {
        $this->altura = $altura;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getTorax()
    {
    	return $this->torax;
    }
    
    /**
     * @param string $torax
     *
     * @return ClienteMedidas
     */
    public function setTorax($torax)
    {
    	$this->torax = $torax;
    
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getCintura()
    {
    	return $this->cintura;
    }
    
    /**
     * @param string $cintura
     *
     * @return ClienteMedidas
     */
    public function setCintura($cintura)
    {
    	$this->cintura = $cintura;
    
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getBarriga()
    {
    	return $this->barriga;
    }
    
    /**
     * @param string $barriga
     *
     * @return ClienteMedidas
     */
    public function setBarriga($barriga)
    {
    	$this->barriga = $barriga;
    
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getQuadril()
    {
    	return $this->quadril;
    }
    
    /**
     * @param string $quadril
     *
     * @return ClienteMedidas
     */
    public function setQuadril($quadril)
    {
    	$this->quadril = $quadril;
    
    	return $this;
    }

    /**
     * Set ativo
     *
     * @param boolean $ativo
     *
     * @return ClienteMedidas
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
