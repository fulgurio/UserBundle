FulgurioUserBundle Configuration Reference
==============================================

All available configuration options are listed below with their default values.

``` yaml
# app/config/config.yml
fulgurio_user:
    from_email:
        address:        webmaster@example.com
        sender_name:    webmaster
    change_password:
        email:
            enabled: false
            from_email:
                address: webmaster@example.com
                sender_name: webmaster
    unsubscribe:
        email:
            enabled: false
            from_email:
                address: webmaster@example.com
                sender_name: webmaster
```


## About change_password
FulgurioUserBundle can sent an email when a user has changed his password. It's
a security email, to alert user (by email) that's someone has changed the
password of his account. If it's not him, it's a hacker !