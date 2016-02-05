#!/bin/bash

################################################################
#
# Qihoo project source deploy tool
# @Wiki: http://add.corp.qihoo.net:8360/display/platform/deploy_tools
#
###############################################################

#  根据term的编码格式进行设置
export LANGUAGE="utf-8"
#export LANGUAGE="gbk"

#  项目名
PROJECT_NAME="QFrame"

#  项目版本号
VERSION="1.0.2"

#  项目svn地址
SVN_URL="https://ipt.src.corp.qihoo.net/svn/fw/trunk/"

#  线上集群
testCluster="w62v.add.cct.qihoo.net 192.168.0.172 192.168.100.157"
leakCluster="w3v.ipt.bjt.qihoo.net w4v.ipt.dxt.qihoo.net w3v.ipt.dxt.qihoo.net w4v.ipt.bjt.qihoo.net leak1.safe.bjt.qihoo.net"
onlineTmp="jy3.ipt.lfc.qihoo.net jy2.ipt.lft.qihoo.net"
online_cluster="$onlineTmp $testCluster $leakCluster"

#  测试集群
beta_cluster="test2v.add.dxt"

#  部署帐号
SSH_USER="sync360"
#SSH_USER="search"

#  目标机的部署目录
REMOTE_DEPLOY_DIR="/home/q/php/$PROJECT_NAME"
#  目标机上真实的目录,一般不用更改
REAL_REMOTE_DEPLOY_DIR="/home/q/php/$PROJECT_NAME-$VERSION"

#  deploy-release.sh 使用，自动执行 $AUTORUN_RELEASE_CMD , 主要用于autoload.sh, 不需要可直接注释
#AUTORUN_RELEASE_CMD="cd $REMOTE_DEPLOY_DIR;sh project/autoload_builder.sh"

#  deploy-package.sh 使用，自动执行 $REMOTE_DEPLOY_DIR/bootstrap.sh , 脚本需要执行权限,不需要可直接注释
#SUDO_AUTORUN_PACKAGE="bootstrap.sh"
#AUTORUN_PACKAGE="bootstrap.sh"
AUTORUN_PACKAGE_CMD="cd $REMOTE_DEPLOY_DIR;sh project/autoload_builder.sh;"


#  用于diff命令  打包时过滤logs目录
DEPLOY_BASENAME=`basename $REMOTE_DEPLOY_DIR`
#  用于diff命令， 获取线上代码时，有时候需要过滤掉日志等文件
TAR_EXCLUDE="--exclude $DEPLOY_BASENAME/logs --exclude $DEPLOY_BASENAME/src/www/thumb"

#  deploy debug 标志，1 表示打开调试信息
UTILS_DEBUG=0

##################################################################################################

SSH="sudo -u $SSH_USER ssh"
SCP="sudo -u $SSH_USER scp"

# 保存本地临时文件的目录
LOCAL_TMP_DIR="/tmp/deploy_tools/$USER"

# 上传代码时过滤这些文件
BLACKLIST='(.*\.tmp$)|(.*\.log$)|(.*\.svn.*)'

# 线上保存临时文件的目录
ONLINE_TMP_DIR="/tmp"

# 备份代码的目录
ONLINE_BACKUP_DIR="/home/$SSH_USER/deploy_history/$PROJECT_NAME"          
LOCAL_DEPLOY_HISTORY_DIR="/home/$USER/deploy_history/$PROJECT_NAME"  

# 代码更新历史(本地文件）
DEPLOY_HISTORY_FILE="$LOCAL_DEPLOY_HISTORY_DIR/deploy_history"            
DEPLOY_HISTORY_FILE_BAK="$LOCAL_DEPLOY_HISTORY_DIR/deploy_history.bak" 
