Enable avatar
========================

It's possible to use an avatar for each user. Just follows these steps:

### Enable the bundle

FulgurioUserBundle use the VichUploaderBundle, it's available in composer, so just register the bundle into the kernel :

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Vich\UploaderBundle\VichUploaderBundle()
```

### Config the bundle

Edit the config yml and add the following lines

``` yaml
# app/config/config.yml
fulgurio_user:
[...]
    avatar:
        enabled: true
vich_uploader:
    db_driver: orm # or mongodb or propel
    mappings:
        avatar_image:
            uri_prefix: /uploads
            upload_destination: %kernel.root_dir%/../web/uploads
            namer:              fulgurio_user.namer_slugify
            directory_namer:    fulgurio_user.directory_namer
```

You can enable or disable avatar with fulgurio_user.avatar.enabled
Files will be dispatched in a directory for each user.

