#!/bin/sh
## init environment
root=/home/yankai/devspace/demo/src/www/front;
mkdir -p $root
cd $root
echo '<?php echo "hello world";?>' > index.php

## start php-fpm and nginx
ps aux | grep php-fpm | awk '{print $2}' | xargs kill -9
/usr/local/php/sbin/php-fpm
/etc/init.d/nginx restart

## keep alive
while true; do
    echo 'helloworl' > /tmp/cc
done
