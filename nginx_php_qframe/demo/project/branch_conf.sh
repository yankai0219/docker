#!	/usr/local/bin/bash
PROJECT_LN="hao360cn_project"
PROJECT_PATH="${HOME}/devspace/${PROJECT_LN}"


SVN_ROOT=`svn info | sed -n '3p' | awk '{print $3}'` 
TAG_ROOT=$SVN_ROOT"/tags"
TRUNK_ROOT=$SVN_ROOT"/trunk"
#PROJECT_HOME=`echo "$SVN_ROOT" | awk -F/ '{print $NF}'`
PROJECT_HOME=$PROJECT_LN
