<?php

namespace Optime\SimpleSsoServerBundle\Security\EventListener;

use Optime\SimpleSso\OneTimePassword\Service\OneTimePasswordCleaner;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class SwitchUserListener
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var OneTimePasswordCleaner
     */
    private $cleaner;
	
	/**
	 * SwitchUserListener constructor.
	 * @param TokenStorageInterface $tokenStorage
	 * @param OneTimePasswordCleaner $cleaner
	 */
    public function __construct(TokenStorageInterface $tokenStorage, OneTimePasswordCleaner $cleaner)
    {
        $this->tokenStorage = $tokenStorage;
        $this->cleaner = $cleaner;
    }

    public function onSwitchUser(SwitchUserEvent $event)
    {
        // Utilizamos el token, porque contiene el usuario actual y no el nuevo.
        $username = $this->tokenStorage->getToken()->getUsername();

        $this->cleaner->clearByUsername($username);
    }
}