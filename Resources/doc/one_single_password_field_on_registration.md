One single password field on registration
========================

For a fastest registration form, usually, you don't ask 
2 times the password (the password and the verification).
Here is the configuration to use only one field.

``` yaml
# app/config/config.yml
fos_user:
[...]
    registration:
        form:
[...]
            type: fulgurio_user_registration
```

done !
