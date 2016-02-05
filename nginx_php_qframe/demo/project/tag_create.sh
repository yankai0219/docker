#!	/usr/local/bin/bash

this_file=`pwd`"/"$0
this_dir=`dirname $this_file`
source $this_dir/branch_conf.sh
source $this_dir/branch_lib.sh

## 设置擦除符
stty erase "^H"

cecho "最近10次TAG提交记录:" $c_notify
#svn list -v $TAG_ROOT | sort -n -r | grep -v '\./' | awk '{print "User:"$2"\t""Ver:"substr($6,9,length($6)-9)"\t""Time:"$3"-"$4, $5 }' | head -n 10
#cecho "" $c_notify
curl -s -H "Host:videores.xen214.net" -d "limit=10&order=createtime%20DESC&project=hao360cn_video_engine"  'http://10.16.15.148/svntag/getListInterface'

## 获得tag的版本号，需要判断版本号是否被占用
cread -p "输入tag版本号: " TAG_VERSION_CODE $c_notify
## todo检查版本号
CUR_TAGS=`svn list $TAG_ROOT`
for _tag in $CUR_TAGS
do
    if [ "release_"$TAG_VERSION_CODE"/" = $_tag ]
    then
        cecho "tag版本${TAG_VERSION_CODE}已存在" $c_error
        exit
    fi
done

## 获得更新说明
cread -p "输入本次更新说明: " TAG_REASON $c_notify
## todo检查更新说明
if [ -z $TAG_REASON ]
then
    cecho "未填写更新说明" $c_error
    exit
fi

## 询问逻辑, 确定从主干的哪个版本生成tag
cecho "更新代码" $c_notify
svn up
TRUNK_RECENT_VERSION=`svn info | sed -n '5p' | awk '{print $2}'`
deploy_confirm "要使用当前trunk版本来打tag吗？"
if [ 0 -eq $? ]
then
    cread -p "输入需要打tag的trunk版本号: " TRUNK_VERSION_CODE $c_notify
    ## 这里需要比对版本是否合法
    is_numeric $TRUNK_VERSION_CODE
    if [ 0 -eq $? ]
    then
        cecho "trunk版本号必须是整型的" $c_error
        exit
    fi

    if [ $TRUNK_VERSION_CODE -gt $TRUNK_RECENT_VERSION ]
    then
        cecho "trunk版本号错误" $c_error
        exit
    fi
else
    TRUNK_VERSION_CODE=$TRUNK_RECENT_VERSION
fi

###################################
echo ""
cecho "创建Tag信息如下:" $boldgreen
cecho "项目: "${PROJECT_LN} $boldwhite
cecho "更新信息: "${TAG_REASON} $boldwhite
cecho "负责人: "${USER} $boldwhite
cecho "TAG版本: "${TAG_VERSION_CODE} $boldwhite
cecho "对应trunk版本: "${TRUNK_VERSION_CODE} $boldwhite
echo ""
deploy_confirm "确认是否要创建Tag?"
if [ 1 != $? ]
then
    cecho "=== === 中止创建 === ===" $c_notify
    exit
else
    saveTagWiki=`curl -s -H "Host:videores.xen214.net" -d "project=${PROJECT_LN}&version=${TAG_VERSION_CODE}&remark=${TAG_REASON}&operator=${USER}"  http://10.16.15.148/svntag/add`
    saveTagWikiRs=${saveTagWiki:1:4}
    if [ "$saveTagWikiRs" == "succ" ] 
    then
        cecho "tag墙添加成功" $boldgreen
    else
        cecho "tag墙添加失败, 请到后台手动添加" $c_error
    fi
fi

cecho "正在生成tag" $c_notify
MAKE_TAG_PATH=$TAG_ROOT"/release_"$TAG_VERSION_CODE
svn cp -r $TRUNK_VERSION_CODE $TRUNK_ROOT $MAKE_TAG_PATH -m "${USER}
${TAG_VERSION_CODE}"

cecho "创建完毕, SVN: ${MAKE_TAG_PATH}" $c_notify
