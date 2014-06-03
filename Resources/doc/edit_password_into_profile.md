Edit password into profile
========================

FOSUser dispatch edition profile and changing password
in 2 separate form. If you don't have many fields in 
profile form, may be it's better to merge it with
password edition.

### Step 1 : First, edit and remove the fos_user_change_password route

``` yaml
# app/config/routing.yml
fos_user_change_password:
    resource: "@FOSUserBundle/Resources/config/routing/change_password.xml"
    prefix: /profile
```

### Step 2 : Replace form with the new one

``` yaml
# app/config/config.yml
fos_user:
[...]
    profile:
        form:
[...]
            type: fulgurio_user_profile_with_password_edition
```

That's it ! You need to enter current password to change it. Very usefull, 
you'll see !
