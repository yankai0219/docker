#!/bin/bash

#########################################################################
#
#	Qihoo project source deploy tool
#	Writen by: bingchen <cb@qihoo.net>
#	http://task.corp.qihoo.net/browse/JYGROUP-184
#
#########################################################################


###########################################################################
#	配置项

#	项目名称
#project="TWIDDER"
PROJECT_HOME="/usr/home/chenchao/project/fw"

#	线上集群列表
#online_clusters="192.168.0.133 192.168.0.171 192.168.0.172 192.168.0.134 192.168.0.131 192.168.0.175 192.168.0.131 192.168.33.191 hao2.safe.lfc.qihoo.net hao2.safe.lft.qihoo.net w1.scg.lfc.qihoo.net w1.scg.lft.qihoo.net w3.quc.lft.qihoo.net w3.quc.lfc.qihoo.net db2.scg.lft.qihoo.net w2.safe.lfc.qihoo.net k3.safe.lfc.qihoo.net k3.safe.lft.qihoo.net soft1.safe.lft.qihoo.net box1.safe.lfc.qihoo.net box1.safe.lft.qihoo.net w1.brow.lft.qihoo.net w2.brow.lft.qihoo.net w3.brow.lft.qihoo.net w5.quc.lfc.qihoo.net w5.quc.lft.qihoo.net w7.quc.lft.qihoo.net w8.quc.lft.qihoo.net w9.quc.lft.qihoo.net 192.168.0.191 192.168.33.193";
online_clusters="w09.add.bjt  w19v.add.bjt w05.add.dxt w06.add.dxt w07.add.dxt w08.add.dxt w09.add.dxt w10.add.dxt w11.add.dxt w1.pay.dxt w2.pay.dxt app01.add.bjt app02.add.bjt app03.add.bjt app04.add.bjt app01.add.dxt app02.add.dxt app03.add.dxt app04.add.dxt vot1.ipt.bjt vot1.ipt.dxt vot2.ipt.bjt vot2.ipt.dxt w1.quc.bjt w1.quc.dxt w2.quc.bjt w2.quc.dxt w1.cms.bjt.qihoo.net w1v.ipt.dxt.qihoo.net w1v.ipt.bjt.qihoo.net w2v.ipt.bjt.qihoo.net w3v.ipt.dxt w3v.ipt.bjt w4v.ipt.dxt w4v.ipt.bjt w2v.ipt.dxt w7v.ipt.dxt w7v.ipt.bjt w8v.ipt.bjt w8v.ipt.dxt hao5.safe.bjt "
online_clusters="hao5.safe.bjt hao6.safe.bjt hao7.safe.bjt hao8.safe.dxt hao9.safe.dxt hao10.safe.dxt"

SYNC_NEW_VER="192.168.0.181 192.168.0.172 10.104.79.31 10.104.79.30 10.102.79.36 10.102.79.35 10.102.79.13"
SEARCH_NEW_VER="220.181.127.201"
online_clusters="$SEARCH_NEW_VER"
#online_clusters="w-w01.add.ccc w-w02.add.ccc w-w03.add.ccc w-w04.add.ccc w-w05.add.ccc w-w01.add.cct w-w02.add.cct w-w03.add.cct w-w04.add.cct w-w05.add.cct w1v.add.cct w1v.add.ccc w2v.add.cct w2v.add.ccc app01.add.ccc app02.add.ccc app03.add.ccc app04.add.ccc app05.add.ccc app06.add.ccc app01.add.cct app02.add.cct app03.add.cct app04.add.cct app05.add.cct app06.add.cct w5v.add.ccc w6v.add.ccc w5v.add.cct w6v.add.cct w-w06.add.ccc w-w07.add.ccc w-w08.add.ccc w-w06.add.cct w-w07.add.cct w-w08.add.cct w01v.add.ccc w02v.add.ccc w01v.add.cct w02v.add.cct w03v.add.ccc w04v.add.ccc w03v.add.cct w04v.add.cct"

#	测试机器
beta_clusters="w3.web.lft.qihoo.net";

#	目标机器的目录
dst="/home/q/php/QFrame/";

#   目标安装路径
install_dst="/home/q/php/QFrame-1.0.1/"

#	SVN 地址
svn="https://ipt.src.corp.qihoo.net/svn/fw/trunk/"

#	同步所使用的用户
ssh_user="search";
ssh_user="sync360";
#ssh_user="chenchao";

#	文件黑名单
blacklist='(.*\.tmp$)|(.*\.log$)|(.*\.svn.*)'


###########################################################################
#	公共库

#	print colored text
#	$1 = message
#	$2 = color

#	格式化输出
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

#	确认用户的输入
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

#	确定根目录
if [ -z "$PROJECT_HOME" ]; then
	echo "先置当前用户工作根目录的环境变量：$PROJECT_HOME"
	exit
fi

prj_nam=`basename $dst`

#	
SSH="sudo -u $ssh_user ssh -c blowfish"
SCP="sudo -u $ssh_user scp -c blowfish"

#	提示颜色
c_notify=$boldcyan
c_error=$boldred
