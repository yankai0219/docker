#!/bin/sh
/etc/init.d/nginx start
while true; do
    echo 'helloworl' > /tmp/cc
done
