# "ported" by Adam Miller <maxamillion@fedoraproject.org> from
#   https://github.com/fedora-cloud/Fedora-Dockerfiles
#
# Originally written for Fedora-Dockerfiles by
#   scollier <scollier@redhat.com>

FROM daocloud.io/centos 
MAINTAINER yankai-c <yankai-c@360.cn>

## Install Nginx
RUN yum -y update; yum clean all
RUN yum -y install epel-release; yum clean all
RUN yum -y install nginx; yum clean all
RUN echo "daemon off;" >> /etc/nginx/nginx.conf
RUN echo "nginx on CentOS 6 inside Docker" > /usr/share/nginx/html/index.html

## Install PHP
RUN yum install yum-priorities -y
RUN yum install wget -y
RUN yum install php -y

RUN php -v
## import one repo which contains php
#RUN wget http://ar2.php.net/distributions/php-7.0.3.tar.gz
#RUN mkdir /tmp/php703
#RUN tar zxvf php-7.0.3.tar.gz -C /tmp/php703
#RUN cd /tmp/php703/php-7.0.3  \
#    && ls -l \
#    && ./configure --prefix=/usr/local/php7.0.3 --enable-fpm \
#    && make \
#    && make install 
#
#RUN ls -l /usr/local/php7.0.3
COPY default.conf /etc/nginx/conf.d/default.conf
RUN echo '<?php echo "hello world php fpm";?>' > /usr/share/nginx/html/index.php
RUN echo '"helo world html";' > /usr/share/nginx/html/index.html

COPY init_nginx_phpfpm.sh /usr/sbin/
RUN chmod a+x /usr/sbin/init_nginx_phpfpm.sh
CMD [ "/usr/sbin/init_nginx_phpfpm.sh" ]
