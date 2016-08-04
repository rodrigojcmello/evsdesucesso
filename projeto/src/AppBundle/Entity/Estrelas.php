<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Estrelas
{
    use Dta;

   /**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="estrelasCliente")
     * @ORM\JoinColumn(name="id_cliente", nullable=false, referencedColumnName="id_cliente")
     */
    protected $cliente;
    
    /**
     * @ORM\ManyToOne(targetEntity="PontoDeVenda", inversedBy="pontosEstrela")
     * @ORM\JoinColumn(name="id_ponto_de_venda", nullable=false, referencedColumnName="id_ponto_de_venda")
     */
    protected $pontosDeVenda;
    
    /**
     * @ORM\ManyToOne(targetEntity="Venda", inversedBy="vendasEstrelas")
     * @ORM\JoinColumn(name="id_venda", nullable=true, referencedColumnName="id_venda")
     */
    protected $venda;
    
    /**
     * @ORM\ManyToOne(targetEntity="OrigemEstrela", inversedBy="origemEstrelas")
     * @ORM\JoinColumn(name="id_origem_estrela", nullable=false, referencedColumnName="id")
     */
    protected $origem;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $quantidade;
    
    /**
     * Set id
     *
     * @param integer $id
     *
     * @return CartelaDigital
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
     * Set cliente
     *
     * @param Cliente $cliente
     *
     * @return CartelaDigital
     */
    public function setCliente(Cliente $cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Add pontosDeVenda
     *
     * @param PontoDeVenda $pontosDeVenda
     *
     * @return PontoDeVenda
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
     * @return float
     */
    public function getQuantidade()
    {
    	return $this->quantidade;
    }
    
    /**
     * @param float $quantidade
     *
     * @return Estrelas
     */
    public function setQuantidade($tipo)
    {
    	$this->quantidade = $quantidade;
    
    	return $this;
    }

}
