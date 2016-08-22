<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(columns={"sku"})})
 */
class Produto
{
    use Dta;

    /**
     * @ORM\Column(name="id_produto", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="produtos")
     * @ORM\JoinColumn(name="id_categoria", nullable=false, referencedColumnName="id_categoria")
     */
    protected $categoria;

    /**
     * @ORM\OneToMany(targetEntity="GradeConsumo", mappedBy="produto")
     */
    protected $gradesConsumo;

    /**
     * @ORM\OneToMany(targetEntity="GradeConsumo", mappedBy="idProdutoEspecifico")
     */
    protected $gradesConsumoEspecifico;

    /**
     * @ORM\OneToOne(targetEntity="ProdutoImagem", mappedBy="produto")
     */
    protected $imagemProduto;

    /**
     * @ORM\OneToMany(targetEntity="TagProduto", mappedBy="produto")
     */
    protected $tagsProduto;

    /**
     * @ORM\OneToMany(targetEntity="VendaProduto", mappedBy="produto")
     */
    protected $vendasProdutos;

    /**
     * @ORM\OneToMany(targetEntity="ItemTabelaPrecos", mappedBy="produto")
     */
    protected $itensTabelaPrecos;

    /**
     * @ORM\OneToMany(targetEntity="ItemPdvTabelaPrecos", mappedBy="produto")
     */
    protected $itensPdvProduto;

    /**
     * @ORM\Column(type="string")
     */
    protected $nome;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ean;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $sku;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $skuint;

    /**
     * @ORM\Column(type="text")
     */
    protected $descricao;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $visivel;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $apelido;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $quantidadeEstrelas;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gradesConsumo     = new ArrayCollection();
        $this->gradesConsumoEspecifico    = new ArrayCollection();
        $this->tagsProduto       = new ArrayCollection();
        $this->vendasProdutos    = new ArrayCollection();
        $this->itensTabelaPrecos = new ArrayCollection();
        $this->itensPdvProduto = new ArrayCollection();
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
     * Set nome
     *
     * @param string $nome
     *
     * @return Produto
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set imagemProduto
     *
     * @param string $imagemProduto
     *
     * @return Produto
     */
    public function setImagemProduto($imagemProduto)
    {
    	$this->imagemProduto = $imagemProduto;

    	return $this;
    }

    /**
     * Get imagemProduto
     *
     * @return string
     */
    public function getImagemProduto()
    {
    	return $this->imagemProduto;
    }

    /**
     * Set apelido
     *
     * @param string $apelido
     *
     * @return Produto
     */
    public function setApelido($apelido)
    {
    	$this->apelido = $apelido;

    	return $this;
    }

    /**
     * Get apelido
     *
     * @return string
     */
    public function getApelido()
    {
    	return $this->apelido;
    }

    /**
     * Set quantidadeEstrelas
     *
     * @param integer $quantidadeEstrelas
     *
     * @return Produto
     */
    public function setQuantidadeEstrelas($quantidadeEstrelas)
    {
    	$this->quantidadeEstrelas = $quantidadeEstrelas;

    	return $this;
    }

    /**
     * Get quantidadeEstrelas
     *
     * @return integer
     */
    public function getQuantidadeEstrelas()
    {
    	return $this->quantidadeEstrelas;
    }

    /**
     * Set ean
     *
     * @param string $ean
     *
     * @return Produto
     */
    public function setEan($ean)
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * Get ean
     *
     * @return string
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * Set sku
     *
     * @param string $sku
     *
     * @return Produto
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set skuint
     *
     * @param string $skuint
     *
     * @return Produto
     */
    public function setSkuint($skuint)
    {
        $this->skuint = $skuint;

        return $this;
    }

    /**
     * Get skuint
     *
     * @return string
     */
    public function getSkuint()
    {
        return $this->skuint;
    }


    /**
     * Set descricao
     *
     * @param string $descricao
     *
     * @return Produto
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get descricao
     *
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set visivel
     *
     * @param boolean $visivel
     *
     * @return Produto
     */
    public function setVisivel($visivel)
    {
        $this->visivel = $visivel;

        return $this;
    }

    /**
     * Get visivel
     *
     * @return boolean
     */
    public function getVisivel()
    {
        return $this->visivel;
    }

