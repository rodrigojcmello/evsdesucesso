<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User;

/**
 * @ORM\Entity
 */
class Anfitriao extends User
{
    use Dta;

    /**
     * @ORM\Column(name="id_anfitriao", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Cliente", mappedBy="anfitriao")
     */
    protected $clientes;
    
    /**
     * @ORM\OneToMany(targetEntity="Venda", mappedBy="anfitriao")
     */
    protected $vendasAnfitriao;
    
    /**
     * @ORM\OneToMany(targetEntity="PontoDeVenda", mappedBy="anfitriao")
     */
    protected $pontoDeVendaAnfitriao;
    
    /**
     * @ORM\OneToMany(targetEntity="PontoDeVenda", mappedBy="anfitriaoMaster")
     */
    protected $pontoDeVendaAnfitriaoMaster;

    /**
     * @ORM\ManyToOne(targetEntity="PontoDeVenda", inversedBy="anfitrioes")
     * @ORM\JoinColumn(name="id_ponto_de_venda", nullable=true, referencedColumnName="id_ponto_de_venda")
     */
    public $pontoDeVenda;

    /**
     * @ORM\Column(type="string")
     */
    protected $nome;

    /**
     * @ORM\Column(type="string")
     */
    protected $endereco;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $faixa;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $telefone;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $cpf;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->clientes               = new ArrayCollection();
        $this->anfitrioesPontoDeVenda = new ArrayCollection();
        $this->vendasAnfitriao        = new ArrayCollection();
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
     * @return Anfitriao
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
     * Set endereco
     *
     * @param string $endereco
     *
     * @return Anfitriao
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;

        return $this;
    }

    /**
     * Get endereco
     *
     * @return string
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * Add cliente
     *
     * @param Cliente $cliente
     *
     * @return Anfitriao
     */
    public function addCliente(Cliente $cliente)
    {
        $this->clientes[] = $cliente;

        return $this;
    }

    /**
     * Remove cliente
     *
     * @param Cliente $cliente
     */
    public function removeCliente(Cliente $cliente)
    {
        $this->clientes->removeElement($cliente);
    }

    /**
     * Get clientes
     *
     * @return Collection
     */
    public function getClientes()
    {
        return $this->clientes;
    }
    
    /**
     * Add vendasAnfitriao
     *
     * @param Anfitriao $vendasAnfitriao
     *
     * @return Anfitriao
     */
    public function addVendasAnfitriao(Anfitriao $vendasAnfitriao)
    {
    	$this->vendasAnfitriao[] = $vendasAnfitriao;
    
    	return $this;
    }
    
    /**
     * Remove vendasAnfitriao
     *
     * @param Anfitriao $vendasAnfitriao
     */
    public function removeVendasAnfitriao(Anfitriao $vendasAnfitriao)
    {
    	$this->vendasAnfitriao->removeElement($vendasAnfitriao);
    }
    
    /**
     * Get vendasAnfitriao
     *
     * @return Collection
     */
    public function getVendasAnfitriao()
    {
    	return $this->vendasAnfitriao;
    }

    /**
     * Set pontoDeVenda
     *
     * @param PontoDeVenda $pontoDeVenda
     *
     * @return Anfitriao
     */
    public function setPontoDeVenda(PontoDeVenda $pontoDeVenda)
    {
        $this->pontoDeVenda = $pontoDeVenda;

        return $this;
    }

    /**
     * Get pontoDeVenda
     *
     * @return PontoDeVenda
     */
    public function getPontoDeVenda()
    {
        return $this->pontoDeVenda;
    }

    /**
     * Get pontoDeVendaAnfitriao
     *
     * @return PontoDeVendaAnfitriao
     */
    public function getPontoDeVendaAnfitriao()
    {
        return $this->pontoDeVendaAnfitriao;
    }

    /**
     * Get pontoDeVendaAnfitriaoMaster
     *
     * @return PontoDeVendaAnfitriaoMaster
     */
    public function getPontoDeVendaAnfitriaoMaster()
    {
        return $this->pontoDeVendaAnfitriaoMaster;
    }

    /**
     * @return integer
     */
    public function getFaixa()
    {
        return $this->faixa;
    }

    /**
     * @param integer $faixa
     *
     * @return Anfitriao
     */
    public function setFaixa($faixa)
    {
        $this->faixa = $faixa;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getTelefone()
    {
    	return $this->telefone;
    }
    
    /**
     * @param string $telefone
     *
     * @return Anfitriao
     */
    public function setTelefone($telefone)
    {
    	$this->telefone = $telefone;
    
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getCpf()
    {
    	return $this->cpf;
    }
    
    /**
     * @param string $cpf
     *
     * @return Anfitriao
     */
    public function setCpf($cpf)
    {
    	$this->cpf = $cpf;
    
    	return $this;
    }
}
