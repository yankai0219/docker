# "ported" by Adam Miller <maxamillion@fedoraproject.org> from
#   https://github.com/fedora-cloud/Fedora-Dockerfiles
#
# Originally written for Fedora-Dockerfiles by
#   scollier <scollier@redhat.com>

FROM nginx:centos6
MAINTAINER The CentOS Project <cloud-ops@centos.org>
## Install PHP

RUN yum install yum-priorities -y

## import one repo which contains php
RUN rpm -Uvh http://repo.webtatic.com/yum/el6/latest.rpm
RUN yum -y install php55w \
		php55wcli \
		php55w-common \
		php55w-gd \
		php55w-ldap \
		php55w-mbstring \
		php55w-mcrypt \
		php55w-mysql \
		php55w-pdo
RUN yum -y install php55w-fpm
COPY default.conf /etc/nginx/conf.d/default.conf
RUN echo '<?php echo "hello world php fpm";?>' > /usr/share/nginx/html/index.php
RUN echo '"helo world html";' > /usr/share/nginx/html/index.html

COPY init_nginx_phpfpm.sh /usr/sbin/
RUN chmod a+x /usr/sbin/init_nginx_phpfpm.sh

RUN mkdir -p /home/q/php/QFrame
COPY QFrame-1.0.2 /home/q/php/QFrame
RUN mkdir -p /home/yankai/devspace/demo
COPY demo /home/yankai/devspace/demo

RUN ln -s /home/yankai/devspace/demo/config/front/httpd/demo.front.conf.yankai /etc/nginx/conf.d/demo.yankai.conf
RUN cd /home/yankai/devspace/demo/config/front
RUN ln -s server/server_conf.demo.front.php.yankai server_conf.php

CMD [ "/usr/sbin/init_nginx_phpfpm.sh" ]
