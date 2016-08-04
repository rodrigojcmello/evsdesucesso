<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ProdutoImagem
{
	use Dta;
	
	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	protected $id;
	
	/**
	 * @ORM\OneToOne(targetEntity="Produto", inversedBy="imagemProduto")
	 * @ORM\JoinColumn(name="id_produto", nullable=true, referencedColumnName="id_produto")
	 */
	protected $produto;
	
	/**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $imagem;
	
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
	 * Set produto
	 *
	 * @param Produto $produto
	 *
	 * @return ProdutoImagem
	 */
	public function setProduto(Produto $produto)
	{
		$this->produto = $produto;
	
		return $this;
	}
	
	/**
	 * Get produto
	 *
	 * @return Produto
	 */
	public function getProduto()
	{
		return $this->produto;
	}
	
	/**
	 * Set imagem
	 *
	 * @param text $imagem
	 *
	 * @return ProdutoImagem
	 */
	public function setImagem($imagem)
	{
		$this->imagem = $imagem;
	
		return $this;
	}
	
	/**
	 * Get imagem
	 *
	 * @return text
	 */
	public function getImagem()
	{
		return $this->imagem;
	}
	
	public function __toString() {
		return $this->imagem;
	}
}
