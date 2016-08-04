<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"id_produto", "id_tag"})})
 */
class TagProduto
{
    use Dta;

    /**
     * @ORM\Column(name="id_tag_produto", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="tagsProduto")
     * @ORM\JoinColumn(name="id_tag", nullable=false, referencedColumnName="id_tag", onDelete="CASCADE")
     */
    protected $tag;

    /**
     * @ORM\ManyToOne(targetEntity="Produto", inversedBy="tagsProduto")
     * @ORM\JoinColumn(name="id_produto", nullable=false, referencedColumnName="id_produto", onDelete="CASCADE")
     */
    protected $produto;

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
     * @return TagProduto
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
     * Set tag
     *
     * @param Tag $tag
     *
     * @return TagProduto
     */
    public function setTag(Tag $tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return Tag
     */
    public function getTag()
    {
        return $this->tag;
    }
}
