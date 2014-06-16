custom_logo
===========

Roundcube Webmail Plugin Customize Logo in Session and Print (can depend on domain)

Plugin to load a different logo within a Round Cube Session and for print out.
The logo can depend on the domain name (not a must) if you have more than one user group.
An other default logo can be displayed in case there is no custom_logo available.
This plugin is intended to be used with default and larry Roundcube skin.

Download and install via http://plugins.roundcube.net

Set the following options directly in Roundcube's main config file or via 
[host-specific](http://trac.roundcube.net/wiki/Howto_Config/Multidomains) configurations:

```php
// common logo in case the custom logo does not exist
// $rcmail_config['common_logo_url'] = '/skins/classic/images/roundcube_logo.png';
$rcmail_config['common_logo_url'] = 'plugins/custom_logo/media/default_mail.png';

/* %d will be replaced with the default user identity email domain part
      i.e. email@domain.com will return in %d "domain.com" */
$rcmail_config['custom_logo_url'] = 'plugins/custom_logo/media/%d_mail.png';
```

Homepage:
http://www.std-soft.com/index.php/hm-service/81-c-std-service-code/4-rc-plugin-custom-logo-eigenes-logo-in-der-roundcube-session-setzen

