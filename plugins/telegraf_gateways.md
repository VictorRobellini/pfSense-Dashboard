
Depending on how recent your pfSense install is, you may need to use the python 3.7 version of the plugin.
If you are running 2.4.5, you probably want telegraf_gateways-3.7.py. They both output he same data in the same format.

All I did was copy telegraf_gateways-3.7.py to /usr/local/bin and rename it to telegraf_gateways.py 

### Python 2.7
Does /usr/local/bin/python2.7 exist on your pfSense system?  If so, use this
telegraf_gateways-2.7.py

### Python 3.7
Does /usr/local/bin/python3.7 exist on your pfSense system?  If so, use this
telegraf_gateways-3.7.py

If you have both 2.7 and 3.7 on your system, use 3.7
