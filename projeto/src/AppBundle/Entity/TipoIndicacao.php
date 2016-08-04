<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TipoIndicacao
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
     * @ORM\OneToMany(targetEntity="Cliente", mappedBy="tipoIndicacao")
     */
    protected $clienteTipoIndicacao;
    
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->clienteTipoIndicacao = new ArrayCollection();
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
     * Add clienteTipoIndicacao
     *
     * @param Cliente $clienteTipoIndicacao
     *
     * @return TipoIndicacao
     */
    public function addClienteTipoIndicacao(Cliente $clienteTipoIndicacao)
    {
    	$this->clienteTipoIndicacao[] = $clienteTipoIndicacao;
    
    	return $this;
    }
    
    /**
     * Remove clienteTipoIndicacao
     *
     * @param Cliente $clienteTipoIndicacao
     */
    public function removeClienteTipoIndicacao(Cliente $clienteTipoIndicacao)
    {
    	$this->clienteTipoIndicacao->removeElement($clienteTipoIndicacao);
    }
    
    /**
     * Get clienteTipoIndicacao
     *
     * @return Collection
     */
    public function getClienteTipoIndicacao()
    {
    	return $this->clienteTipoIndicacao;
    }
}