    /**
     * Set categoria
     *
     * @param Categoria $categoria
     *
     * @return Produto
     */
    public function setCategoria(Categoria $categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return Categoria
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Add gradesConsumo
     *
     * @param GradeConsumo $gradesConsumo
     *
     * @return Produto
     */
    public function addGradesConsumo(GradeConsumo $gradesConsumo)
    {
        $this->gradesConsumo[] = $gradesConsumo;

        return $this;
    }

    /**
     * Remove gradesConsumo
     *
     * @param GradeConsumo $gradesConsumo
     */
    public function removeGradesConsumo(GradeConsumo $gradesConsumo)
    {
        $this->gradesConsumo->removeElement($gradesConsumo);
    }

    /**
     * Get gradesConsumo
     *
     * @return Collection
     */
    public function getGradesConsumo()
    {
        return $this->gradesConsumo;
    }

    /**
     * Add gradesConsumoEspecifico
     *
     * @param GradeConsumo $gradesConsumoEspecifico
     *
     * @return Produto
     */
    public function addGradesConsumoEspecifico(GradeConsumo $gradesConsumoEspecifico)
    {
    	$this->gradesConsumoEspecifico[] = $gradesConsumoEspecifico;

    	return $this;
    }

    /**
     * Remove gradesConsumoEspecifico
     *
     * @param GradeConsumo $gradesConsumoEspecifico
     */
    public function removeGradesConsumoEspecifico(GradeConsumo $gradesConsumoEspecifico)
    {
    	$this->gradesConsumoEspecifico->removeElement($gradesConsumoEspecifico);
    }

    /**
     * Get gradesConsumoEspecifico
     *
     * @return Collection
     */
    public function getGradesConsumoEspecifico()
    {
    	return $this->gradesConsumoEspecifico;
    }


    /**
     * Add tagsProduto
     *
     * @param TagProduto $tagsProduto
     *
     * @return Produto
     */
    public function addTagsProduto(TagProduto $tagsProduto)
    {
        $this->tagsProduto[] = $tagsProduto;

        return $this;
    }

    /**
     * Reset tagsProduto
     *
     * @return Produto
     */
    public function resetTagsProduto(  )
    {

        $this->tagsProduto = null ;

        return $this;
    }


    /**
     * Remove tagsProduto
     *
     * @param TagProduto $tagsProduto
     */
    public function removeTagsProduto(TagProduto $tagsProduto)
    {
        $this->tagsProduto->removeElement($tagsProduto);
    }

    /**
     * Get tagsProduto
     *
     * @return Collection
     */
    public function getTagsProduto()
    {
        return $this->tagsProduto;
    }

    /**
     * Add vendasProduto
     *
     * @param VendaProduto $vendasProduto
     *
     * @return Produto
     */
    public function addVendasProduto(VendaProduto $vendasProduto)
    {
        $this->vendasProdutos[] = $vendasProduto;

        return $this;
    }

    /**
     * Remove vendasProduto
     *
     * @param VendaProduto $vendasProduto
     */
    public function removeVendasProduto(VendaProduto $vendasProduto)
    {
        $this->vendasProdutos->removeElement($vendasProduto);
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
     * Add itensTabelaPreco
     **
     * @return Produto
     */
    public function resetItensTabelaPreco()
    {
        $this->itensTabelaPrecos = new ArrayCollection();

        return $this;
    }


    /**
     * Add itensTabelaPreco
     *
     * @param ItemTabelaPrecos $itensTabelaPreco
     *
     * @return Produto
     */
    public function addItensTabelaPreco(ItemTabelaPrecos $itensTabelaPreco)
    {
        $this->itensTabelaPrecos[] = $itensTabelaPreco;

        return $this;
    }

    /**
     * Remove itensTabelaPreco
     *
     * @param ItemTabelaPrecos $itensTabelaPreco
     */
    public function removeItensTabelaPreco(ItemTabelaPrecos $itensTabelaPreco)
    {
        $this->itensTabelaPrecos->removeElement($itensTabelaPreco);
    }

    /**
     * Get itensTabelaPrecos
     *
     * @return Collection
     */
    public function getItensTabelaPrecos()
    {
        return $this->itensTabelaPrecos;
    }

    /**
     * Add itensPdvProduto
     *
     * @param ItemPdvTabelaPrecos $itensPdvProduto
     *
     * @return Produto
     */
    public function addItensPdvProduto(ItemPdvTabelaPrecos $itensPdvProduto)
    {
    	$this->itensPdvProduto[] = $itensPdvProduto;

    	return $this;
    }

    /**
     * Remove itensPdvProduto
     *
     * @param ItemPdvTabelaPrecos $itensPdvProduto
     */
    public function removeItensPdvProduto(ItemPdvTabelaPrecos $itensPdvProduto)
    {
    	$this->itensPdvProduto->removeElement($itensPdvProduto);
    }

    /**
     * Get itensPdvProduto
     *
     * @return Collection
     */
    public function getItensPdvProduto()
    {
    	return $this->itensPdvProduto;
    }

    public function __toString() {
    	return $this->nome;
    }
}
