#!/usr/local/bin/python2.7
# Taken From
# https://forum.netgate.com/topic/142093/can-telegraf-package-gather-latency-packet-loss-information/3

import glob, os, socket

DPINGER_SOCK_PATH = "/var/run/"

os.chdir(DPINGER_SOCK_PATH)

for sock_name in glob.glob("dpinger*.sock"):
    sock = socket.socket(socket.AF_UNIX, socket.SOCK_STREAM)
    sock_path = DPINGER_SOCK_PATH+sock_name
    s = sock.connect(sock_path)
    line = sock.recv(1024).split('\n', 1)[0]
    values = line.split()
    print "gateways,gateway_name=" + values[0] + " rtt=" + str(int(values[1])/100.0) + \
          ",rttsd=" + str(int(values[2])/100.0) + ",loss=" + str(int(values[3])) + "i"
    sock.close()
