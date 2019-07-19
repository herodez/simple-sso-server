<?php

namespace Optime\SimpleSsoServerBundle\Security;

use Optime\SimpleSso\Security\AuthDataResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class BasicAuthDataResolver implements AuthDataResolverInterface
{
    /**
     * @var TokenUtils
     */
    private $tokenStorage;
    
    /**
     * LoyaltyAuthDataResolver constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    
    /**
     * @return string
     */
    public function getUsername()
    {
        if(!$token = $this->tokenStorage->getToken()){
            throw new InsufficientAuthenticationException("No se encontró un token de seguridad disponible");
        }

        return $token->getUsername();
    }
    
    /**
     * @return array
     */
    public function getData()
    {
        if(!$token = $this->tokenStorage->getToken()){
            throw new InsufficientAuthenticationException("No se encontró un token de seguridad disponible");
        }

        return [
            'user' => $this->userToArray($token->getUser()),
            'security_roles' => $this->rolesToArray($token),
        ];
    }
    
    
    /**
     * @param UserInterface $user
     * @return array
     */
    private function userToArray(UserInterface $user)
    {
        return [
            'username' => $user->getUsername(),
        ];
    }

    /**
     * @param TokenInterface $token
     * @return array
     */
    protected function rolesToArray(TokenInterface $token)
    {
        $roles = [];

        /** @var RoleInterface $role */
        foreach ($token->getRoles() as $role) {
            $roles[] = $role->getRole();
        }

        return $roles;
    }
}