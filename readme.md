# Simple SSO-server bundle 

[![Latest Stable Version](http://img.shields.io/badge/Latest%20Stable-1.0.0-green.svg)](http://optgit.optimeconsulting.net:8090/component/optime_sso_server)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg?style=flat-square)](https://php.net/)

This repository contains a SSO-server bundle that provide user authentication service for third party application.

## Installation

The SSO-server bundle can be installed with [Composer](https://getcomposer.org/) following the next steps:

### Add Optime private repository 

First at all add the Optime private composer repository in your project
```json
{
  "repositories": [
    {
	  "type": "composer",
	  "url": "http://satis.developerplace.net/"
    }
  ]
}
```
Because the Optime private repository not allow HTTP secure connections for now, you must be allow 
insecure HTTP connections in your composer configuration in order to install packages from this repository, 
add the following line in the configuration section of your **composer.json** file to allow insecure HTTP
connections.

```json
{
    "config": {
      "secure-http": false
    }
}
```
> **Note:** check the official composer documentation for more information about private repositories
configuration

then install the package via composer
```sh
composer require optime/simple-sso-server
```
## Usage
After install register the bundle in the application bundles section.
```php
return [
    ....
    Optime\SimpleSsoClientBundle\SimpleSsoServerBundle::class => ['all' => true]
];
```
and register the bundle routes configuration on your **routing.yaml** file.
```yaml
simple_sso_server:
    resource: "@SimpleSsoServerBundle/Controller/"
    type:     annotation
    prefix:   /
```

### Configuration

To configure the bundle create a **simple_sso_server.yaml** file with the following parameters:
```yaml
simple_sso_server:
  applications:
    App:
      username: "app_secret_user"
      password: "app_secret_password"
  auth_data_resolver_service: "app_user_resolver_service"
```
#### parameters

- **Applications**: Configure an array of third parties application that are allowed to authenticate via SSO-server.

- **username**: Configure the secret username that an third party application must be use to authenticate via SSO-server.

- **password**: Configure the secret password that an third party application must be use to authenticate via SSO-server.

- **auth_data_resolver_service**: Configure a service that must be implement **AuthDataResolverInterface** interface this service is the
responsible to return a data array representing the user data that will be pass to the third party application when it
make a SSO user authentication request to the SSO-server. 

## Security Vulnerabilities

If you have found a security issue, please contact the maintainers directly at [mgonzalez@optimeconsulting.com](mailto:mgonzalez@optimeconsulting.com).