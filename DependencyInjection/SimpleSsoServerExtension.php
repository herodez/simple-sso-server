<?php

namespace Optime\SimpleSsoServerBundle\DependencyInjection;

use Optime\SimpleSso\Security\AuthDataResolverInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SimpleSsoServerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('simple_sso_server.applications', $config['applications']);
        $container->setAlias('simple_sso_server.application_repository', $config['application_repository_service']);
        $container->setAlias('simple_sso_server.auth_data_resolver', $config['auth_data_resolver_service']);
    }
}
