Facebook connector is available on Fulgurio User Bundle. It uses HWIOAuthBundle.

Just follow these steps:
1. Download HWIOAuthBundle using composer
2. Enable the Bundle
3. Configure your application's security.yml
4. Configure the bundle
5. Import routing
6. Update your database schema

### Step 1: Download FulgurioUserBundle using composer

Just call composer update, but it will be already loaded, lucky man !

``` bash
$ phar composer.phar update
```


### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new HWI\Bundle\OAuthBundle\HWIOAuthBundle()    );
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
    firewalls:
        main:
            oauth:
                resource_owners:
                    facebook:           "/login/check-facebook"
                login_path:        /login
                failure_path:      /login
                oauth_user_provider:
                    service: fulgurio_user.social_connector_provider
```

### Step 4: Configure the bundle

``` yaml
# app/config/config.yml
hwi_oauth:
    firewall_name: main
    connect:
        account_connector: fulgurio_user.social_connector_provider
    resource_owners:
        facebook:
            type:                facebook
            client_id:           <YOUR_CLIENT_ID>
            client_secret:       <YOUR_SECRET_KEY>
            scope:               "public_profile,email"
```

Just replace <YOUR_CLIENT_ID> and <YOUR_SECRET_KEY> with your own data.

### Step 5: Import routing files

``` yaml
# app/config/routing.yml
hwi_oauth_redirect:
    resource: "@HWIOAuthBundle/Resources/config/routing/redirect.xml"
    prefix:   /connect

hwi_oauth_security:
    resource: "@HWIOAuthBundle/Resources/config/routing/login.xml"
    prefix: /login

hwi_oauth_connect:
    resource: "@HWIOAuthBundle/Resources/config/routing/connect.xml"
    prefix: /login

hwi_oauth_redirect:
    resource: "@HWIOAuthBundle/Resources/config/routing/redirect.xml"
    prefix:   /login

facebook_login:
    pattern: /login/check-facebook
```

### Step 6: Update your database schema

Here is the sample for ORM run the following command.

``` bash
$ php app/console doctrine:schema:update --force
```
