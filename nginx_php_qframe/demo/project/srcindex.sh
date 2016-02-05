export PATH=.:/sbin:/usr/sbin:/usr/local/sbin:/usr/local/bin:/bin:/usr/bin:/usr/local/bin
SCI_PATH=`dirname $0`
CUR_PATH=$SCI_PATH

root=$CUR_PATH/../
proot=/home/q/php/QFrame/
#静床
staticroot=/home/q/php/qstatic_sdk/
cd $root
rm -rf project/cscope.*
csfile=$root/tmp/cscope.files
touch $root/tmp/cscope.files
find $root/ -name "*.php" >  $csfile
find $root/ -name "*.html" >> $csfile
find $root/ -name "*.htm" >>  $csfile
find $root/ -name "*.sh" >>   $csfile
find $root/ -name "*.inc" >>  $csfile
find $root/ -name "*.sql" >>  $csfile
find $root/ -name "*.js" >>   $csfile
find $root/ -name "*.css" >>  $csfile
find $root/ -name "*.conf" >> $csfile
find $root/ -name "*.tpl" >>  $csfile
find $proot/ -name "*.php" >> $csfile
find $staticroot/ -name "*.php" >> $csfile
cd project
cscope -b  -i $csfile
