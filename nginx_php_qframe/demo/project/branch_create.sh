#!	/usr/local/bin/bash

#   include lib
this_file=`pwd`"/"$0
this_dir=`dirname $this_file`

source $this_dir/branch_conf.sh
source $this_dir/branch_lib.sh

# 获取SVN路径
TRUNK_PATH=$TRUNK_ROOT

# 检查TRUNK
if [[ $TRUNK_PATH != */trunk* ]]
then
    cecho "==== 当前工作目录  不是工程的主干项目 ====" $c_error
    cecho "==== 创建分支需要在主干的工作目录内创建 ====" $c_error
    cecho "=== === 退出 === ===" $c_notify
    exit
fi    

# 检查BRANCH
if [ ! -n "$1" ]
then
    cecho "==== 分支名称未填写 ====" $c_error
    cecho "=== === 退出 === ===" $c_notify
    exit
fi
BRANCH_EXIST=0
BRANCH_NAME=$1
CUR_BRANCHES=`svn list $SVN_ROOT"/branches"`

deploy_confirm "确认要创建分支${BRANCH_NAME}？"
if [ 0 -eq $? ]
then
    cecho "=== === 中止创建分支 === ===" $c_notify
    cecho "=== === 退出 === ===" $c_notify
    exit
fi

for _branch in $CUR_BRANCHES
do
    if [ $BRANCH_NAME"/" = $_branch ]
    then
        deploy_confirm "分支${BRANCH_NAME}已存在，是否需要创建到本地工作目录？"
        if [ 0 -eq $? ]
        then
            cecho "=== === 中止创建分支 === ===" $c_notify
            cecho "=== === 退出 === ===" $c_notify
            exit
        else
            BRANCH_EXIST=1
        fi
    fi
done

BRANCH_PATH=$SVN_ROOT"/branches/"$1

# 建立分支
if [ $BRANCH_EXIST -eq 0 ]
then
    cecho "=== === 开始创建分支 === ===" $c_notify
    svn cp ${TRUNK_PATH} ${BRANCH_PATH}
    cecho "=== === 分支建立完成 === ===" $c_notify
fi
# checkout
BRANCH_WORKDIR=${HOME}/devspace/${PROJECT_HOME}_${BRANCH_NAME}
if [ ! -d ${BRANCH_WORKDIR} ]
then
    cecho "=== === 创建本地工作目录 === ===" $c_notify
    mkdir -p ${HOME}"/devspace"
    svn co ${BRANCH_PATH} ${BRANCH_WORKDIR} 
    cecho "=== === 本地checkout完成 === ===" $c_notify
else
    cecho "=== === 本地工作目录已存在 === ===" $c_notify
    cecho "=== === ${BRANCH_WORKDIR} === ===" $c_notify
fi
# 创建软链接
cecho "=== === 切换本地工作目录到分支 === ===" $c_notify
unlink "${HOME}/devspace/${PROJECT_LN}"
ln -s "${BRANCH_WORKDIR}" "${HOME}/devspace/${PROJECT_LN}"
cecho "=== === 分支建立完成 === ===" $c_notify

cd ..
cd -
# 建立配置文件
cecho "=== === 建立配置文件 === ===" $c_notify

for dir in `ls config`
do
    cd "$PROJECT_PATH/config/$dir/"
    if [ -e "server/server_conf.$USER.php" ]
    then
        rm -rf server_conf.php
        ln -s "server/server_conf.$USER.php" server_conf.php
    else
        cecho "=== === 配置文件  config/$dir/server/server_conf.$USER.php 不存在 === ===" $c_notify
    fi
done

cecho "=== === 配置文件建立完成 === ===" $c_notify
exit
