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
# 使用帮助
if [ $# -lt 1 ] || [ "-h" = "$1" ] || [ "--help" = "$1" ]
then
  cecho "用法: $0 基准host名称"
  cecho "$0 svn"
  cecho "$0 host1"
  cecho "输出所有与基准host的代码不同的文件列表"
  exit 0;
fi

init
###########################################################################
# 从svn获取当前的最新代码
if [ $1 == "svn" ]
then
	bench_host=$1
	CURRENT_REVISION=$(get_svn_head_revision $SVN_URL)
	CURRENT_TIME=$(now)
	BENCH_SOURCE_DIR=$LOCAL_TMP_DIR/$bench_host-$PROJECT_NAME-$CURRENT_REVISION-$CURRENT_TIME
	export_svn $SVN_URL $BENCH_SOURCE_DIR $CURRENT_REVISION
else
	bench_host=$1
	CURRENT_TIME=$(now)
	BENCH_SOURCE_DIR="$LOCAL_TMP_DIR/bench-$bench_host-$PROJECT_NAME-$CURRENT_TIME"
	get_online_src_all $bench_host $REMOTE_DEPLOY_DIR $BENCH_SOURCE_DIR
	BENCH_SOURCE_DIR=${BENCH_SOURCE_DIR}${REMOTE_DEPLOY_DIR}
fi

hosts=$online_cluster
for host in $hosts
do
	cecho "\n=== ${host} ===\n" $c_notify
	local_online="$LOCAL_TMP_DIR/$host-$PROJECT_NAME-$CURRENT_TIME"
	get_online_src_all $host $REMOTE_DEPLOY_DIR $local_online
	prefix=`echo "$BENCH_SOURCE_DIR" | awk '{ gsub("/","\\\/"); print $0"\\\/"; }'`
	dir_diff=`diff -rbB --brief $BENCH_SOURCE_DIR ${local_online}${REMOTE_DEPLOY_DIR} | grep -v "Only in ${local_online}${dst}" | grep -v "\.svn" | awk '{ if("Files"==$1) { print "!\t"$2; }; if("Only"==$1) { print "+\t"substr($3,1,length($3)-1)"/"$4 } }' | sed "s/$prefix//"`
	echo -e "$dir_diff"
done

clean
