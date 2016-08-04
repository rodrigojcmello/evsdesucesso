<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class FormaPagamento
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
     * @ORM\OneToMany(targetEntity="VendaProduto", mappedBy="formaPagamento")
     */
    protected $vendasFormaPagamento;
    
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->vendasFormaPagamento = new ArrayCollection();
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
     * Add vendasFormaPagamento
     *
     * @param VendaProduto $vendasFormaPagamento
     *
     * @return FormaPagamento
     */
    public function addVendasFormaPagamento(VendaProduto $vendasFormaPagamento)
    {
    	$this->vendasFormaPagamento[] = $vendasFormaPagamento;
    
    	return $this;
    }
    
    /**
     * Remove vendasFormaPagamento
     *
     * @param VendaProduto $vendasFormaPagamento
     */
    public function removeVendasFormaPagamento(VendaProduto $vendasFormaPagamento)
    {
    	$this->vendasFormaPagamento->removeElement($vendasFormaPagamento);
    }
    
    /**
     * Get vendasFormaPagamento
     *
     * @return Collection
     */
    public function getVendasFormaPagamento()
    {
    	return $this->vendasFormaPagamento;
    }
}
