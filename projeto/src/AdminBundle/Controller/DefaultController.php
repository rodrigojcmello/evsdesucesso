<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }

    /**
     * @Route("/admin/sair")
     */
    public function sairAction(Request $request)
    {

        $req = $request->getSession();

        $req->start();
        $req->invalidate();
        $req->clear();

        dump($req);
        dump( $this->getUser() ) ; // ->invaldate();

        $anonToken = new AnonymousToken('theTokensKey', 'anon.', array());
        $this->get('security.token_storage')->setToken($anonToken);

        dump($_SERVER);
        return new Response("ok");
    }

    /**
     * @Route("/admin")
     * @template()
     */
    public function adminAction()
    {
        return $this->render('AdminBundle:Default:home.html.twig');
    }
}