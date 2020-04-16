#!/usr/local/bin/python3.7

# telegraf_gateways-3.7.py
# https://gist.github.com/fastjack/a0feb792a9655da7aa3e2a7a1d9f812f#file-gateways-py-for-pfsense-2-4-5
# Special thanks to fashjack - https://gist.github.com/fastjack

import glob, os, socket

DPINGER_SOCK_PATH = "/var/run/"

os.chdir(DPINGER_SOCK_PATH)

for sock_name in glob.glob("dpinger*.sock"):
    sock = socket.socket(socket.AF_UNIX, socket.SOCK_STREAM)
    sock_path = DPINGER_SOCK_PATH+sock_name
    s = sock.connect(sock_path)
    line = sock.recv(1024).decode().split('\n', 1)[0]
    values = line.split()
    print("gateways,gateway_name="+values[0]+" rtt="+str(int(values[1])/1.0)+ \
        ",rttsd="+str(int(values[2])/1.0)+",loss="+str(int(values[3]))+"i")
    sock.close()
