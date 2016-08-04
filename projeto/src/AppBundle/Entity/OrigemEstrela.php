<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class OrigemEstrela
{
    use Dta;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $descricao;

    /**
     * @ORM\OneToMany(targetEntity="Estrelas", mappedBy="origem")
     */
    protected $origemEstrelas;
    
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->origemEstrelas = new ArrayCollection();
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
     * Set descricao
     *
     * @param string $descricao
     *
     * @return FormaPagamento
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
     * Add origemEstrelas
     *
     * @param Estrelas $origemEstrelas
     *
     * @return OrigemEstrela
     */
    public function addOrigemEstrelas(Estrelas $origemEstrelas)
    {
    	$this->origemEstrelas[] = $origemEstrelas;
    
    	return $this;
    }
    
    /**
     * Remove origemEstrelas
     *
     * @param Estrelas $origemEstrelas
     */
    public function removeOrigemEstrelas(Estrelas $origemEstrelas)
    {
    	$this->origemEstrelas->removeElement($origemEstrelas);
    }
    
    /**
     * Get origemEstrelas
     *
     * @return Collection
     */
    public function getOrigemEstrelas()
    {
    	return $this->origemEstrelas;
    }
}
