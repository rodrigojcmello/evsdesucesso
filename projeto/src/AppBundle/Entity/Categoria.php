<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"id_categoria_pai", "nome"})})
 */
class Categoria
{
    use Dta;

    /**
     * @ORM\Column(name="id_categoria", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Categoria", mappedBy="pai")
     */
    protected $filhas;

    /**
     * @ORM\OnetoMany(targetEntity="GradeConsumo", mappedBy="categoria")
     */
    protected $gradesConsumo;

    /**
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="filhas")
     * @ORM\JoinColumn(name="id_categoria_pai", referencedColumnName="id_categoria")
     */
    protected $pai;

    /**
     * @ORM\OneToMany(targetEntity="Produto", mappedBy="categoria")
     */
    protected $produtos;

    /**
     * @ORM\Column(type="string")
     */
    protected $nome;

    /**
     * @ORM\Column(type="text")
     */
    protected $descricao;

    /**
     * Constructor
     *
     * @param string $nome
     * @param string $descricao
     */
    public function __construct($nome = '', $descricao = '')
    {
        $this->setNome($nome);
        $this->setDescricao($descricao);

        $this->filhas        = new ArrayCollection();
        $this->gradesConsumo = new ArrayCollection();
        $this->produtos      = new ArrayCollection();
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
     * @return Categoria
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
     * Set descricao
     *
     * @param string $descricao
     *
     * @return Categoria
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
     * Add filha
     *
     * @param Categoria $filha
     *
     * @return Categoria
     */
    public function addFilha(Categoria $filha)
    {
        $this->filhas[] = $filha;

        return $this;
    }

    /**
     * Remove filha
     *
     * @param Categoria $filha
     */
    public function removeFilha(Categoria $filha)
    {
        $this->filhas->removeElement($filha);
    }

    /**
     * Get filhas
     *
     * @return Categoria[]|Collection
     */
    public function getFilhas()
    {
        return $this->filhas;
    }

    /**
     * Add gradesConsumo
     *
     * @param GradeConsumo $gradesConsumo
     *
     * @return Categoria
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
     * Set pai
     *
     * @param Categoria $pai
     *
     * @return Categoria
     */
    public function setPai(Categoria $pai = null)
    {
        $this->pai = $pai;

        return $this;
    }

    /**
     * Get pai
     *
     * @return Categoria
     */
    public function getPai()
    {
        return $this->pai;
    }

    /**
     * Add produto
     *
     * @param Produto $produto
     *
     * @return Categoria
     */
    public function addProduto(Produto $produto)
    {
        $this->produtos[] = $produto;

        return $this;
    }

    /**
     * Remove produto
     *
     * @param Produto $produto
     */
    public function removeProduto(Produto $produto)
    {
        $this->produtos->removeElement($produto);
    }

    /**
     * Get produtos
     *
     * @return Collection
     */
    public function getProdutos()
    {
        return $this->produtos;
    }
    
    public function __toString() {
    	return $this->nome;
    }
}
