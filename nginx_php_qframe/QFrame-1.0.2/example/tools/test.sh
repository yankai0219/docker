#!	/usr/local/bin/bash


#########################################################################
#
#	Qihoo project source deploy tool: 线上目录比较工具
#	Writen by: bingchen <cb@qihoo.net>
#	http://task.corp.qihoo.net/browse/JYGROUP-184
#
#########################################################################


################################################################################
#   配置项

#   include lib
this_file=`pwd`"/"$0

DEPLOY_TOOLS_DIR=`dirname $this_file`
. $DEPLOY_TOOLS_DIR/conf.sh
. $DEPLOY_TOOLS_DIR/utils.sh

################################################################################

#get_remote_os 192.168.33.192

sudo_ssh_run 192.168.33.192 "cd /home/q/system/;echo hello"
