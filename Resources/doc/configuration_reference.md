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
            template: FulgurioUserBundle:ChangePassword:email.html.twig
            from_email:
                address: webmaster@example.com
                sender_name: webmaster
    unsubscribe:
        email:
            enabled: false
            template: FulgurioUserBundle:Registration:email_unsubscribe.html.twig
            from_email:
                address: webmaster@example.com
                sender_name: webmaster
    ban:
        email:
            enabled: true
            template: FulgurioUserBundle:Admin:email_ban.html.twig
            from_email:
                address: webmaster@example.com
                sender_name: webmaster
    unban:
        email:
            template: FulgurioUserBundle:Admin:email_unban.html.twig

```


## About change_password
FulgurioUserBundle can sent an email when a user has changed his password. It's
a security email, to alert user (by email) that's someone has changed the
password of his account. If it's not him, it's a hacker !

## About unsubscribe
FulgurioUserBundle can sent an email when a user unsubscribe for the website.
It's a validation of the subscription, to be sure that it has worked !

## About ban / unban
The administration of FulgurioUserBundle allows user ban, you can sent an email
to alert user that he s banned.
