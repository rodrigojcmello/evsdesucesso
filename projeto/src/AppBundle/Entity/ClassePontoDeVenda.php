<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ClassePontoDeVenda
{
    use Dta;

    /**
     * @ORM\Column(name="id_classe_ponto_de_venda", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="PontoDeVenda", mappedBy="classePontoDeVenda")
     */
    protected $pontosDeVenda;

    /**
     * @ORM\OneToMany(targetEntity="TabelaPrecos", mappedBy="classePontoDeVenda")
     */
    protected $tabelasPrecos;

    /**
     * @ORM\Column(type="text")
     */
    protected $descricao;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pontosDeVenda = new ArrayCollection();
        $this->tabelasPrecos = new ArrayCollection();
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
     * Set descricao
     *
     * @param string $descricao
     *
     * @return ClassePontoDeVenda
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
     * Add pontosDeVenda
     *
     * @param PontoDeVenda $pontosDeVenda
     *
     * @return ClassePontoDeVenda
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

    /**
     * Add tabelasPreco
     *
     * @param TabelaPrecos $tabelasPreco
     *
     * @return ClassePontoDeVenda
     */
    public function addTabelasPreco(TabelaPrecos $tabelasPreco)
    {
        $this->tabelasPrecos[] = $tabelasPreco;

        return $this;
    }

    /**
     * Remove tabelasPreco
     *
     * @param TabelaPrecos $tabelasPreco
     */
    public function removeTabelasPreco(TabelaPrecos $tabelasPreco)
    {
        $this->tabelasPrecos->removeElement($tabelasPreco);
    }

    /**
     * Get tabelasPrecos
     *
     * @return Collection
     */
    public function getTabelasPrecos()
    {
        return $this->tabelasPrecos;
    }
    
    public function __toString() {
    	return $this->descricao;
    }
}
