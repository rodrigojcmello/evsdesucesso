<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class PontoDeVenda
{
    use Dta;

    /**
     * @ORM\Column(name="id_ponto_de_venda", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Anfitriao", mappedBy="pontoDeVenda")
     */
    protected $anfitrioes;
    
    /**
     * @ORM\OneToMany(targetEntity="CartelaDigital", mappedBy="pontosDeVenda")
     */
    protected $pontosCliente;
    
    /**
     * @ORM\OneToMany(targetEntity="Estrelas", mappedBy="pontosDeVenda")
     */
    protected $pontosEstrela;
    
    /**
     * @ORM\OneToMany(targetEntity="ItemPdvTabelaPrecos", mappedBy="pontoDeVenda")
     */
    protected $itensPdv;

    /**
     * @ORM\OneToMany(targetEntity="GradeConsumoPdv", mappedBy="idPontoDeVenda")
     */
    protected $itensGradePdv;

    /**
     * @ORM\ManyToOne(targetEntity="ClassePontoDeVenda", inversedBy="pontosDeVenda")
     * @ORM\JoinColumn(name="id_classe_ponto_de_venda", nullable=false, referencedColumnName="id_classe_ponto_de_venda")
     */
    protected $classePontoDeVenda;

    /**
     * @ORM\ManyToOne(targetEntity="Uf", inversedBy="pontosDeVenda")
     * @ORM\JoinColumn(name="id_uf", nullable=false, referencedColumnName="id_uf")
     */
    protected $uf;
    
    /**
     * @ORM\ManyToOne(targetEntity="Anfitriao", inversedBy="pontoDeVendaAnfitriao")
     * @ORM\JoinColumn(name="id_anfitriao", nullable=true, referencedColumnName="id_anfitriao")
     */
    protected $anfitriao;
    
    /**
     * @ORM\ManyToOne(targetEntity="Anfitriao", inversedBy="pontoDeVendaAnfitriaoMaster")
     * @ORM\JoinColumn(name="id_anfitriao_master", nullable=true, referencedColumnName="id_anfitriao")
     */
    protected $anfitriaoMaster;

    /**
     * @ORM\Column(type="string")
     */
    protected $nome;

    /**
     * @ORM\Column(type="string")
     */
    protected $endereco;

    /**
     * @ORM\Column(type="string")
     */
    protected $telefone;

    /**
     * @ORM\Column(type="string")
     */
    protected $site;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $dataExpiracao;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->anfitrioes = new ArrayCollection();
        $this->pontosEstrela = new ArrayCollection();
        $this->itensPdv = new ArrayCollection();
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
     * @return PontoDeVenda
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
     * @return PontoDeVenda
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
     * Set telefone
     *
     * @param string $telefone
     *
     * @return PontoDeVenda
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

        return $this;
    }

    /**
     * Get telefone
     *
     * @return string
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Set site
     *
     * @param string $site
     *
     * @return PontoDeVenda
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }
    
    /**
     * Set dataExpiracao
     *
     * @param date $dataExpiracao
     *
     * @return PontoDeVenda
     */
    public function setDataExpiracao($dataExpiracao)
    {
    	$this->dataExpiracao = $dataExpiracao;
    
    	return $this;
    }
    
    /**
     * Get dataExpiracao
     *
     * @return date
     */
    public function getDataExpiracao()
    {
    	return $this->dataExpiracao;
    }

    /**
     * Add anfitrioes
     *
     * @param Anfitriao $anfitrioes
     *
     * @return PontoDeVenda
     */
    public function addAnfitrioes(Anfitriao $anfitrioes)
    {
        $this->anfitrioes[] = $anfitrioes;

        return $this;
    }

    /**
     * Remove anfitrioes
     *
     * @param Anfitriao $anfitrioes
     */
    public function removeAnfitrioes(Anfitriao $anfitrioes)
    {
        $this->anfitrioes->removeElement($anfitrioes);
    }

    /**
     * Get anfitrioes
     *
     * @return Collection
     */
    public function getAnfitrioes()
    {
        return $this->anfitrioes;
    }
    
    /**
     * Add pontosEstrela
     *
     * @param Estrelas $pontosEstrela
     *
     * @return PontoDeVenda
     */
    public function addPontosEstrela(Estrelas $pontosEstrela)
    {
    	$this->pontosEstrela[] = $pontosEstrela;
    
    	return $this;
    }
    
    /**
     * Remove pontosEstrela
     *
     * @param Estrelas $pontosEstrela
     */
    public function removePontosEstrela(Estrelas $pontosEstrela)
    {
    	$this->pontosEstrela->removeElement($pontosEstrela);
    }
    
    /**
     * Get pontosEstrela
     *
     * @return Collection
     */
    public function getPontosEstrela()
    {
    	return $this->pontosEstrela;
    }
    
    /**
     * Add itensPdv
     *
     * @param ItemPdvTabelaPrecos $itensPdv
     *
     * @return PontoDeVenda
     */
    public function addItensPdv(ItemPdvTabelaPrecos $itensPdv)
    {
    	$this->itensPdv[] = $itensPdv;
    
    	return $this;
    }
    
    /**
     * Remove itensPdv
     *
     * @param ItemPdvTabelaPrecos $itensPdv
     */
    public function removeItensPdv(ItemPdvTabelaPrecos $itensPdv)
    {
    	$this->itensPdv->removeElement($itensPdv);
    }
    
    /**
     * Get itensPdv
     *
     * @return Collection
     */
    public function getItensPdv()
    {
    	return $this->itensPdv;
    }

    /**
     * Add itensGradePdv
     *
     * @param GradeConsumoPdv $itensGradePdv
     *
     * @return PontoDeVenda
     */
    public function addItensGradePdv(GradeConsumoPdv $itensGradePdv)
    {
        $this->itensGradePdv[] = $itensGradePdv;

        return $this;
    }

    /**
     * Remove itensGradePdv
     *
     * @param GradeConsumoPdv $itensGradePdv
     */
    public function removeItensGradePdv(GradeConsumoPdv $itensGradePdv)
    {
        $this->itensGradePdv->removeElement($itensGradePdv);
    }

    /**
     * Get itensGradePdv
     *
     * @return Collection
     */
    public function getItensGradePdv()
    {
        return $this->itensGradePdv;
    }
    
    /**
     * Add pontosCliente
     *
     * @param CartelaDigital $pontosCliente
     *
     * @return PontoDeVenda
     */
    public function addPontosCliente(CartelaDigital $pontosCliente)
    {
    	$this->pontosCliente[] = $pontosCliente;
    
    	return $this;
    }
    
    /**
     * Remove pontosCliente
     *
     * @param CartelaDigital $pontosCliente
     */
    public function removePontosCliente(CartelaDigital $pontosCliente)
    {
    	$this->pontosCliente->removeElement($pontosCliente);
    }
    
    /**
     * Get pontosCliente
     *
     * @return Collection
     */
    public function getPontosCliente()
    {
    	return $this->pontosCliente;
    }

    /**
     * Set classePontoDeVenda
     *
     * @param ClassePontoDeVenda $classePontoDeVenda
     *
     * @return PontoDeVenda
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
     * Set uf
     *
     * @param Uf $uf
     *
     * @return PontoDeVenda
     */
    public function setUf(Uf $uf)
    {
        $this->uf = $uf;

        return $this;
    }

    /**
     * Get uf
     *
     * @return Uf
     */
    public function getUf()
    {
        return $this->uf;
    }
    
    /**
     * Set anfitriao
     *
     * @param Anfitriao $anfitriao
     *
     * @return Anfitriao
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
     * Set anfitriaoMaster
     *
     * @param Anfitriao $anfitriaoMaster
     *
     * @return Anfitriao
     */
    public function setAnfitriaoMaster(Anfitriao $anfitriaoMaster)
    {
    	$this->anfitriaoMaster = $anfitriaoMaster;
    
    	return $this;
    }
    
    /**
     * Get anfitriaoMaster
     *
     * @return Anfitriao
     */
    public function getAnfitriaoMaster()
    {
    	return $this->anfitriaoMaster;
    }
    
    public function __toString() {
    	return $this->nome;
    }
}
