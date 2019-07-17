<?php

namespace Optime\SimpleSsoServerBundle\Security;

use AppBundle\Entity\Company;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Model\CompanyUserData;
use AppBundle\Security\TokenUtils;
use Optime\Component\SingleSignOn\SimpleSso\Security\AuthDataResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class LoyaltyAuthDataResolver implements AuthDataResolverInterface
{
    /**
     * @var TokenUtils
     */
    private $tokenUtils;
    /**
     * @var CompanyUserData
     */
    private $companyUserData;

    /**
     * LoyaltyAuthDataResolver constructor.
     * @param TokenUtils $tokenUtils
     * @param CompanyUserData $companyUserData
     */
    public function __construct(TokenUtils $tokenUtils, CompanyUserData $companyUserData)
    {
        $this->tokenUtils = $tokenUtils;
        $this->companyUserData = $companyUserData;
    }

    public function getUsername()
    {
        if(!$token = $this->tokenUtils->getToken()){
            throw new InsufficientAuthenticationException("No se encontró un token de seguridad disponible");
        }

        return $token->getUsername();
    }

    public function getData()
    {
        if(!$token = $this->tokenUtils->getToken()){
            throw new InsufficientAuthenticationException("No se encontró un token de seguridad disponible");
        }

        $user = $token->getUser();
        $company = $this->tokenUtils->getCompany($token);
        $profile = $this->companyUserData->getHighPriorityProfile($company, $user);

        return [
            'company' => $this->companyToArray($company),
            'user' => $this->userToArray($user),
            'profile' => $this->profileToArray($profile),
            'security_roles' => $this->rolesToArray($token),
        ];
    }


    /**
     * @param User $user
     * @return array
     */
    private function userToArray(User $user)
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'first_name' => $user->getPerson()->getFirstname(),
            'last_name' => $user->getPerson()->getLastname(),
        ];
    }

    /**
     * @param Company $company
     * @return array
     */
    private function companyToArray(Company $company)
    {
        return [
            'id' => $company->getId(),
            'name' => $company->getName(),
        ];
    }

    /**
     * @param Profile $profile
     * @return array
     */
    protected function profileToArray(Profile $profile)
    {
        return [
            'id' => $profile->getId(),
            'name' => (string)$profile,
        ];
    }

    /**
     * @param TokenInterface $token
     * @return \Symfony\Component\Security\Core\Role\RoleInterface[]
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