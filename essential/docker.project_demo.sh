#!/bin/sh
## init environment
ln -s /home/yankai/demo_php/config/nginx/nginx.conf /usr/local/nginx/conf/include/yk.ngx.conf

## start php-fpm and nginx
ps aux | grep php-fpm | awk '{print $2}' | xargs kill -9
/usr/local/php/sbin/php-fpm
/etc/init.d/nginx restart

## keep alive
while true; do
    echo 'helloworl' > /tmp/cc
done
