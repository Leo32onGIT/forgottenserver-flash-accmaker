## What is this?
This is a containerized client and account maker for [this branch](https://github.com/Leo32onGIT/forgottenserver/tree/10.79-flash-dev) of theforgottenserver.\
It cannot really be used in production anymore because [flash is deprecated](https://en.wikipedia.org/wiki/Adobe_Flash#End_of_life).

It was created by cutting this up (quite a while ago):\
https://github.com/gesior/Gesior2012/

Having a client **as a browser tab** is highly valuable to me, so I use it as my development environment.


## Components
- PHP web files
- Socket file policy service ([original source](https://github.com/Leo32onGIT/forgottenserver-flash-accmaker))

### Installing on Windows (IIS)
These are hand-holding steps for those who are unfamilar with iis.

##### Download PHP
1. Download the [PHP binaries](https://windows.php.net/download)
2. Extract them and place the folder somewhere appropriate on your PC i.e. C:\Web\PHP
3. Open **php.ini** and point *open_basedir* to your *'website files directory'* and *'the directory with your servers [config.lua](https://github.com/Leo32onGIT/forgottenserver/blob/10.79-flash-dev/config.lua.dist) file'*

> *i.e.* open_basedir = "C:\GitHub\forgottenserver;C:\GitHub\forgottenserver-flash-accmaker;C:\Windows\Temp"

##### Create the site in IIS
1. Open IIS and create a new website
2. Point to your local copy of this repo:

> *i.e.* C:\GitHub\forgottenserver-flash-accmaker

3. Open the [web.config](https://github.com/Leo32onGIT/forgottenserver-flash-accmaker/blob/master/web.config#L21) file in this repo, and point **scriptProccessor** to the php-cgi binary.

> *i.e.* scriptProcessor="C:\Web\PHP\php-cgi.exe"

#### Setup Flash Socket Service
1. Run [install.bat](https://github.com/Leo32onGIT/forgottenserver-flash-accmaker/blob/master/flashsocketservice/install.bat) to register the **flash socket server** executable as a service.
2. Find the newly registered service in **services.msc** and **Start** it.

#### Install the account maker
1. Add your server url to [config.php](https://github.com/Leo32onGIT/forgottenserver-flash-accmaker/blob/master/config/config.php)
2. Add your forgottenserver directory to config.php too

> $config['site']['url'] = 'http://127.0.0.1';

> $config['site']['serverPath'] = "C:\GitHub\forgottenserver\";

3. Browse to http://127.0.0.1 and click **Install Bootstrap ACC**)

### Installing on Linux
I assume you already have the capability to set up a php website if you're using linux.\
*This repository **is** just php web files.*

However - you will need to find an alternative way of serving the [socket policy file](https://www.adobe.com/devnet/flashplayer/articles/socket_policy_files.html).
