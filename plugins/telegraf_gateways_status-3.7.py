#!/usr/local/bin/python3.7

from datetime import datetime
import subprocess
import sys

php_str = """php -r "require_once('/etc/inc/gwlb.inc'); print(return_gateways_status_text());" """

try:

    proc = subprocess.Popen(php_str, shell=True, stdout=subprocess.PIPE)
    output = proc.stdout.read().decode('utf-8')

    fields_output, gw_output = output.split("\n",1)
    gateways_text = gw_output.split("\n")
    fields = fields_output.split()

    for gw in gateways_text:
        if len(gw) > 0:
            gw_str="gateways,gateway_name="
            values = gw.split()
            for i,val in enumerate(values):
                field = fields[i].lower()
                if field in ['monitor','source','status','substatus']:
                    if field == 'monitor':
                        field = 'monitor_ip'
                    elif field == 'source':
                        field = 'gateway_ip'
                    val = f'"{val}"'
                elif field == 'delay':
                    field = 'rtt'
                    val = val.replace('ms','')
                elif field == 'stddev':
                    field = 'rttsd'
                    val = val.replace('ms','')
                elif field == 'loss':
                    val = str(int(float(val.replace('%','')))) + "i"
                if i == 0:
                    gw_str+=str(val) + " "
                else:
                    gw_str+=str(field)+"="+str(val)+","
            gw_str=gw_str[:-1]
            print(gw_str)

except Exception as e:
    print(e, file=sys.stderr)
