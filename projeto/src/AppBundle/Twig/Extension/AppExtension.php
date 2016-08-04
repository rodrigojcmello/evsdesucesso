<?php

namespace AppBundle\Twig\Extension;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Security\Core\Security as Security;

class AppExtension extends \Twig_Extension
{
    public function getFunctions() {
        return array(
            'hua' => new \Twig_Function_Method( $this, 'hua' )
        );
    }

    public function hua()
    {
        return 'whatever';
    }

    public function getName()
    {
        return 'app_bundle';
    }
}
