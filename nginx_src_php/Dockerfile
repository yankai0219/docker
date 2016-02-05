# "ported" by Adam Miller <maxamillion@fedoraproject.org> from
#   https://github.com/fedora-cloud/Fedora-Dockerfiles
#
# Originally written for Fedora-Dockerfiles by
#   scollier <scollier@redhat.com>

FROM centos:basic
MAINTAINER Keything <yankai0219126@gmail.com>

RUN mkdir /source_code
COPY install_dependency.sh /source_code/
COPY install_nginx.sh /source_code/
RUN which wget
# RUN cd /source_code \
#     && sh install_dependency.sh \
#     && sh install_nginx.sh
