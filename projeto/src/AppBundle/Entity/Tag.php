<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"nome"})})
 */
class Tag
{
    use Dta;

    /**
     * @ORM\Column(name="id_tag", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="TagProduto", mappedBy="tag")
     */
    protected $tagsProduto;
    
    /**
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="parentTag")
     * @ORM\JoinColumn(name="id_parent", nullable=true, referencedColumnName="id_tag")
     */
    protected $tagProduto;
    
    /**
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="tagProduto")
     */
    protected $parentTag;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $visivel;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $exibirAutoProdutos;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $exibirCategoria;

    /**
     * @ORM\Column(type="string")
     */
    protected $nome;

    /**
     * Constructor
     *
     * @param string $nome
     */
    public function __construct($nome = '')
    {
        $this->setNome($nome);

        $this->filhas      = new ArrayCollection();
        $this->tagsProduto = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Tag
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
     * Set nome
     *
     * @param string $nome
     *
     * @return Tag
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }
    
    /**
     * Get parentTag
     *
     * @return integer
     */
    public function getParentTag()
    {
    	return $this->parentTag;
    }
    
    /**
     * Set parentTag
     *
     * @param integer $parentTag
     *
     * @return Tag
     */
    public function setParentTag($parentTag)
    {
    	$this->parentTag = $parentTag;
    
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
     * Set visivel
     *
     * @param boolean $visivel
     *
     * @return Tag
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
     * Set exibirAutoProdutos
     *
     * @param boolean $exibirAutoProdutos
     *
     * @return Tag
     */
    public function setExibirAutoProdutos($exibirAutoProdutos)
    {
    	$this->exibirAutoProdutos = $exibirAutoProdutos;
    
    	return $this;
    }
    
    /**
     * Get exibirAutoProdutos
     *
     * @return boolean
     */
    public function getExibirAutoProdutos()
    {
    	return $this->exibirAutoProdutos;
    }

    /**
     * Set exibirCategoria
     *
     * @param boolean $exibirCategoria
     *
     * @return Tag
     */
    public function setExibirCategoria($exibirCategoria)
    {
    	$this->exibirCategoria = $exibirCategoria;
    
    	return $this;
    }
    
    /**
     * Get exibirCategoria
     *
     * @return boolean
     */
    public function getExibirCategoria()
    {
    	return $this->exibirCategoria;
    }
    
    /**
     * Set tagProduto
     *
     * @param integer $tagProduto
     *
     * @return Tag
     */
    public function setTagProduto($tagProduto)
    {
    	$this->tagProduto = $tagProduto;
    
    	return $this;
    }
    
    /**
     * Get tagProduto
     *
     * @return integer
     */
    public function getTagProduto()
    {
    	return $this->tagProduto;
    }
    
    /**
     * Add tagsProduto
     *
     * @param TagProduto $tagsProduto
     *
     * @return Tag
     */
    public function addTagsProduto(TagProduto $tagsProduto)
    {
        $this->tagsProduto[] = $tagsProduto;

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
}
