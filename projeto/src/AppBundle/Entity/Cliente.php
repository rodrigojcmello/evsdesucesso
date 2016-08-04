<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Cliente
{
    use Dta;

    /**
     * @ORM\Column(name="id_cliente", type="string")
     * @ORM\Id
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Anfitriao", inversedBy="clientes")
     * @ORM\JoinColumn(name="id_anfitriao", nullable=false, referencedColumnName="id_anfitriao")
     */
    protected $anfitriao;
    
    /**
     * @ORM\ManyToOne(targetEntity="TipoIndicacao", inversedBy="clienteTipoIndicacao")
     * @ORM\JoinColumn(name="id_tipo_indicacao", nullable=true, referencedColumnName="id")
     */
    protected $tipoIndicacao;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="clienteIndicou")
     * @ORM\JoinColumn(name="id_cliente_indicou", nullable=true, referencedColumnName="id_cliente")
     */
    protected $clienteIndicacao;
    
    /**
     * @ORM\OneToMany(targetEntity="Cliente", mappedBy="clienteIndicacao")
     */
    protected $clienteIndicou;
    
    /**
     * @ORM\OneToMany(targetEntity="ClienteBioimpedancia", mappedBy="bioimpedancia")
     */
    protected $clientesBioimpedancia;
    
    /**
     * @ORM\OneToMany(targetEntity="ClienteFoto", mappedBy="clienteFoto")
     */
    protected $clientesFoto;
    
    /**
     * @ORM\OneToMany(targetEntity="ClienteMedidas", mappedBy="medidas")
     */
    protected $clientesMedidas;

    /**
     * @ORM\OneToMany(targetEntity="Venda", mappedBy="cliente")
     */
    protected $vendas;
    
    /**
     * @ORM\OneToMany(targetEntity="CartelaDigital", mappedBy="cliente")
     */
    protected $cartelasCliente;
    
    /**
     * @ORM\OneToMany(targetEntity="Estrelas", mappedBy="cliente")
     */
    protected $estrelasCliente;

    /**
     * @ORM\Column(type="string")
     */
    protected $nome;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="text")
     */
    protected $foto;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dataHoraInclusao;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $telefoneCelular;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $dataNascimento;
    
    /**
     * @ORM\Column(type="string", nullable=true, length=1)
     */
    protected $sexo;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $senha;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->vendas = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return Cliente
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * @return Cliente
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
     * Set email
     *
     * @param string $email
     *
     * @return Cliente
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set foto
     *
     * @param string $foto
     *
     * @return Cliente
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }
    
    /**
     * Set telefoneCelular
     *
     * @param string $telefoneCelular
     *
     * @return Cliente
     */
    public function setTelefoneCelular($telefoneCelular)
    {
    	$this->telefoneCelular = $telefoneCelular;
    
    	return $this;
    }
    
    /**
     * Get telefoneCelular
     *
     * @return string
     */
    public function getTelefoneCelular()
    {
    	return $this->telefoneCelular;
    }
    
    /**
     * Set dataNascimento
     *
     * @param string $dataNascimento
     *
     * @return Cliente
     */
    public function setDataNascimento($dataNascimento)
    {
    	$this->dataNascimento = $dataNascimento;
    
    	return $this;
    }
    
    /**
     * Get dataNascimento
     *
     * @return string
     */
    public function getDataNascimento()
    {
    	return $this->dataNascimento;
    }
    
    /**
     * Set sexo
     *
     * @param string $sexo
     *
     * @return Cliente
     */
    public function setSexo($sexo)
    {
    	$this->sexo = $sexo;
    
    	return $this;
    }
    
    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo()
    {
    	return $this->sexo;
    }
    
    /**
     * Set senha
     *
     * @param string $senha
     *
     * @return Cliente
     */
    public function setSenha($senha)
    {
    	$this->senha = $senha;
    
    	return $this;
    }
    
    /**
     * Get senha
     *
     * @return string
     */
    public function getSenha()
    {
    	return $this->senha;
    }

    /**
     * Set anfitriao
     *
     * @param Anfitriao $anfitriao
     *
     * @return Cliente
     */
    public function setAnfitriao(Anfitriao $anfitriao)
    {
        $this->anfitriao = $anfitriao;

        return $this;
    }

    /**
     * Get anfitriao
     *
     * @return Anfitriao
     */
    public function getAnfitriao()
    {
        return $this->anfitriao;
    }

    /**
     * Add venda
     *
     * @param Venda $venda
     *
     * @return Cliente
     */
    public function addVenda(Venda $venda)
    {
        $this->vendas[] = $venda;

        return $this;
    }

    /**
     * Remove venda
     *
     * @param Venda $venda
     */
    public function removeVenda(Venda $venda)
    {
        $this->vendas->removeElement($venda);
    }

    /**
     * Get vendas
     *
     * @return Collection
     */
    public function getVendas()
    {
        return $this->vendas;
    }
    
    /**
     * Add cartelasCliente
     *
     * @param CartelaDigital $cartelasCliente
     *
     * @return Cliente
     */
    public function addCartelasCliente(CartelaDigital $cartelasCliente)
    {
    	$this->cartelasCliente[] = $cartelasCliente;
    
    	return $this;
    }
    
    /**
     * Remove cartelasCliente
     *
     * @param CartelaDigital $cartelasCliente
     */
    public function removeCartelasCliente(CartelaDigital $cartelasCliente)
    {
    	$this->cartelasCliente->removeElement($cartelasCliente);
    }
    
    /**
     * Get cartelasCliente
     *
     * @return Collection
     */
    public function getCartelasCliente()
    {
    	return $this->cartelasCliente;
    }
    
    /**
     * Set dataHoraInclusao
     *
     * @param \DateTime $dataHoraInclusao
     *
     * @return Cliente
     */
    public function setDataHoraInclusao($dataHoraInclusao)
    {
    	$this->dataHoraInclusao = $dataHoraInclusao;
    
    	return $this;
    }
    
    /**
     * Get dataHoraInclusao
     *
     * @return \DateTime
     */
    public function getDataHoraInclusao()
    {
    	return $this->dataHoraInclusao;
    }
    
    public function __toString() {
    	return $this->nome;
    }
}
