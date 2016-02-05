#!	/usr/local/bin/bash

#   include lib
this_file=`pwd`"/"$0
this_dir=`dirname $this_file`
source $this_dir/branch_conf.sh
source $this_dir/branch_lib.sh

if [ ! -n "$1" ]
then
    cecho "==== 主干工作目录未填写 ====" $c_error
    cecho "=== === 退出 === ===" $c_notify
    exit
fi

# 获取主干工作目录
TRUNK_WORKDIR="${PROJECT_HOME}_trunk"
if [ ! -d "${HOME}/devspace/${TRUNK_WORKDIR}" ]
then
    cecho "==== 主干的工作目录未找到 ====" $c_error
    cecho "=== === 退出 === ===" $c_notify
fi

# 确保分支版本最新
svn up

# 获取生成版本号,最新版本号以及分支目录
BRANCH_PATH=`svn info | sed -n '2p' | awk '{print $2}'`
CREATE_VER=`svn log --stop-on-copy | awk '/^r[0-9]+/{bgn=$1} END{print bgn}' | cut -d "r" -f2`
LAST_VER=`svn info | sed -n '5p' | awk '{print $2}'`

cd ${HOME}"/devspace/"${TRUNK_WORKDIR}
cecho "=== === 需要改变的文件列表： === ===" $c_notify
svn merge --dry-run -r${CREATE_VER}:${LAST_VER} ${BRANCH_PATH}

deploy_confirm "是否需要执行合并"
if [ 1 != $? ]
then
    cecho "=== === 中止合并 === ===" $c_notify
    exit
fi

cecho "=== === 执行合并 === ===" $c_notify
svn merge -r${CREATE_VER}:${LAST_VER} ${BRANCH_PATH}

cecho "=== === 切换至主目录 === ===" $c_notify
cd ../
unlink "${HOME}/devspace/${PROJECT_LN}"
ln -s "${HOME}/devspace/${TRUNK_WORKDIR}" "${HOME}/devspace/${PROJECT_LN}"

cecho "=== === 合并完成 === ===" $c_notify
