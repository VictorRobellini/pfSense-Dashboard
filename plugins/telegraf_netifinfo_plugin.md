I found a plugin to get IP, MAC, IF Name and Status. [On this thread](https://github.com/influxdata/telegraf/issues/3756#issuecomment-485606025 "On this thread") a user posts some go code for a telegraf plugin. I fired up a FreeBSD 11 ami on amazon, installed go and compiled a binary version of the code. It's worked as expected but my queries and formatting could use some help.

I saved the code as telegraf_netifinfo_plugin.go and compiled with the following commands:

    setenv CGO_ENABLED 0
    setenv GOOS freebsd
    setenv GOARCH amd64
    go build -o telegraf_netifinfo_plugin

