#!/usr/local/bin/php-cgi -f
<?php
// require_once("config.inc");
require_once("gwlb.inc");

$source = "pfconfig";
$gwstat = return_gateways_status($true);

foreach ($gwstat as $gw_ip => $gwaddress) {
    $gateway = $gw_ip;
    $monitor = $gwstat[$gw_ip]["monitorip"];
    $source = $gwstat[$gw_ip]["srcip"];
    $delay = $gwstat[$gw_ip]["delay"];
    $stddev = $gwstat[$gw_ip]["stddev"];
    $loss = $gwstat[$gw_ip]["loss"];
    $status = $gwstat[$gw_ip]["status"];
    $substatus = $gwstat[$gw_ip]["substatus"];

    printf("gateways,gateway_name=%s monitor_ip=\"%s\",gateway_ip=\"%s\",rtt=%s,rttsd=%s,loss=%si,status=\"%s\",substatus=\"%s\"\n",
        $gateway,
        $monitor,
        $source,
        floatval($delay),
        floatval($stddev),
        floatval($loss),
        $status,
        $substatus
    );
}
?>
