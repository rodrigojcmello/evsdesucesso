<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TabelaPrecos
{
    use Dta;

    /**
     * @ORM\Column(name="id_tabela_precos", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="ClassePontoDeVenda", inversedBy="tabelasPrecos")
     * @ORM\JoinColumn(name="id_classe_ponto_de_venda", nullable=false, referencedColumnName="id_classe_ponto_de_venda")
     */
    protected $classePontoDeVenda;

    /**
     * @ORM\OneToMany(targetEntity="ItemTabelaPrecos", mappedBy="tabelaPrecos")
     */
    protected $itensTabelaPrecos;

    /**
     * @ORM\Column(type="date")
     */
    protected $dataInicio;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $dataFim;

    /**
     * @ORM\Column(type="text")
     */
    protected $descricao;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->itensTabelaPrecos = new ArrayCollection();
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
     * Set dataInicio
     *
     * @param date $dataInicio
     *
     * @return TabelaPrecos
     */
    public function setDataInicio($dataInicio)
    {
        $this->dataInicio = $dataInicio;

        return $this;
    }

    /**
     * Get dataInicio
     *
     * @return date
     */
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    /**
     * Set dataFim
     *
     * @param date $dataFim
     *
     * @return TabelaPrecos
     */
    public function setDataFim($dataFim)
    {
        $this->dataFim = $dataFim;

        return $this;
    }

    /**
     * Get dataFim
     *
     * @return date
     */
    public function getDataFim()
    {
        return $this->dataFim;
    }

    /**
     * Set descricao
     *
     * @param string $descricao
     *
     * @return TabelaPrecos
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
     * Set classePontoDeVenda
     *
     * @param ClassePontoDeVenda $classePontoDeVenda
     *
     * @return TabelaPrecos
     */
    public function setClassePontoDeVenda(ClassePontoDeVenda $classePontoDeVenda)
    {
        $this->classePontoDeVenda = $classePontoDeVenda;

        return $this;
    }

    /**
     * Get classePontoDeVenda
     *
     * @return ClassePontoDeVenda
     */
    public function getClassePontoDeVenda()
    {
        return $this->classePontoDeVenda;
    }

    /**
     * Add itensTabelaPreco
     *
     * @param ItemTabelaPrecos $itensTabelaPreco
     *
     * @return TabelaPrecos
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
    
    public function __toString() {
    	return $this->descricao;
    }
}
