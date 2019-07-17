<?php
/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Optime\SimpleSsoServerBundle\Security\EventListener;

use Optime\Component\SingleSignOn\SimpleSso\OneTimePassword\Service\OneTimePasswordCleaner;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class LogoutListener implements LogoutHandlerInterface
{
    /**
     * @var OneTimePasswordCleaner
     */
    private $otpCleaner;

    /**
     * LogoutListener constructor.
     * @param OneTimePasswordCleaner $otpCleaner
     */
    public function __construct(OneTimePasswordCleaner $otpCleaner)
    {
        $this->otpCleaner = $otpCleaner;
    }

    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $this->otpCleaner->clearByUsername($token->getUsername());
    }
}