#!/bin/bash


####################################################################################################
#   配置项

#   include lib
this_file=`pwd`"/"$0

DEPLOY_TOOLS_DIR=`dirname $this_file`
. $DEPLOY_TOOLS_DIR/conf.sh
. $DEPLOY_TOOLS_DIR/utils.sh


####################################################################################################
# 使用帮助
if [ $# -lt 1 ] || [ "-h" = "$1" ] || [ "--help" = "$1" ]
then
	echo "用法: $0 FILE";
	echo "FILE* :  revert 所使用的备份文件"
	echo "FILE* :  /home/sync360/deploy_history/qcloud/20111219183211-qcloud-bak.tgz" 
    echo "-------------------------------"
	echo "用法: $0 ls";
    echo "列出部署的历史，每次备份的文件位置"

	exit 0;
fi

###################################################################################################
if [ $1 == "ls" ]
then
	no=0
	echo "deploy history file is $DEPLOY_HISTORY_FILE"
	for line in `cat $DEPLOY_HISTORY_FILE`
	do
		no=`echo "$no + 1" | bc`
		cecho "$no\t$line";
	done
	exit 0;
fi


revert_src_tgz=$1
#   开始回滚
hosts=$online_cluster
echo $hosts
for host in ${hosts}
do
	cecho "\n=== ${host} ===\n" $c_notify
    echo $SSH
	$SSH $host "test -s $revert_src_tgz"
	if [ 0 -ne $? ]; then
		cecho "\t错误：远程主机原始备份文件不存在" $c_error
		deploy_confirm "    是否继续 ?"
		if [ 1 != $? ]; then
			exit 1;
		else
			continue
		fi
	fi
	cecho "\n=== ${host} 回滚文件列表===\n" $c_notify
	$SSH $host tar xvfz $revert_src_tgz -C $REMOTE_DEPLOY_DIR
	if [ 0 -ne $? ]; then
		cecho "\t错误：$host  回滚失败 " $c_error
	fi
done


deploy_confirm " revert 完成， 是否删除deploy_history中的这个记录?"
if [ 1 != $? ]; then
	exit 0;
else
	cat $DEPLOY_HISTORY_FILE > $DEPLOY_HISTORY_FILE_BAK
	cat $DEPLOY_HISTORY_FILE_BAK | grep -v "$revert_src_tgz" > $DEPLOY_HISTORY_FILE
fi
