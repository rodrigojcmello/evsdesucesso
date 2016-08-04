<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ItemTabelaPrecos
{
    use Dta;

    /**
     * @ORM\Column(name="id_item_tabela_precos", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Produto", inversedBy="itensTabelaPrecos")
     * @ORM\JoinColumn(name="id_produto", nullable=false, referencedColumnName="id_produto")
     */
    protected $produto;

    /**
     * @ORM\ManyToOne(targetEntity="TabelaPrecos", inversedBy="itensTabelaPrecos")
     * @ORM\JoinColumn(name="id_tabela_precos", nullable=false, referencedColumnName="id_tabela_precos")
     */
    protected $tabelaPrecos;

    /**
     * @ORM\ManyToOne(targetEntity="Uf", inversedBy="itensTabelaPrecos")
     * @ORM\JoinColumn(name="id_uf", nullable=false, referencedColumnName="id_uf")
     */
    protected $uf;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $pontoValor;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $venda;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $custo25;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $custo35;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $custo42;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $custo50;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $aceitaCartelaDigital;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $precoAberto;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $creditoCartela;

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
     * Set pontoValor
     *
     * @param float $pontoValor
     *
     * @return ItemTabelaPrecos
     */
    public function setPontoValor($pontoValor)
    {
        $this->pontoValor = $pontoValor;

        return $this;
    }

    /**
     * Get pontoValor
     *
     * @return float
     */
    public function getPontoValor()
    {
        return $this->pontoValor;
    }
    
    /**
     * Set aceitaCartelaDigital
     *
     * @param boolean $aceitaCartelaDigital
     *
     * @return ItemTabelaPrecos
     */
    public function setAceitaCartelaDigital($aceitaCartelaDigital)
    {
    	$this->aceitaCartelaDigital = $aceitaCartelaDigital;
    
    	return $this;
    }
    
    /**
     * Get aceitaCartelaDigital
     *
     * @return boolean
     */
    public function getAceitaCartelaDigital()
    {
    	return $this->aceitaCartelaDigital;
    }
    
    /**
     * Set precoAberto
     *
     * @param boolean $precoAberto
     *
     * @return ItemTabelaPrecos
     */
    public function setPrecoAberto($precoAberto)
    {
    	$this->precoAberto = $precoAberto;
    
    	return $this;
    }
    
    /**
     * Get precoAberto
     *
     * @return boolean
     */
    public function getPrecoAberto()
    {
    	return $this->precoAberto;
    }
    
    /**
     * Set creditoCartela
     *
     * @param boolean $creditoCartela
     *
     * @return ItemTabelaPrecos
     */
    public function setCreditoCartela($creditoCartela)
    {
    	$this->creditoCartela = $creditoCartela;
    
    	return $this;
    }
    
    /**
     * Get creditoCartela
     *
     * @return boolean
     */
    public function getCreditoCartela()
    {
    	return $this->creditoCartela;
    }

    /**
     * Set venda
     *
     * @param float $venda
     *
     * @return ItemTabelaPrecos
     */
    public function setVenda($venda)
    {
        $this->venda = $venda;

        return $this;
    }

    /**
     * Get venda
     *
     * @return float
     */
    public function getVenda()
    {
        return $this->venda;
    }

    /**
     * Set custo25
     *
     * @param float $custo25
     *
     * @return ItemTabelaPrecos
     */
    public function setCusto25($custo25)
    {
        $this->custo25 = $custo25;

        return $this;
    }

    /**
     * Get custo25
     *
     * @return float
     */
    public function getCusto25()
    {
        return $this->custo25;
    }

    /**
     * Set custo35
     *
     * @param float $custo35
     *
     * @return ItemTabelaPrecos
     */
    public function setCusto35($custo35)
    {
        $this->custo35 = $custo35;

        return $this;
    }

    /**
     * Get custo35
     *
     * @return float
     */
    public function getCusto35()
    {
        return $this->custo35;
    }

    /**
     * Set custo42
     *
     * @param float $custo42
     *
     * @return ItemTabelaPrecos
     */
    public function setCusto42($custo42)
    {
        $this->custo42 = $custo42;

        return $this;
    }

    /**
     * Get custo42
     *
     * @return float
     */
    public function getCusto42()
    {
        return $this->custo42;
    }

    /**
     * Set custo50
     *
     * @param float $custo50
     *
     * @return ItemTabelaPrecos
     */
    public function setCusto50($custo50)
    {
        $this->custo50 = $custo50;

        return $this;
    }

    /**
     * Get custo50
     *
     * @return float
     */
    public function getCusto50()
    {
        return $this->custo50;
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
     * Set tabelaPrecos
     *
     * @param TabelaPrecos $tabelaPrecos
     *
     * @return ItemTabelaPrecos
     */
    public function setTabelaPrecos(TabelaPrecos $tabelaPrecos)
    {
        $this->tabelaPrecos = $tabelaPrecos;

        return $this;
    }

    /**
     * Get tabelaPrecos
     *
     * @return TabelaPrecos
     */
    public function getTabelaPrecos()
    {
        return $this->tabelaPrecos;
    }

    /**
     * Set uf
     *
     * @param Uf $uf
     *
     * @return ItemTabelaPrecos
     */
    public function setUf(Uf $uf)
    {
        $this->uf = $uf;

        return $this;
    }

    /**
     * Get uf
     *
     * @return Uf
     */
    public function getUf()
    {
        return $this->uf;
    }
    
    /**
     * Set uf
     *
     * @param Uf $uf
     *
     * @return ItemTabelaPrecos
     */
    public function setIdUf(Uf $uf)
    {
    	$this->uf = $uf;
    
    	return $this;
    }
    
    /**
     * Get uf
     *
     * @return Uf
     */
    public function getIdUf()
    {
    	return $this->uf;
    }
    
    public function __toString() {
    	return $this->uf->getNome();
    }
}
