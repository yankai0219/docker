#!	/usr/local/bin/bash

#   include lib
this_file=`pwd`"/"$0
this_dir=`dirname $this_file`
source $this_dir/branch_conf.sh
source $this_dir/branch_lib.sh

# 检查BRANCH
if [ ! -n "$1" ]
then
    cecho "==== 分支名称未填写 ====" $c_error
    cecho "=== === 退出 === ===" $c_notify
    exit
fi
BRANCH_NAME=$1

# 检查分支目录是否存在
if [ ! -d "${HOME}/devspace/${PROJECT_HOME}_${BRANCH_NAME}" ]
then
    cecho "==== 分支${BRANCH_NAME}的工作目录不存在 ====" $c_error
    cecho "=== === 退出 === ===" $c_notify
    exit
fi

cecho "=== === 切换到分支${BRANCH_NAME} === ===" $c_notify
unlink "${HOME}/devspace/${PROJECT_LN}"
ln -s "${HOME}/devspace/${PROJECT_HOME}_${BRANCH_NAME}" "${HOME}/devspace/${PROJECT_LN}"
cecho "=== === 完毕 === ===" $c_notify

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
