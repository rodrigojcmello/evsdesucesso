<?php

namespace AppBundle\Entity;

trait Dta
{
    /**
     * @ORM\Column(type="datetime")
     */
    protected $dta;

    /**
     * Get dta
     *
     * @return \DateTime
     */
    public function getDta()
    {
        return $this->dta;
    }

    /**
     * Set dta
     *
     * @param \DateTime $dta
     *
     * @return $this
     */
    public function setDta(\DateTime $dta)
    {
        $this->dta = $dta;

        return $this;
    }
}