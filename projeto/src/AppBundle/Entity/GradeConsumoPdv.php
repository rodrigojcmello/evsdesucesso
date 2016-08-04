<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class GradeConsumoPdv
{
    use Dta;

    /**
     * @ORM\Column(name="id_grade_consumo_pdv", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="PontoDeVenda", inversedBy="itensGradePdv")
     * @ORM\JoinColumn(name="id_ponto_de_venda", nullable=false, referencedColumnName="id_ponto_de_venda", onDelete="CASCADE")
     */
    protected $idPontoDeVenda;

    /**
     * @ORM\ManyToOne(targetEntity="GradeConsumo", inversedBy="itensGradePdv")
     * @ORM\JoinColumn(name="id_grade_consumo", nullable=false, referencedColumnName="id_grade_consumo", onDelete="CASCADE")
     */
    protected $idGradeConsumo;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $quantidade;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $ativo;

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
     * Set idPontoDeVenda
     *
     * @param PontoDeVenda $idPontoDeVenda
     *
     * @return GradeConsumoPdv
     */
    public function setIdPontoDeVenda(PontoDeVenda $idPontoDeVenda)
    {
        $this->idPontoDeVenda = $idPontoDeVenda;

        return $this;
    }

    /**
     * Get idPontoDeVenda
     *
     * @return PontoDeVenda
     */
    public function getIdPontoDeVenda()
    {
        return $this->idPontoDeVenda;
    }
    
    /**
     * Set idGradeConsumo
     *
     * @param GradeConsumo $idGradeConsumo
     *
     * @return GradeConsumoPdv
     */
    public function setIdGradeConsumo(GradeConsumo $idGradeConsumo)
    {
        $this->idGradeConsumo = $idGradeConsumo;

        return $this;
    }

    /**
     * Get IdGradeConsumo
     *
     * @return GradeConsumo
     */
    public function getIdGradeConsumo()
    {
        return $this->idGradeConsumo;
    }
    
    
    /**
     * Set quantidade
     *
     * @param float $quantidade
     *
     * @return GradeConsumoPdv
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
     * Set ativo
     *
     * @param boolean $ativo
     *
     * @return GradeConsumoPdv
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * Get ativo
     *
     * @return float
     */
    public function getAtivo()
    {
        return $this->ativo;
    }
    
}
