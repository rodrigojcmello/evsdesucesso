<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class VendaProduto
{
    use Dta;

    /**
     * @ORM\Column(name="id_venda_produto", type="string")
     * @ORM\Id
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Produto", inversedBy="vendasProdutos")
     * @ORM\JoinColumn(name="id_produto", nullable=false, referencedColumnName="id_produto")
     */
    protected $produto;

    /**
     * @ORM\ManyToOne(targetEntity="Venda", inversedBy="vendasProdutos")
     * @ORM\JoinColumn(name="id_venda", nullable=false, referencedColumnName="id_venda")
     */
    protected $venda;
    
    /**
     * @ORM\ManyToOne(targetEntity="FormaPagamento", inversedBy="vendasFormaPagamento")
     * @ORM\JoinColumn(name="id_forma_pagamento", nullable=true, referencedColumnName="id")
     */
    protected $formaPagamento;

    /**
     * @ORM\Column(type="float")
     */
    protected $quantidade;

    /**
     * @ORM\Column(type="float")
     */
    protected $valorVenda;

    /**
     * @ORM\Column(type="float")
     */
    protected $valorCusto;
    
    /**
     * @ORM\ManyToOne(targetEntity="VendaProduto", inversedBy="parentProduto")
     * @ORM\JoinColumn(name="id_parent", nullable=true, referencedColumnName="id_venda_produto")
     */
    protected $vendaProduto;
    
    /**
     * @ORM\OneToMany(targetEntity="VendaProduto", mappedBy="vendaProduto")
     */
    protected $parentProduto;
    
    /**
     * @ORM\ManyToOne(targetEntity="GradeConsumo", inversedBy="vendaGradeConsumo")
     * @ORM\JoinColumn(name="id_grade_consumo", nullable=true, referencedColumnName="id_grade_consumo")
     */
    protected $gradeConsumo;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $quantidade1;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $valorCusto1;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $pontosDeVolume;

    /**
     * Set id
     *
     * @param string $id
     *
     * @return VendaProduto
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
     * Set quantidade
     *
     * @param integer $quantidade
     *
     * @return VendaProduto
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * Get quantidade
     *
     * @return integer
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }
    
    /**
     * Set quantidade1
     *
     * @param integer $quantidade1
     *
     * @return VendaProduto
     */
    public function setQuantidade1($quantidade1)
    {
    	$this->quantidade1 = $quantidade1;
    
    	return $this;
    }
    
    /**
     * Get quantidade1
     *
     * @return integer
     */
    public function getQuantidade1()
    {
    	return $this->quantidade1;
    }

    /**
     * Set produto
     *
     * @param Produto $produto
     *
     * @return VendaProduto
     */
    public function setProduto(Produto $produto)
    {
        $this->produto = $produto;

        return $this;
    }

    /**
     * Get produto
     *
     * @return Produto
     */
    public function getProduto()
    {
        return $this->produto;
    }

    /**
     * Set venda
     *
     * @param Venda $venda
     *
     * @return VendaProduto
     */
    public function setVenda(Venda $venda)
    {
        $this->venda = $venda;

        return $this;
    }

    /**
     * Get venda
     *
     * @return Venda
     */
    public function getVenda()
    {
        return $this->venda;
    }
    
    /**
     * Set vendaProduto
     *
     * @param Venda $vendaProduto
     *
     * @return VendaProduto
     */
    public function setVendaProduto(Produto $vendaProduto)
    {
    	$this->vendaProduto = $vendaProduto;
    
    	return $this;
    }
    
    /**
     * Get vendaProduto
     *
     * @return Produto
     */
    public function getVendaProduto()
    {
    	return $this->vendaProduto;
    }
    
    /**
     * Set gradeConsumo
     *
     * @param Venda $gradeConsumo
     *
     * @return VendaProduto
     */
    public function setGradeConsumo(GradeConsumo $gradeConsumo)
    {
    	$this->gradeConsumo = $gradeConsumo;
    
    	return $this;
    }
    
    /**
     * Get gradeConsumo
     *
     * @return GradeConsumo
     */
    public function getGradeConsumo()
    {
    	return $this->gradeConsumo;
    }

    /**
     * @return float
     */
    public function getValorVenda()
    {
        return $this->valorVenda;
    }

    /**
     * @param float $valorVenda
     *
     * @return VendaProduto
     */
    public function setValorVenda($valorVenda)
    {
        $this->valorVenda = $valorVenda;

        return $this;
    }

    /**
     * @return float
     */
    public function getValorCusto()
    {
        return $this->valorCusto;
    }

    /**
     * @param float $valorCusto
     *
     * @return VendaProduto
     */
    public function setValorCusto($valorCusto)
    {
        $this->valorCusto = $valorCusto;

        return $this;
    }
    
    /**
     * @return float
     */
    public function getValorCusto1()
    {
    	return $this->valorCusto1;
    }
    
    /**
     * @param float $valorCusto1
     *
     * @return VendaProduto
     */
    public function setValorCusto1($valorCusto1)
    {
    	$this->valorCusto1 = $valorCusto1;
    
    	return $this;
    }
    
    /**
     * @return float
     */
    public function getPontosDeVolume()
    {
    	return $this->pontosDeVolume;
    }
    
    /**
     * @param float $pontosDeVolume
     *
     * @return VendaProduto
     */
    public function setPontosDeVolume($pontosDeVolume)
    {
    	$this->pontosDeVolume = $pontosDeVolume;
    
    	return $this;
    }
    
    public function __toString() {
    	return $this->venda->getId();
    }
}
