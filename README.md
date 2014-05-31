Fulgurio User bundle
========================

This bundle is a working user registration using FOSUserBundle. It use also
Twitter Bootstrap.
This bundle will be a bootstrap for your futur development, just fork it !

## Installation

Please take a look at FOSUserBundle documentation. Here you will find all
informations to work with this bundle

1. Download FulgurioUserBundle using composer
2. Enable the Bundle
3. Configure your application's security.yml
4. Configure the bundle
5. Import routing
6. Update your database schema

### Step 1: Download FulgurioUserBundle using composer

It will also load FOSUserBundle, just follow the next steps
Coming soon

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FOS\UserBundle\FOSUserBundle(),
        new Fulgurio\UserBundle\FulgurioUserBundle(),
        new Fulgurio\TwitterBootstrapBundle\FulgurioTwitterBootstrapBundle()
    );
}
```

### Step 3: Configure your application's security.yml

Please read FOSUserBundle security for more information. Here is the well
configuration :

Below is a minimal example of the configuration necessary to use the
FOSUserBundle in your application:

``` yaml
# app/config/security.yml
security:
    encoders:
        FOS\UserBundle\Model\UserInterface: sha512

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: ROLE_ADMIN

    providers:
        fos_userbundle:
            id: fos_user.user_provider.username_email

    firewalls:
         dev:
             pattern:  ^/(_(profiler|wdt)|css|images|js)/
             security: false
        main:
            pattern: ^/
            form_login:
                provider: fos_userbundle
                csrf_provider: form.csrf_provider
                always_use_default_target_path: true
                default_target_path: /
                username_parameter: login[email]
                password_parameter: login[password]
                csrf_parameter: login[csrf_token]
                remember_me: true
            remember_me:
                key:      "%secret%"
                remember_me_parameter: login[remember_me]
            logout:
                path: /logout
                target: /
                invalidate_session: false
            anonymous:    true

    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
```

### Step 4: Configure the bundle

``` yaml
# app/config/config.yml
fos_user:
    db_driver: orm # other valid values are 'mongodb', 'couchdb' and 'propel'
    firewall_name: main
    user_class: Fulgurio\UserBundle\Entity\User
    registration:
        form:
            name: registration
    resetting:
        form:
            name: resetPassword
    profile:
        form:
            name: profile
```

### Step 5: Import routing files

Here is FOSUserBundle routing :

``` yaml
# app/config/routing.yml
fos_user_security:
    resource: "@FOSUserBundle/Resources/config/routing/security.xml"

fos_user_profile:
    resource: "@FOSUserBundle/Resources/config/routing/profile.xml"
    prefix: /profile

fos_user_register:
    resource: "@FOSUserBundle/Resources/config/routing/registration.xml"
    prefix: /register

fos_user_resetting:
    resource: "@FOSUserBundle/Resources/config/routing/resetting.xml"
    prefix: /resetting

fos_user_change_password:
    resource: "@FOSUserBundle/Resources/config/routing/change_password.xml"
    prefix: /profile
```

To see main page as example, add this one :

``` yaml
# app/config/routing.yml
fulgurio_user:
    resource: "@FulgurioUserBundle/Controller/"
    type:     annotation
    prefix:   /
```

### Step 6: Update your database schema

Please see FOSUserBundle documentation.

Here is the sample for ORM run the following command.

``` bash
$ php app/console doctrine:schema:update --force
```
