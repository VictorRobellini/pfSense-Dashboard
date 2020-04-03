#!/bin/sh
/usr/local/sbin/unbound-control -c /var/unbound/unbound.conf $* | grep -vE 'thread[0-9]+'
