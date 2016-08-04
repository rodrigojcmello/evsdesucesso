<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Venda
{
    use Dta;

    /**
     * @ORM\Column(name="id_venda", type="string")
     * @ORM\Id
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="vendas")
     * @ORM\JoinColumn(name="id_cliente", nullable=false, referencedColumnName="id_cliente")
     */
    protected $cliente;
    
    /**
     * @ORM\ManyToOne(targetEntity="Anfitriao", inversedBy="vendasAnfitriao")
     * @ORM\JoinColumn(name="id_anfitriao", nullable=true, referencedColumnName="id_anfitriao")
     */
    protected $anfitriao;

    /**
     * @ORM\OneToMany(targetEntity="VendaProduto", mappedBy="venda")
     */
    protected $vendasProdutos;
    
    /**
     * @ORM\OneToMany(targetEntity="Estrelas", mappedBy="venda")
     */
    protected $vendasEstrelas;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $dataInicio;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dataFim;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->vendasProdutos = new ArrayCollection();
        $this->vendasEstrelas = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return Venda
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set dataInicio
     *
     * @param \DateTime $dataInicio
     *
     * @return Venda
     */
    public function setDataInicio($dataInicio)
    {
        $this->dataInicio = $dataInicio;

        return $this;
    }

    /**
     * Get dataInicio
     *
     * @return \DateTime
     */
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    /**
     * Set dataFim
     *
     * @param \DateTime $dataFim
     *
     * @return Venda
     */
    public function setDataFim($dataFim)
    {
        $this->dataFim = $dataFim;

        return $this;
    }

    /**
     * Get dataFim
     *
     * @return \DateTime
     */
    public function getDataFim()
    {
        return $this->dataFim;
    }

    /**
     * Set cliente
     *
     * @param Cliente $cliente
     *
     * @return Venda
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
     * Set anfitriao
     *
     * @param Anfitriao $anfitriao
     *
     * @return Venda
     */
    public function setAnfitriao(Anfitriao $anfitriao)
    {
    	$this->anfitriao = $anfitriao;
    
    	return $this;
    }
    
    /**
     * Get anfitriao
     *
     * @return Anfitriao
     */
    public function getAnfitriao()
    {
    	return $this->anfitriao;
    }

    /**
     * Add vendasProdutos
     *
     * @param VendaProduto $vendasProduto
     *
     * @return Venda
     */
    public function addVendasProdutos(VendaProduto $vendasProdutos)
    {
        $this->vendasProdutos[] = $vendasProdutos;

        return $this;
    }

    /**
     * Remove vendasProdutos
     *
     * @param VendaProduto $vendasProdutos
     */
    public function removeVendasProdutos(VendaProduto $vendasProdutos)
    {
        $this->vendasProdutos->removeElement($vendasProdutos);
    }

    /**
     * Get vendasProdutos
     *
     * @return Collection
     */
    public function getVendasProdutos()
    {
        return $this->vendasProdutos;
    }
    
    /**
     * Add vendasEstrelas
     *
     * @param Estrelas $vendasEstrelas
     *
     * @return Venda
     */
    public function addVendasEstrelas(Estrelas $vendasEstrelas)
    {
    	$this->vendasEstrelas[] = $vendasEstrelas;
    
    	return $this;
    }
    
    /**
     * Remove vendasEstrelas
     *
     * @param Estrelas $vendasEstrelas
     */
    public function removeVendasEstrelas(Estrelas $vendasEstrelas)
    {
    	$this->vendasEstrelas->removeElement($vendasEstrelas);
    }
    
    /**
     * Get vendasEstrelas
     *
     * @return Collection
     */
    public function getVendasEstrelas()
    {
    	return $this->vendasEstrelas;
    }

    /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param integer $status
     *
     * @return Venda
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}
