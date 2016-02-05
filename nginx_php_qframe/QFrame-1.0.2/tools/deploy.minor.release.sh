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

#   include lib
this_file=`pwd`"/"$0
this_dir=`dirname $this_file`
. $this_dir/deploy.sh


###########################################################################
#	����

if [ $# -lt 1 ] || [ "-h" = "$1" ] || [ "--help" = "$1" ]
then
	echo "�÷�: $0 FILE1 [ FILE2 ... ]";
	echo "FILE* : ��Ҫ�ϴ����ļ�/Ŀ¼��ע�⣺ÿһ���ļ������������ $PROJECT_HOME ��Ŀ¼�����·��"
	exit 0;
fi


###########################################################################
#	Lets go

#   �б𱾻��Ĳ���ϵͳ���ͣ�FreeBSD �� Linux ��Щ���ͬ
LOCAL_OS=`uname -s`

if [[ "$LOCAL_OS" == *BSD ]]
then
    test
else
    #     Linux
    test
fi

DEPLOY_DIR="$HOME/deploy"
rm -rf $DEPLOY_DIR && mkdir -p $DEPLOY_DIR

if [ -z "$ENV_BETA" ]
then
	#	release �� svn �� export trunk
	PROJECT_HOME="$DEPLOY_DIR/$prj_nam"
	cecho "=== export chunk from svn ===" $c_notify
	svn export $svn $PROJECT_HOME > /dev/null 2>&1
	svn_version=`svn --xml info $svn | grep 'revision' | head -1 | awk -F '"' '{ print $2 }'`	#   ��ȡ��ǰ SVN �İ汾"
else
	#	beta �� $PROJECT_HOME ��ȡ�ô���
	svn_version="beta";
fi

PROJECT_HOME_LEN=`echo "$PROJECT_HOME/" | wc -m | bc`

#	�������е��ļ��������˺�����"
files="";

while [ $# -ne 0 ]
do
    if [[ "$LOCAL_OS" == *BSD ]]
    then
        file=`echo "/usr/bin/find -E $PROJECT_HOME/$1 -type f -not -regex '$blacklist' | cut -c '$PROJECT_HOME_LEN-1000' | xargs echo" | sh`
    else
        #     Linux
        file=`echo "/usr/bin/find $PROJECT_HOME/$1 -regextype posix-extended -type f -not -regex '$blacklist' | cut -c '$PROJECT_HOME_LEN-1000' | xargs echo" | sh`
    fi

    files="${files}${file} "

	shift
done

#	
if [ 0 -ne `expr "$files" : ' *'` ]; then
	cecho "\nû���ҵ�Ҫ�ϴ����ļ���������������" $c_error
	exit 1;
fi

#	ȷ���ļ�
cecho "\n=== �ϴ��ļ��б� === \n" $c_notify
no=0;
for file in $files
do
    no=`echo "$no + 1" | bc`
    cecho "$no\t$file";
done
echo ""
deploy_confirm "ȷ���ļ��б�"
if [ 1 != $? ]; then
	exit 1;
fi

#	Դ�ļ����
cecho "\n=== �ļ���� === \n" $c_notify
time=`date "+%Y%m%d%H%M%S"`
src_tgz="$HOME/patch.${svn_version}.${USER}.${time}.tgz"
tar cvfz $src_tgz -C $PROJECT_HOME $files > /dev/null 2>&1
echo "$src_tgz"
if [ ! -s "$src_tgz" ]; then
    cecho "�����ļ����ʧ��" $c_error
    exit 1
fi

if [ -z "$ENV_BETA" ]
then
	hosts="$online_clusters"
else
	hosts="$beta_clusters"
fi

cecho "\n=== ��ʼ���� ===" $c_notify

#	ͬ��"
host1="";
host1_src="";

for host in ${hosts}
do
	cecho "\n=== ${host} ===\n" $c_notify

	#	��ȡ�������Ķ�Ӧ�ļ�
	online_src="/home/$ssh_user/${USER}.$prj_nam.$host.tgz"
	$SSH $host tar cvhfz $online_src -C $dst $files > /dev/null 2>&1
	$SCP $host:$online_src $online_src > /dev/null 2>&1
	local_online="$DEPLOY_DIR/online/$host"
	rm -rf $local_online && mkdir -p $local_online
	tar xz -f $online_src -C $local_online

	#	��¼��׼����
	if [ "" = "$host1_src" ]; then
		host1="$host"
		host1_src="$local_online"
	fi

	#	�Ա��ļ��� SVN �汾�����ϰ汾
	cecho "\t--- ����ļ��Ƚϲ��� ---\n" $c_notify

	for file in $files
	do
		#	ȷ���ļ����ͣ�ֻ��� text ����
        type=`file $PROJECT_HOME/$file | grep "text"`
		if [ -z "$type" ]; then
			continue
		fi

        cecho "\t$file"
		diffs=`diff -Bb $PROJECT_HOME/$file $local_online/$file`

        #   ���û�в�ͬ�Ͳ�Ҫȷ��
        if [ -z "$diffs" ]; then
            continue
        fi

		#	������׼�����İ汾һ�£����Զ��ύ
		if [ "$host" != "$host1" ]
		then
			tmp=`diff -Bb $host1_src/$file $local_online/$file`
			if [ -z "$tmp" ]; then
            	continue
            fi
		fi

        #   ���� vimdiff
        sleep 1
		vimdiff $PROJECT_HOME/$file ${local_online}/$file

    	deploy_confirm "	�޸�ȷ�� $file ?"
        if [ 1 != $? ]; then
	        exit 1;
        fi
	done

	#	�ϴ�Դ�ļ�
    dst_src_tgz=`basename $src_tgz`
	$SCP $src_tgz $host:~/$dst_src_tgz > /dev/null 2>&1

    $SSH $host "test -s ~/$dst_src_tgz"
    if [ 0 -ne $? ]; then
        cecho "\t�����ļ��ϴ�ʧ��" $c_error
        exit 1
    fi

	#	����ԭʼ�ļ�
    cecho "\n\t--- ����ԭʼ�ļ� ---\n" $c_notify
    bak_src_tgz="~$ssh_user/bak.patch.${svn_version}.${USER}.${time}.tgz"
    $SSH $host tar cvhfz ${bak_src_tgz} -C $dst $files > /dev/null 2>&1
    cecho "\t${bak_src_tgz}" 

    $SSH $host "test -s $bak_src_tgz"
    if [ 0 -ne $? ]; then
        cecho "\t����Զ������ԭʼ�ļ�����ʧ��" $c_error
        exit 1
    fi

	#	չ��Դ�ļ�
    cecho "\n\t--- �����ļ� ---\n" $c_notify
	$SSH $host "env LC_CTYPE=zh_CN.GB2312 tar xvfz ~/$dst_src_tgz -C $dst" 2>&1 | sed -e 's/^/	/'

	if [ 0 != $? ]
	then
        cecho "\t���󣺲����ļ�ʧ��" $c_error
    	deploy_confirm "	��������"
        if [ 1 != $? ]; then
	        exit 1;
        fi
	fi

	#	ʧЧEA,����build autoload map // add by cc
       $SSH $host "test -s $dst/project/autoload\_builder.sh"
       if [ 0 -eq $? ]; then
          cecho "\n\t--- run autoload_builder.sh ---\n" $c_notify
          $SSH $host "cd $dst;sh $dst/project/autoload\_builder.sh"
       fi
        
    #   �鿴�������� state ȷ�Ϸ�������

    #   ��ʾ��֤����Ч��
    verify="	--- ������ϣ�ִ�д�����ָ�ԭʼ�汾�� $SSH $host tar xvfz $bak_src_tgz -C $dst ";

	if [ "$host" = "$host1" ]
	then
	    echo ""
		deploy_confirm "$verify������֤Ч��"
		if [ 1 != $? ]; then
			exit 1;
		fi
	else
		cecho "\n$verify \n" $c_notify
	fi
done

#	��������
rm -rf $src_tgz

if [ ! -z "$DEPLOY_DIR" ]
then
	rm -rf $DEPLOY_DIR
fi

cecho "\n=== ������� ===\n" $c_notify
