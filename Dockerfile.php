# Maintainer By yankai-c <yankai-c@360.cn> from
#  https://github.com/yankai0219/docker.git
#

FROM yk/centos_nginx
MAINTAINER yankai-c <yankai-c@360.cn>

## basic enviroment
## install php
RUN wget http://cn2.php.net/distributions/php-5.6.18.tar.gz -O /tmp/php-5.6.18.tar.gz; \
    tar zxvf /tmp/php-5.6.18.tar.gz -C /tmp/; \
    cd /tmp/php-5.6.18; \
    ./configure --prefix=/usr/local/php --with-config-file-path=/usr/local/php/etc --with-mysql=mysqlnd --with-mysqli=mysqlnd --with-pdo-mysql=mysqlnd -with-jpeg-dir --with-png-dir --with-zlib --with-libxml-dir=/usr --enable-xml --disable-rpath --enable-bcmath --enable-shmop --enable-sysvsem --enable-inline-optimization --with-curl --enable-mbregex --enable-mbstring --with-mcrypt --enable-ftp --with-gd --enable-gd-native-ttf --with-openssl --with-mhash --enable-pcntl --enable-sockets --with-xmlrpc --enable-zip --enable-soap --with-gettext --disable-fileinfo --enable-opcache --enable-fpm; \
    make && make install

# configure php
RUN ln -s /usr/local/php/bin/php /usr/local/bin/; \
    cd /usr/local/php/etc; \ 
    sed 's/;error_log/error_log/' php-fpm.conf.default > php-fpm.conf; \
    cd /usr/local/php/etc/php-fpm.d; \
    sed 's/user = nobody/user = www/' www.conf.default > www.conf.tmp; \
    sed 's/group = nobody/group = www/' www.conf.tmp > www.conf; \
    rm -rf www.conf.tmp;
## the following configure cannot work well
#COPY init.d/init.d.php-fpm5.2 /etc/init.d/php-fpm
#RUN chmod +x /etc/init.d/php-fpm

## start php-fpm when it runs
EXPOSE 9000
#CMD /etc/init.d/php-fpm start
#CMD /usr/local/php/sbin/php-fpm
# 不知什么原因 现在php-fpm不能够通过CMD启动，只能临时采用这种方式启动
COPY essential/docker.php.start.sh /tmp/docker.php.start.sh 
RUN chmod a+x /tmp/docker.php.start.sh
CMD /tmp/docker.php.start.sh
