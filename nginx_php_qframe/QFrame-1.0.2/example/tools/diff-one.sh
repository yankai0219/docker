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
if [ $# -lt 2 ] || [ "-h" = "$1" ] || [ "--help" = "$1" ]
then
    cecho "用法: $0 host1 host2";
    cecho "列出host1和host2的源码不同"
    cecho "    host1可以是svn"
    cecho "用法: $0 svn host2";
    exit 0;
fi

init
###########################################################################
# 从svn获取当前的最新代码
if [ "$1" == "svn" ]
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


# 获取线上代码列表
host="$2"
local_online="$LOCAL_TMP_DIR/online-$PROJECT_NAME-$CURRENT_TIME"
get_online_src_all $host $REMOTE_DEPLOY_DIR $local_online

prefix=`echo "$BENCH_SOURCE_DIR" | awk '{ gsub("/","\\\/"); print $0"\\\/"; }'`
dir_diff=`diff -rbB --brief $BENCH_SOURCE_DIR ${local_online}${REMOTE_DEPLOY_DIR} | grep -v "Only in ${local_online}${dst}" | grep -v "\.svn" | awk '{ if("Files"==$1) { print "!\t"$2; }; if("Only"==$1) { print "+\t"substr($3,1,length($3)-1)"/"$4 } }' | sed "s/$prefix//"`


#	比较用户输入的文件
while [ 1 = 1 ]
do
	if [ -z "$dir_diff" ]
	then 
		cecho "没有找到不同的文件" $c_notify
		exit 0
	fi

    echo -e "$dir_diff"
	cread -p "输入比较文件的路径（路径参考以上输出），n退出；左帧SVN: " file $c_notify

	if [ "n" = "$file" ]; then
		break;
	fi

	if [ "" = "$file" ]; then
		continue;
	fi

	local_file="$BENCH_SOURCE_DIR/$file"
	online_file="${local_online}${REMOTE_DEPLOY_DIR}/$file"

	if [ ! -s "$local_file" ]; then
		cecho "没有找到文件，确认文件路径是否正确：$file" $c_error
		continue;
	fi

    vimdiff $local_file $online_file
done

clean
