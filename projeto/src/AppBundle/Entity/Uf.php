<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"nome"})})
 */
class Uf
{
    use Dta;

    /**
     * @ORM\Column(name="id_uf", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="ItemTabelaPrecos", mappedBy="uf")
     */
    protected $itensTabelaPrecos;

    /**
     * @ORM\OneToMany(targetEntity="PontoDeVenda", mappedBy="uf")
     */
    protected $pontosDeVenda;

    /**
     * @ORM\Column(type="string")
     */
    protected $nome;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->itensTabelaPrecos = new ArrayCollection();
        $this->pontosDeVenda     = new ArrayCollection();
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
     * @return Uf
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
     * Add itensTabelaPreco
     *
     * @param ItemTabelaPrecos $itensTabelaPreco
     *
     * @return Uf
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
     * Add pontosDeVenda
     *
     * @param PontoDeVenda $pontosDeVenda
     *
     * @return Uf
     */
    public function addPontosDeVenda(PontoDeVenda $pontosDeVenda)
    {
        $this->pontosDeVenda[] = $pontosDeVenda;

        return $this;
    }

    /**
     * Remove pontosDeVenda
     *
     * @param PontoDeVenda $pontosDeVenda
     */
    public function removePontosDeVenda(PontoDeVenda $pontosDeVenda)
    {
        $this->pontosDeVenda->removeElement($pontosDeVenda);
    }

    /**
     * Get pontosDeVenda
     *
     * @return Collection
     */
    public function getPontosDeVenda()
    {
        return $this->pontosDeVenda;
    }
    
    public function __toString() {
    	return $this->nome;
    }
}
