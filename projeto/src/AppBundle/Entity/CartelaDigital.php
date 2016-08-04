<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CartelaDigital
{
    use Dta;

   /**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="cartelasCliente")
     * @ORM\JoinColumn(name="id_cliente", nullable=false, referencedColumnName="id_cliente")
     */
    protected $cliente;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $dataHoraAquisicao;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dataHoraUtilizacao;
    
    /**
     * @ORM\ManyToOne(targetEntity="PontoDeVenda", inversedBy="pontosCliente")
     * @ORM\JoinColumn(name="id_ponto_de_venda", nullable=false, referencedColumnName="id_ponto_de_venda")
     */
    protected $pontosDeVenda;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $tipo;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $ativo;
    
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->pontosDeVenda = new ArrayCollection();
    }

    
    /**
     * Set id
     *
     * @param integer $id
     *
     * @return CartelaDigital
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
     * Set dataHoraAquisicao
     *
     * @param \DateTime $dataHoraAquisicao
     *
     * @return CartelaDigital
     */
    public function setDataHoraAquisicao($dataHoraAquisicao)
    {
        $this->dataHoraAquisicao = $dataHoraAquisicao;

        return $this;
    }

    /**
     * Get dataHoraAquisicao
     *
     * @return \DateTime
     */
    public function getDataHoraAquisicao()
    {
        return $this->dataHoraAquisicao;
    }

    /**
     * Set dataHoraUtilizacao
     *
     * @param \DateTime $dataHoraUtilizacao
     *
     * @return CartelaDigital
     */
    public function setDataHoraUtilizacao($dataHoraUtilizacao)
    {
        $this->dataHoraUtilizacao = $dataHoraUtilizacao;

        return $this;
    }

    /**
     * Get dataHoraUtilizacao
     *
     * @return \DateTime
     */
    public function getDataHoraUtilizacao()
    {
        return $this->dataHoraUtilizacao;
    }

    /**
     * Set cliente
     *
     * @param Cliente $cliente
     *
     * @return CartelaDigital
     */
    public function setCliente(Cliente $cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Add pontosDeVenda
     *
     * @param PontoDeVenda $pontosDeVenda
     *
     * @return PontoDeVenda
     */
    public function addPontosDeVenda(PontoDeVenda $pontosDeVenda)
    {
        $this->pontosDeVenda[] = $pontosDeVenda;

        return $this;
    }

    /**
     * Remove pontosDeVenda
     *
     * @param PontoDeVenda $pontosDeVenda
     */
    public function removePontosDeVenda(PontoDeVenda $pontosDeVenda)
    {
        $this->pontosDeVenda->removeElement($pontosDeVenda);
    }

    /**
     * Get pontosDeVenda
     *
     * @return Collection
     */
    public function getPontosDeVenda()
    {
        return $this->pontosDeVenda;
    }
    
    /**
     * @return integer
     */
    public function getTipo()
    {
    	return $this->tipo;
    }
    
    /**
     * @param integer $tipo
     *
     * @return CartelaDigital
     */
    public function setTipo($tipo)
    {
    	$this->tipo = $tipo;
    
    	return $this;
    }

    /**
     * @return boolean
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param boolean $ativo
     *
     * @return CartelaDigital
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }
}
