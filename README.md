custom_logo
===========

Roundcube Webmail Plugin Customize Logo in Session (can depend on Domain)

Plugin to load a different logo within a Round Cube Session.
The Logo can depend on the domain name (not a must) if you have diffent customers.

Download and install via http://plugins.roundcube.net

Set the following options directly in Roundcube's main config file or via 
[host-specific](http://trac.roundcube.net/wiki/Howto_Config/Multidomains) configurations:

$config['custom_logo_url'] = 'plugins/custom_logo/images/%d_logo.png'; // %d ~ default user email domain part


