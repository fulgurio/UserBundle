## Tests
FulgurioUserBundle use DoctrineFixturesBundle and Liip\FunctionalTestBundle. They are already loaded on FulgurioUserBundle by composer.
To use functional tests, you need to follow this steps :
1. Enable the bundles
2. Configure the bundles
3. Launch tests

### Step 1: Enable the bundles

Enable the test bundles in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
[...]
        if (in_array($this->getEnvironment(), array('test'))) {
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
            $bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
        }
```
### Step 2: Configure the bundles

``` yaml
# app/config/config_test.yml
 framework:
    translator:
        enabled:              false
[...]
 doctrine:
     dbal:
-        dbname:   "%database_name%_test"
+#        dbname:   "%database_name%_test"
+        connections:
+            default:
+                driver:   pdo_sqlite
+                path:     %kernel.cache_dir%/test.db
+
+liip_functional_test:
+    cache_sqlite_db: true
```

### Step 3: Launch tests
``` bash
$  phpunit -c app vendor/Fulgurio/UserBundle/
```