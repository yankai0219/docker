#!/bin/sh
# 1. Basic Softwart
cur_dir=/tmp/source_code
mkdir -p $cur_dir 
cd $cur_dir
wget -c http://soft.vpser.net/web/pcre/pcre-8.12.tar.gz
tar zxf pcre-8.12.tar.gz
cd pcre-8.12/
./configure
make && make install
