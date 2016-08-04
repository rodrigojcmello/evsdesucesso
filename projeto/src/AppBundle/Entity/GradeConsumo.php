<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class GradeConsumo
{
    use Dta;

    /**
     * @ORM\Column(name="id_grade_consumo", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="gradesConsumo")
     * @ORM\JoinColumn(name="id_categoria", nullable=true, referencedColumnName="id_categoria")
     */
    protected $categoria;

    /**
     * @ORM\ManyToOne(targetEntity="Produto", inversedBy="gradesConsumo")
     * @ORM\JoinColumn(name="id_produto", nullable=false, referencedColumnName="id_produto")
     */
    protected $produto;
    
    /**
     * @ORM\ManyToOne(targetEntity="Produto", inversedBy="gradesConsumoEspecifico")
     * @ORM\JoinColumn(name="id_produto_especifico", nullable=true, referencedColumnName="id_produto")
     */
    protected $idProdutoEspecifico;
    
    /**
     * @ORM\OneToMany(targetEntity="VendaProduto", mappedBy="gradeConsumo")
     */
    protected $vendaGradeConsumo;

    /**
     * @ORM\OneToMany(targetEntity="GradeConsumoPdv", mappedBy="idGradeConsumo")
     */
    protected $itensGradePdv;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $quantidade;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $descricao;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $visivel;

    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->vendaGradeConsumo     = new ArrayCollection();
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
     * @param float $quantidade
     *
     * @return GradeConsumo
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * Get quantidade
     *
     * @return float
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set categoria
     *
     * @param Categoria $categoria
     *
     * @return GradeConsumo
     */
    public function setCategoria(Categoria $categoria = null)
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
     * Add vendaGradeConsumo
     *
     * @param GradeConsumo $vendaGradeConsumo
     *
     * @return GradeConsumo
     */
    public function addVendaGradeConsumo(VendaProduto $vendaGradeConsumo)
    {
        $this->vendaGradeConsumo[] = $vendaGradeConsumo;

        return $this;
    }

    /**
     * Remove vendaGradeConsumo
     *
     * @param GradeConsumo $vendaGradeConsumo
     */
    public function removeVendaGradeConsumo(VendaProduto $vendaGradeConsumo)
    {
        $this->vendaGradeConsumo->removeElement($vendaGradeConsumo);
    }

    /**
     * Get vendaGradeConsumo
     *
     * @return Collection
     */
    public function getVendaGradeConsumo()
    {
        return $this->vendaGradeConsumo;
    }

    /**
     * Add itensGradePdv
     *
     * @param GradeConsumoPdv $itensGradePdv
     *
     * @return GradeConsumo
     */
    public function addItensGradePdv(GradeConsumoPdv $itensGradePdv)
    {
        $this->itensGradePdv[] = $itensGradePdv;

        return $this;
    }

    /**
     * Remove itensGradePdv
     *
     * @param GradeConsumoPdv $itensGradePdv
     */
    public function removeItensGradePdv(GradeConsumoPdv $itensGradePdv)
    {
        $this->itensGradePdv->removeElement($itensGradePdv);
    }

    /**
     * Get itensGradePdv
     *
     * @return Collection
     */
    public function getItensGradePdv()
    {
        return $this->itensGradePdv;
    }
    
    /**
     * Set produto
     *
     * @param Produto $produto
     *
     * @return GradeConsumo
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
     * Set idProdutoEspecifico
     *
     * @param Produto $idProdutoEspecifico
     *
     * @return GradeConsumo
     */
    public function setIdProdutoEspecifico(Produto $idProdutoEspecifico = null)
    {
    	$this->idProdutoEspecifico = $idProdutoEspecifico;
    
    	return $this;
    }
    
    /**
     * Get idProdutoEspecifico
     *
     * @return Produto
     */
    public function getIdProdutoEspecifico()
    {
    	return $this->idProdutoEspecifico;
    }
    
    /**
     * Set descricao
     *
     * @param string $descricao
     *
     * @return GradeConsumo
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
     * @return GradeConsumo
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
    
    public function __toString() {
    	return $this->descricao;
    }
}
