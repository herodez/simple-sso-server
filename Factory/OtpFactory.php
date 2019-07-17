<?php

namespace Optime\SimpleSsoServerBundle\Factory;

use Optime\SimpleSsoServerBundle\Entity\OneTimePassword;
use Optime\Component\SingleSignOn\SimpleSso\OneTimePassword\OneTimePasswordFactoryInterface;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class OtpFactory implements OneTimePasswordFactoryInterface
{
    public function create($otp, $application, $username, $authData)
    {
        return new OneTimePassword($otp, $application, $username, $authData);
    }
}