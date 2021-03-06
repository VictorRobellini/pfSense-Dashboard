# telegraf_pfinterface.php
IP Address and subnet for IPv4 and IPv6 are collected.  I don't have an ipv6 on the dashbaord because I don't use it.

# telegraf_gateways
Depending on how recent your pfSense install is, you may need to use the python 3.7 version of the plugin. If you are running 2.4.5, you probably want telegraf_gateways-3.7.py. They both output he same data in the same format.

All I did was copy telegraf_gateways-3.7.py to /usr/local/bin and rename it to telegraf_gateways.py
## Python 2.7

Does /usr/local/bin/python2.7 exist on your pfSense system? If so, use this telegraf_gateways-2.7.py
## Python 3.7

Does /usr/local/bin/python3.7 exist on your pfSense system? If so, use this telegraf_gateways-3.7.py

If you have both 2.7 and 3.7 on your system, use 3.7

Thanks to [this thread](https://forum.netgate.com/topic/152132/grafana-dashboard-using-telegraf-with-additional-plugins/16), user [bigjohns97](https://forum.netgate.com/user/bigjohns97) for following up and [fastjack](https://gist.github.com/fastjack) for the dev effort.

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
