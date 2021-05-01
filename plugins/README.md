# Installing the Plugins
Perhaps the easiest method of installing these plugins is by utlizing the "Filer" plugin in pfSense. Simply type the entire file name (i.e. "/usr/local/bin/plugin_name") and paste the code into the window. Make sure to set the permissions to be "0755", as they default to "0644" which is NOT executable!

# telegraf_pfifgw.php

*Replaces telegraf_gateways.py scripts (2.7 & 3.7)*

*Replaces telegraf_pfinterface.php and telegraf_gateways.php*

This single script collects information for Interfaces and gateways.

**Interfaces:**
* Interface name
* IP4 address
* IP4 subnet
* IP6 address
* IP6 subnet
* MAC address
* Friendly name
* Status (Online/Offline/Etc.)

**Gateways:**
* Interface name
* Monitor IP
* Source IP
* Default gw (True/False)
* GW Description
* Delay
* Stddev
* Loss (%)
* Status (Online/Offline/etc.)
* Substatus (None/Packetloss/Latency/Etc.)

This has been modified from the original python version to a native PHP script. The new script calls the builtin "return_gateways_status_text" function from "/etc/inc/gwlb.inc". In addition to the loss, rtt, and rttsd included in the original plugin, the new version will also return the gateway IP, monitored IP, status, and substatus of the gateway. This eliminates the guess work of whether or not pfSense has marked this gateway as down. 

# telegraf_unbound_lite.sh
#### This plugin is not in use on my system but it's still worth documenting.
If you only care about some of the unbound metrics and don't want to waste space collecting all the unwanted unbound metrics, here's a plugin for you.

# telegraf_netifinfo_plugin
#### This plugin is not in use on my system but it's still worth documenting.
I found a plugin to get IP, MAC, IF Name and Status. [On this thread](https://github.com/influxdata/telegraf/issues/3756#issuecomment-485606025 "On this thread") a user posts some go code for a telegraf plugin. I fired up a FreeBSD 11 ami on amazon, installed go and compiled a binary version of the code. It's worked as expected but my queries and formatting could use some help.

I saved the code as telegraf_netifinfo_plugin.go and compiled with the following commands:

    setenv CGO_ENABLED 0
    setenv GOOS freebsd
    setenv GOARCH amd64
    go build -o telegraf_netifinfo_plugin
