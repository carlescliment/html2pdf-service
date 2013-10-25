html2pdf-service
================

A REST microservice that converts html input into pdf files. Written in Silex.


## Installation

Add permissions to the documents folder.

```
$ APACHEUSER=`ps aux | grep -E '[a]pache|[h]ttpd' | grep -v root | head -1 | cut -d\  -f1`
$ sudo setfacl -R -m u:$APACHEUSER:rwX -m u:`whoami`:rwX documents
$ sudo setfacl -dR -m u:$APACHEUSER:rwX -m u:`whoami`:rwX documents
```