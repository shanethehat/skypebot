# Skpyebot

## Description

A PHP backend for controlling a Skype user via a DBus connection to a running Skype client.

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

## License and Author

   Author:: Ben Longden (blongden@inviqa.com)

   Copyright 2012, Inviqa

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.