### Running on

    Grafana 6.7.1
    Influxdb 1.7.10

[Original Reddit thread](https://www.reddit.com/r/PFSENSE/comments/fsss8r/additional_grafana_dashboard/ "Originial Reddit thread")

I was going to post this in the thread made by [/u/seb6596](https://www.reddit.com/u/seb6596 "/u/seb6596") since this is based on [their dashboard](https://www.reddit.com/r/PFSENSE/comments/fsf7f7/my_pfsense_monitor_dashboard_in_grafana/ "their dashboard"), but I made quite a few changes and wanted to include information that would get lost in the thread.

What I updated:

- Created dashboard wide variables to make the dashboard more portable and easily configurable. You shouldn't need to update any of the queries.
- Took some inspiration and panels [from this dashboard](https://grafana.com/grafana/dashboards/9806 "from this dashboard")
- Included gateway RTT from dpinger thanks to [this integration](https://forum.netgate.com/topic/142093/can-telegraf-package-gather-latency-packet-loss-information/3 "this integration")
- Used[ telegraf configs](https://www.reddit.com/r/pfBlockerNG/comments/bu0ms0/pfblockerngtelegrafinfluxdb_ip_block_list/ " telegraf configs") from this post by [/u/PeskyWarrior](https://www.reddit.com/u/PeskyWarrior "/u/PeskyWarrior")
- Tag, templating - No need to specify all cpus or interfaces in the graph queries. These values are pulled in with queries.
- Added chart to show all adapters, IP, MAC and Status[ from here](https://github.com/influxdata/telegraf/issues/3756#issuecomment-485606025 " from here")
- Added Temperature data based on feedback from[ /u/tko1982](https://www.reddit.com/u/tko1982 " /u/tko1982") - CPU Temp and any other ACPI device that reports temp is now collected and reported
- Cleaned up the telegraf config

The dashboard has a bunch of variables that you don't need to mess with. The only one you will want to change is the $WAN. Just set that to your WAN interface and the graphs will update accordingly. There is a row for WAN network statistics that keys off of the $WAN variable. The LAN statistics include data for all interfaces excluding $WAN. You could easily apply this to any other interface you want to highlight.

What I didn't do and need help with:

- Include IP and ping methods from [/u/seb6596](https://www.reddit.com/u/seb6596 "/u/seb6596") when they are back online.
- Make it pretty. I've never been good at this part
- Get the pfBlocker (IP & DNS) panels right. It's got something to do with "AND $timeFilter" but I'm pretty new and still learning.
- Get the RTT calculations right from the dpinger integration. It's in microseconds but for some reason doesn't match the graphs in pfSense when I compare them.

### Plugins
I put all my plugins in /usr/local/bin and set them to 555

To troubleshoot plugins, add the following lines to the agent block in /usr/local/etc/telegraf.conf

    debug = true
    quiet = false
    logfile = "/var/log/telegraf/telegraf.log"
    
In pfSense, under Services -> Teltegraf, at the bottom of the page with the teeny tiny text box, I have the following additional configuration included the extra config listed in the config dir
