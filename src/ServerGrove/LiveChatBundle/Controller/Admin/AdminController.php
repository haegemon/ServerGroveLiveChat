<?php

namespace ServerGrove\LiveChatBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\SecurityContext;
use ServerGrove\LiveChatBundle\Form\OperatorLoginType;

/**
 * Class AdminController
 *
 * @author Ismael Ambrosi <ismael@servergrove.com>
 */
class AdminController extends Controller
{
    /**
     * @Route("/login", name="_security_login")
     * @Template
     *
     * @return array
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {

            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);

        } else {

            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'error' => $error,
            'form'  => $this->createLoginForm()->createView()
        );
    }

    /**
     * @Route("/logout", name="sglc_admin_logout")
     * @return array
     */
    public final function logoutAction()
    {
        return array();
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function createLoginForm()
    {
        return $this->get('form.factory')->create(new OperatorLoginType());
    }

}
