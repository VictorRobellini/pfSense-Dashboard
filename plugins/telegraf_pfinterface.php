#!/usr/local/bin/php-cgi -f
<?php
require_once("config.inc");
require_once("gwlb.inc");

$host = gethostname();
$source = "pfconfig";

$gw_array = return_gateways_array();
$gw_statuses = return_gateways_status(true);

foreach ($gw_array as $gw => $gateway) {

    $name = $gw_statuses[$gw]["name"];
    $monitor = $gw_statuses[$gw]["monitorip"];
    $source = $gw_statuses[$gw]["srcip"];
    $delay = $gw_statuses[$gw]["delay"];
    $stddev = $gw_statuses[$gw]["stddev"];
    $loss = $gw_statuses[$gw]["loss"];
    $status = $gw_statuses[$gw]["status"];
    $substatus;

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

    if ($gw_statuses[$gw]) {
        if (isset($gateway['monitor_disable'])) {
            $monitor = "Unmonitored";
            $delay = "Pending";
            $stdev = "Pending";
            $loss = "Pending";
        }
    }


    // Some earlier versions of pfSense do not return substatus
    if (isset($gw_statuses[$gw]["substatus"])) {
        $substatus = $gw_statuses[$gw]["substatus"];
    } else {
        $substatus = "N/A";
    }

    printf("gateways,host=%s,interface=%s monitor=\"%s\",source=\"%s\",defaultgw=%s,gwdescr=\"%s\",delay=%s,stddev=%s,loss=%s,status=\"%s\",substatus=\"%s\"\n",
        $host,
        $interface,
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
