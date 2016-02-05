# "ported" by Adam Miller <maxamillion@fedoraproject.org> from
#   https://github.com/fedora-cloud/Fedora-Dockerfiles
#
# Originally written for Fedora-Dockerfiles by
#   scollier <scollier@redhat.com>

FROM nginx_php:latest
MAINTAINER yankai <yankai0219> 

RUN yum -y update && yum clean all
RUN yum -y install scl-utils && yum clean all

RUN yum install -y centos-release-SCL && yum clean all
RUN yum install -y --enablerepo=centosplus mysql55-mysql mysql55-mysql-server && yum clean all

VOLUME /var/lib/mysql

ADD my.cnf /opt/rh/mysql55/root/etc/my.cnf
ADD docker-entrypoint.sh /entrypoint.sh
RUN chmod u+x /entrypoint.sh

EXPOSE 3306
CMD [ '/entrypoint.sh' ]
