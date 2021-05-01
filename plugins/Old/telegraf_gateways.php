#!/usr/local/bin/php-cgi -f
<?php
require_once("config.inc");
require_once("gwlb.inc");

$host = gethostname();
$source = "pfconfig";

$a_gateways = return_gateways_array();
$gateways_status = return_gateways_status(true);

foreach ($a_gateways as $i => $gateway) {

    $name = $gateways_status[$i]["name"];
    $monitor = $gateways_status[$i]["monitorip"];
    $source = $gateways_status[$i]["srcip"];
    $delay = $gateways_status[$i]["delay"];
    $stddev = $gateways_status[$i]["stddev"];
    $loss = $gateways_status[$i]["loss"];
    $status = $gateways_status[$i]["status"];
    $substatus = $gateways_status[$i]["substatus"];

    $interface = $gateway["interface"];
    $friendlyname = $gateway["friendlyiface"]; # This is not the friendly interface name so I'm not using it
    $friendlyifdescr = $gateway["friendlyifdescr"];
    $gwdescr = $gateway["descr"];
    $defaultgw = $gateway['isdefaultgw'];

    if (isset($gateway['isdefaultgw'])) {
        $defaultgw = "1";
    } else {
        $defaultgw = "0";
    }

    if ($gateways_status[$i]) {
        if (isset($gateway['monitor_disable'])) {
            $monitor = "Unmonitored";
            $delay = "Pending";
            $stdev = "Pending";
            $loss = "Pending";
        }
    }

    printf("gateways,host=%s,interface=%s monitor=\"%s\",source=\"%s\",defaultgw=%s,gwdescr=\"%s\",delay=%s,stddev=%s,loss=%s,status=\"%s\",substatus=\"%s\"\n",
        $host,
        $interface,
        # $friendlyifdescr,
        #$name,
        $monitor,
        $source,
        $defaultgw,
        $gwdescr,
        floatval($delay),
        floatval($stddev),
        floatval($loss),
        $status,
        $substatus
    );
};
?>
