#!/bin/sh
docker ps -a | awk '{print $1}' | grep -v CONTAIN| xargs docker rm -f 
