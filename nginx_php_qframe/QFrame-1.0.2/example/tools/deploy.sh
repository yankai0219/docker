#!	/usr/local/bin/bash

#########################################################################
#
#	Qihoo project source deploy tool
#	Writen by: bingchen <cb@qihoo.net>
#	http://task.corp.qihoo.net/browse/JYGROUP-184
#
#########################################################################


###########################################################################
#	������

#	��Ŀ����
PROJECT_HOME="__PROJECT_HOME__"

#	���ϼ�Ⱥ�б�
online_clusters="";

#	���Ի���
beta_clusters="";

#	Ŀ�������Ŀ¼
dst="__RELEASE_HOME__";

#	SVN ��ַ
svn=""

#	ͬ����ʹ�õ��û�
ssh_user="search";

#	�ļ�������
blacklist='(.*\.tmp$)|(.*\.log$)|(.*\.svn.*)'


###########################################################################
#	������

#	print colored text
#	$1 = message
#	$2 = color

#	��ʽ�����
export black='\E[0m\c'
export boldblack='\E[1;0m\c'
export red='\E[31m\c'
export boldred='\E[1;31m\c'
export green='\E[32m\c'
export boldgreen='\E[1;32m\c'
export yellow='\E[33m\c'
export boldyellow='\E[1;33m\c'
export blue='\E[34m\c'
export boldblue='\E[1;34m\c'
export magenta='\E[35m\c'
export boldmagenta='\E[1;35m\c'
export cyan='\E[36m\c'
export boldcyan='\E[1;36m\c'
export white='\E[37m\c'
export boldwhite='\E[1;37m\c'

cecho()
{
	message=$1
	color=${2:-$black}

	echo -e "$color"
	echo -e "$message"
	tput sgr0			# Reset to normal.
	echo -e "$black"
	return
}

cread()
{
	color=${4:-$black}

	echo -e "$color"
	read $1 "$2" $3 
	tput sgr0			# Reset to normal.
	echo -e "$black"
	return
}

#	ȷ���û�������
deploy_confirm()
{
	while [ 1 = 1 ]
	do
		cread -p "$1 [y/n]: " CONTINUE $c_notify 	  
		if [ "y" = "$CONTINUE" ]; then
		  return 1;
		fi

		if [ "n" = "$CONTINUE" ]; then
		  return 0;
		fi
	done

	return 0;
}


###########################################################################
#	Start

export LC_ALL="zh_CN.GB2312"

#PROJECT_HOME=`echo "echo \$""${project}_HOME" | sh`

#	ȷ����Ŀ¼
if [ -z "$PROJECT_HOME" ]; then
	echo "���õ�ǰ�û�������Ŀ¼�Ļ���������$PROJECT_HOME"
	exit
fi

prj_nam=`basename $dst`

#	
SSH="sudo -u $ssh_user ssh -c blowfish"
SCP="sudo -u $ssh_user scp -c blowfish"

#	��ʾ��ɫ
c_notify=$boldcyan
c_error=$boldred
