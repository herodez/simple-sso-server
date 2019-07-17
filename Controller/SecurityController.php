<?php

namespace Optime\SimpleSsoServerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 *
 * @Route("/simple-sso")
 */
class SecurityController extends AbstractController
{
    public function __construct()
    {
    }
    
    /**
	 * @Route("/login",
	 *  condition="request.query.has('username') and request.query.has('_target')"
	 * )
	 * @Method("GET")
	 * @Security("is_authenticated()")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
    public function loginAction(Request $request)
    {
        $useCase = $this->get('simple_sso_server.use_case.create_login_otp');

        return $this->redirect($useCase->handle(
            $request->query->get('username'),
            $request->query->get('_target')
        ));
    }
	
	/**
	 * @Route("/login")
	 * @Method("POST")
	 * @param Request $request
	 * @return Response
	 * @throws \Exception
	 */
    public function getCredentialsAction(Request $request)
    {
        try {
            $credentials = $this->get('simple_sso_server.use_case.get_credentials')->handle(
                $request->get('otp'),
                $request->get('created'),
                $request->get('password')
            );
        } catch (\Exception $e) {
            if ($this->container->has('logger')) {
                $this->get('logger')->error($e->getMessage(), [
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            throw $e;
        }

        return new Response(base64_encode($credentials));
    }
	
	/**
	 * @Route("/verify")
	 * @Method("GET")
	 * @param Request $request
	 * @return Response
	 */
    public function checkActiveSessionAction(Request $request)
    {
        $result = $this->get('simple_sso_server.use_case.verify_active_session')->handle(
            $request->query->get('otp'),
            $request->query->get('created'),
            $request->query->get('password')
        );

        return new Response($result ? 1 : 0);
    }
}
