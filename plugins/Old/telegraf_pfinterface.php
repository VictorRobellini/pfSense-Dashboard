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
?>
