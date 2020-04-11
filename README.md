## What's Monitored
- Active Users
- Uptime
- CPU Load total
- Disk Utilization
- Memory Utilization
- CPU Utilization per core (Single Graph)
- Ram Utilization time graph
- Load Average
- Load Average Graph
- CPU and ACPI Temperature Sensors
- pfBlocker IP Stats
- pfBlocker DNS Stats
- Gateway Response time - dpinger
- List of interfaces with IP, MAC, Status and pfSesnse labels thanks to [/u/trumee](https://www.reddit.com/r/PFSENSE/comments/fsss8r/additional_grafana_dashboard/fmal0t6/)
- WAN Statistics - Traffic & Throughput (Identified by dashboard variable)
- LAN Statistics - Traffic & Throughput (Identified by dashboard variable)
- Unbound stats - Plugin and config included and working but not implemented

![Screenshot](Grafana-pfSense.png)

## Running on

    Grafana 6.7.1
    Influxdb 1.7.10

## Configuration

### Grafana
The Config for the dashboard relies on the variables defined within the dashboard in Grafana.  When importing the dashboard, make sure to select your datasource. 

Dashboard Settings -> Variables

WAN - $WAN is a static variable defined so that a separate dashboard panel can be created for WAN interfaces stats.  Use a comma-separated list for multiple WAN interfaces.

LAN_Interfaces - $LAN_Interfaces uses a regex to remove any interfaces you don't want to be grouped as LAN. The filtering happens in the "Regex" field. I use a negative lookahead regex to match the interfaces I want excluded.  It should be pretty easy to understand what you need to do here. I have excluded igb0 (WAN) and igb2 (only used to host vlans).

After writing this up, I realize I need to change this variable name, it's just not going to happen right now. 

### Telegraf
[Telegraf Config](config/additional_config.conf)

In the [/config](config/additional_config.conf) directory you will find all of the additional telegraf config. In pfSense, under Services -> Teltegraf, at the bottom of the page with the teeny tiny text box is where you paste in the included config.

I also included the config for Unbound DNS and it's commented out.  I'm not currently using it, but it's fully functional, just uncomment if you want to use it.

### Plugins
[Plugins](plugins)

I put all my plugins in /usr/local/bin and set them to 555


I also included a wrapper script for Unbound DNS.  I'm not currently using it, but it's fully functional.
   
## Troubleshooting

### Telegraf Plugins

To troubleshoot plugins, add the following lines to the agent block in /usr/local/etc/telegraf.conf and send a HUP to the telegraf pid. You're going to need to do this from a ssh shell. One you update the config you are going to need to tell telegraf to read the new configs. If you restart telegraf from pfSense, this will not work since it will overwrite your changes.

#### Telegraf Config
    debug = true
    quiet = false
    logfile = "/var/log/telegraf/telegraf.log"

#### Restarting Telegraf
    # ps -aux | grep -i telegraf
    # kill -HUP <pid of telegraf proces>
    
### InfluxDB
When in doubt, run a few queries to see if the data you are looking for is being populated.

    bash-4.4# influx
    Connected to http://localhost:8086 version 1.7.10
    InfluxDB shell version: 1.7.10
    > show databases
    name: databases
    name
    ----
    pfsense
    _internal
    > use pfsense
    Using database pfsense
    > show measurements
    name: measurements
    name
    ----
    cpu
    disk
    diskio
    dnsbl_log
    gateways
    interface
    ip_block_log
    mem
    net
    pf
    processes
    swap
    system
    temperature
    > select * from system limit 20
    name: system
    time                host                     load1         load15        load5         n_cpus n_users uptime     uptime_format
    ----                ----                     -----         ------        -----         ------ ------- ------     -------------
    1585272640000000000 pfSense.home         0.0615234375  0.07861328125 0.0791015625  4      1       196870     2 days,  6:41
    1585272650000000000 pfSense.home         0.05126953125 0.07763671875 0.076171875   4      1       196880     2 days,  6:41
    1585272660000000000 pfSense.home         0.04296875    0.07666015625 0.0732421875  4      1       196890     2 days,  6:41
    1585272670000000000 pfSense.home         0.03564453125 0.07568359375 0.0703125     4      1       196900     2 days,  6:41
    1585272680000000000 pfSense.home         0.02978515625 0.07470703125 0.0673828125  4      1       196910     2 days,  6:41
    1585272690000000000 pfSense.home         0.02490234375 0.07373046875 0.064453125   4      1       196920     2 days,  6:42
    ...

## [Original Reddit thread](https://www.reddit.com/r/PFSENSE/comments/fsss8r/additional_grafana_dashboard/ "Originial Reddit thread")

I was going to post this in the thread made by [/u/seb6596](https://www.reddit.com/u/seb6596 "/u/seb6596") since this is based on [their dashboard](https://www.reddit.com/r/PFSENSE/comments/fsf7f7/my_pfsense_monitor_dashboard_in_grafana/ "their dashboard"), but I made quite a few changes and wanted to include information that would get lost in the thread.

What I updated:

- Created dashboard wide variables to make the dashboard more portable and easily configurable. You shouldn't need to update any of the queries.
- Took some inspiration and panels [from this dashboard](https://grafana.com/grafana/dashboards/9806 "from this dashboard")
- Included gateway RTT from dpinger thanks to [this integration](https://forum.netgate.com/topic/142093/can-telegraf-package-gather-latency-packet-loss-information/3 "this integration")
- Used[ telegraf configs](https://www.reddit.com/r/pfBlockerNG/comments/bu0ms0/pfblockerngtelegrafinfluxdb_ip_block_list/ " telegraf configs") from this post by [/u/PeskyWarrior](https://www.reddit.com/u/PeskyWarrior "/u/PeskyWarrior")
- Tag, templating - No need to specify all cpus or interfaces in the graph queries. These values are pulled in with queries.
- Added chart to show all adapters, IP, MAC and Status[ from here](https://github.com/influxdata/telegraf/issues/3756#issuecomment-485606025 " from here")
- Added Temperature data based on feedback from[ /u/tko1982](https://www.reddit.com/u/tko1982 " /u/tko1982") - CPU Temp and any other ACPI device that reports temp is now collected and reported

### TODO

- Include IP and ping methods from [/u/seb6596](https://www.reddit.com/u/seb6596 "/u/seb6596") when they are back online.
- Make it pretty. I've never been good at this part
- Get the RTT calculations right from the dpinger integration. It's in microseconds but for some reason doesn't match the graphs in pfSense when I compare them.
- Figure out if I can show subnet and media speed/duplex for the interfaces
- Use the pfSense labels in the graphs that show network stats - 2 different measurements
