<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CustosMensais
{
    use Dta;

   /**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	protected $id;

    /**
     * @ORM\OneToOne(targetEntity="HistoricoPadraoCustos", inversedBy="custosHistorico")
     * @ORM\JoinColumn(name="id_historico", nullable=false, referencedColumnName="id")
     */
    protected $custo;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $valor;
    
    /**
     * Set id
     *
     * @param integer $id
     *
     * @return CustosMensais
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
     * Set custo
     *
     * @param HistoricoPadraoCustos $custo
     *
     * @return CustosMensais
     */
    public function setCusto(HistoricoPadraoCustos $custo)
    {
        $this->custo = $custo;

        return $this;
    }

    /**
     * Get custo
     *
     * @return HistoricoPadraoCustos
     */
    public function getCusto()
    {
        return $this->custo;
    }
    
    /**
     * @return float
     */
    public function getValor()
    {
    	return $this->valor;
    }
    
    /**
     * @param float $valor
     *
     * @return CustosMensais
     */
    public function setValor($valor)
    {
    	$this->valor = $valor;
    
    	return $this;
    }

}
