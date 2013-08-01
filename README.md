# Skpyebot

## Running the bot

Steps required to run the bot:

1. Install the PECL DBus extension (see below)
1. Install the Skype client and sign in
1. From a terminal, run the dbus monitor script `php dbus-monitor.php`

On the first run, the Skype client application should show a popup that requires you to authorise the Dbus script.

## Installing DBus

The PHP dbus libraries can be installed from PECL:

    sudo pecl install DBus-0.1.1

Further libraries my be required for your operation system.

- [Install PHP DBus on Ubuntu](http://web-dev-wiki.blogspot.co.uk/2012/11/how-to-install-dbus-for-php-on-ubuntu.html)



