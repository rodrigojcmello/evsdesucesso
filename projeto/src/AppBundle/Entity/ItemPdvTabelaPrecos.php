<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ItemPdvTabelaPrecos
{
    use Dta;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Produto", inversedBy="itensPdvProduto")
     * @ORM\JoinColumn(name="id_produto", nullable=false, referencedColumnName="id_produto")
     */
    protected $produto;
    
    /**
     * @ORM\ManyToOne(targetEntity="PontoDeVenda", inversedBy="itensPdv")
     * @ORM\JoinColumn(name="id_ponto_de_venda", nullable=false, referencedColumnName="id_ponto_de_venda")
     */
    protected $pontoDeVenda;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $preco;

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
     * Set preco
     *
     * @param float $preco
     *
     * @return ItemPdvTabelaPrecos
     */
    public function setPreco($preco)
    {
    	$this->preco = $preco;
    
    	return $this;
    }
    
    /**
     * Get preco
     *
     * @return float
     */
    public function getPreco()
    {
    	return $this->preco;
    }

    /**
     * Set ativo
     *
     * @param boolean $ativo
     *
     * @return ItemPdvTabelaPrecos
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

    /**
     * Set produto
     *
     * @param Produto $produto
     *
     * @return ItemTabelaPrecos
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
     * Set pontoDeVenda
     *
     * @param PontoDeVenda $pontoDeVenda
     *
     * @return Anfitriao
     */
    public function setPontoDeVenda(PontoDeVenda $pontoDeVenda)
    {
    	$this->pontoDeVenda = $pontoDeVenda;
    
    	return $this;
    }
    
    /**
     * Get pontoDeVenda
     *
     * @return PontoDeVenda
     */
    public function getPontoDeVenda()
    {
    	return $this->pontoDeVenda;
    }
        
}
