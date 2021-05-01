#!/usr/local/bin/php-cgi -f
<?php
require_once("config.inc");
require_once("gwlb.inc");
require_once("interfaces.inc");

$host = gethostname();
$source = "pfconfig";

$iflist = get_configured_interface_with_descr(true);
foreach ($iflist as $ifname => $friendly) {
	$ifinfo =  get_interface_info($ifname);
	$ifstatus = $ifinfo['status'];
	$ifconf = $config['interfaces'][$ifname];
	$ip4addr = get_interface_ip($ifname);
	$ip4subnet = get_interface_subnet($ifname);
	$ip6addr = get_interface_ipv6($ifname);
	$ip6subnet = get_interface_subnetv6($ifname);
	$realif = get_real_interface($ifname);
	$mac = get_interface_mac($realif);

	if (!isset($ip4addr)){
		$ip4addr = "Unassigned";
	}
	if (!isset($ip4subnet)){
		$ip4subnet = "Unassigned";
	}
	if (!isset($ip6addr)){
		$ip6addr = "Unassigned";
	}
	if (!isset($ip6subnet)){
		$ip6subnet = "Unassigned";
	}
	if (!isset($mac)){
		$mac = "Unavailable";
	}
	if (strtolower($ifstatus) == "up"){
		$ifstatus = 1;
	}
	if (strtolower($ifstatus) == "active"){
		$ifstatus = 1;
	}
	if (strtolower($ifstatus) == "no carrier"){
		$ifstatus = 0;
	}
	if (strtolower($ifstatus) == "down"){
		$ifstatus = 0;
	}
	if (!isset($ifstatus)){
		$ifstatus = 2;
	}

	printf("interface,host=%s,name=%s,ip4_address=%s,ip4_subnet=%s,ip6_address=%s,ip6_subnet=%s,mac_address=%s,friendlyname=%s,source=%s status=%s\n",
		$host,
		$realif,
		$ip4addr,
		$ip4subnet,
		$ip6addr,
		$ip6subnet,
		$mac,
		$friendly,
		$source,
		$ifstatus
	);
}

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
