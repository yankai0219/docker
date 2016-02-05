#!	/usr/local/bin/bash


#########################################################################
#
#	Qihoo project source deploy tool: ����Ŀ¼�ȽϹ���
#	Writen by: bingchen <cb@qihoo.net>
#	http://task.corp.qihoo.net/browse/JYGROUP-184
#
#########################################################################


#	����
if [ $# -lt 1 ] || [ "-h" = "$1" ] || [ "--help" = "$1" ]
then
	echo "�÷�: $0 ONLINE_HOST";
	echo "ONLINE_HOST : �Աȵ�ǰ SVN �еİ汾�� ONLINE_HOST �Ĳ�ͬ"
	exit 0;
fi


###########################################################################
#	������

#   include lib
this_file=`pwd`"/"$0
this_dir=`dirname $this_file`
. $this_dir/deploy.sh


###########################################################################
#	Start

#	�� svn �� export trunk
cecho "=== export chunk from svn ===" $c_notify
DEPLOY_DIR="$HOME/deploy"
PROJECT_HOME="$DEPLOY_DIR/$prj_nam"
rm -rf $DEPLOY_DIR && mkdir -p $DEPLOY_DIR
svn export $svn $PROJECT_HOME > /dev/null 2>&1

#   �����ϰ汾������ 
host="$1"
online_src="/home/$ssh_user/${USER}.$prj_nam.tgz"
$SSH $host tar cvLfz $online_src $dst > /dev/null 2>&1
$SCP $host:$online_src $online_src > /dev/null 2>&1
local_online="$DEPLOY_DIR/online"
rm -rf $local_online && mkdir -p $local_online
tar xz -f $online_src -C $local_online

prefix=`echo "$PROJECT_HOME" | awk '{ gsub("/","\\\/"); print $0"\\\/"; }'`
dir_diff=`diff -rbB --brief $PROJECT_HOME ${local_online}${dst} | grep -v "Only in ${local_online}${dst}" | awk '{ if("Files"==$1) { print "!\t"$2; }; if("Only"==$1) { print "+\t"substr($3,1,length($3)-1)"/"$4 } }' | sed "s/$prefix//"`

#	�Ƚ��û�������ļ�
while [ 1 = 1 ]
do
    echo -e "$dir_diff"
	cread -p "����Ƚ��ļ���·����·���ο������������n�˳�����֡SVN: " file $c_notify

	if [ "n" = "$file" ]; then
		break;
	fi

	if [ "" = "$file" ]; then
		continue;
	fi

	local_file="$PROJECT_HOME/$file"
	online_file="${local_online}${dst}/$file"

	if [ ! -s "$local_file" ]; then
		cecho "û���ҵ��ļ���ȷ���ļ�·���Ƿ���ȷ��$file" $c_error
		continue;
	fi

    vimdiff $local_file $online_file
done

#	��������
rm -rf $DEPLOY_DIR
