<?php

namespace Optime\SimpleSsoServerBundle\Controller;

use Optime\SimpleSso\UseCase\CreateLoginOtpUseCase;
use Optime\SimpleSso\UseCase\GetCredentialsUseCase;
use Optime\SimpleSso\UseCase\VerifyActiveSessionUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 *
 * @Route("/simple-sso")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login",
     *  condition="request.query.has('username') and request.query.has('_target')"
     * )
     * @Method("GET")
     * @Security("is_authenticated()")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginAction(Request $request, CreateLoginOtpUseCase $useCase)
    {
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
    public function getCredentialsAction(
        Request $request,
        GetCredentialsUseCase $useCase,
        ContainerInterface $container
    ) {
        try {
            $credentials = $useCase->handle(
                $request->get('otp'),
                $request->get('created'),
                $request->get('password')
            );
        } catch (\Exception $e) {
            if ($container->has('logger')) {
                $container->get('logger')->error($e->getMessage(), [
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
    public function checkActiveSessionAction(Request $request, VerifyActiveSessionUseCase $useCase)
    {
        $result = $useCase->handle(
            $request->query->get('otp'),
            $request->query->get('created'),
            $request->query->get('password')
        );
        
        return new Response($result ? 1 : 0);
    }
}
