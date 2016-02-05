#!/bin/sh
## build system required files : className => FilePath
this_dir=$(dirname $0)
cd $this_dir/../

PHP=/usr/local/bin/php
PROJECT_HOME=$(pwd)
AUTOLOAD_PATH="$PROJECT_HOME/src"

# create project autoload files
# php exe_php scan_filepath dest_auto_load_file cache_key
$PHP $PROJECT_HOME/project/build_includes.php front $AUTOLOAD_PATH $PROJECT_HOME/src/www/front/auto_load.php "project_prjname_front:$USER:autoload:map"
$PHP $PROJECT_HOME/project/build_includes.php admin $AUTOLOAD_PATH $PROJECT_HOME/src/www/admin/auto_load.php "project_prjname_admin:$USER:autoload:map"
$PHP $PROJECT_HOME/project/build_includes.php task $AUTOLOAD_PATH $PROJECT_HOME/src/task/auto_load.php "project_prjname_task:$USER:autoload:map"
$PHP $PROJECT_HOME/project/build_includes.php test $AUTOLOAD_PATH $PROJECT_HOME/src/test/auto_load.php "project_prjname_test:$USER:autoload:map"


#HOST_NAME=$(hostname)
#SERVER_AREA=$(echo $HOST_NAME | awk -F"." '{print $3}')
#for SERVER_TYPE in front api
#do
#    SERVER_CONF="$PAY_SERVICE_HOME/config/${SERVER_TYPE}/server_conf.php"
#    if [ ! -f $SERVER_CONF ] 
#    then
#        ln -s $PAY_SERVICE_HOME/config/${SERVER_TYPE}/server/server_conf.release.${SERVER_AREA}.php $SERVER_CONF
#    fi
#done

#test mail
#curl -s "http://10.108.90.110/mini.php?kw=$(hostname)" -H'host:so.v.360.cn' > /dev/null
