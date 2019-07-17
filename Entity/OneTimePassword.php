<?php

namespace Optime\SimpleSsoServerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Optime\SimpleSso\OneTimePassword\OneTimePassword as BaseClass;

/**
 * @ORM\Table(name="simple_sso_otp")
 * @ORM\Entity(repositoryClass="Optime\SimpleSsoServerBundle\Entity\OneTimePasswordRepository")
 */
class OneTimePassword extends BaseClass
{
}

