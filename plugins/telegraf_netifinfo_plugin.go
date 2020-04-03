package main

import (
	"fmt"
	"net"
	"strings"
)

func main() {

	//----------------------
	// Get the local machine IP address
	// https://www.socketloop.com/tutorials/golang-how-do-I-get-the-local-ip-non-loopback-address
	//----------------------

	var currentIP, currentNetworkHardwareName string

	// get all the system's or local machine's network interfaces

	interfaces, _ := net.Interfaces()
	for _, interf := range interfaces {

		if addrs, err := interf.Addrs(); err == nil {
			for _, addr := range addrs {
				// check the address type and if it is not a loopback the display it
				// = GET LOCAL IP ADDRESS
				if ipnet, ok := addr.(*net.IPNet); ok && !ipnet.IP.IsLoopback() {
					if ipnet.IP.To4() != nil {
						currentIP = ipnet.IP.String()

						// only interested in the name with current IP address`
						if strings.Contains(addr.String(), currentIP) {
							currentNetworkHardwareName = interf.Name
						}

						macAddress := interf.HardwareAddr
						hwAddr, err := net.ParseMAC(macAddress.String())
						if err != nil {
							continue
						}

						currentNetworkHardwareName = strings.Replace(currentNetworkHardwareName, " ", "", -1)

						fmt.Printf("interface,name=%s,ip_address=%s,mac_address=%s status=1\n",
							currentNetworkHardwareName, currentIP, hwAddr)
					}
				}
			}
		}
	}
}