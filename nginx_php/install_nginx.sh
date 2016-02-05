
cur_dir=/tmp/source_code
if [ ! -e $cur_dir ]; then
    mkdir -p $cur_dir 
fi

cd $cur_dir
wget -c http://soft.vpser.net/web/nginx/nginx-1.6.0.tar.gz
tar zxf nginx-1.6.0.tar.gz
cd nginx-1.6.0/
./configure --user=www --group=www --prefix=/usr/local/nginx
make && make install

ln -s /usr/local/nginx/sbin/nginx /usr/bin/nginx

rm -f /usr/local/nginx/conf/nginx.conf
cd $cur_dir
cp conf/nginx.conf /usr/local/nginx/conf/nginx.conf

mkdir -p /home/wwwroot/default
chmod +w /home/wwwroot/default
mkdir -p /home/wwwlogs
chmod 777 /home/wwwlogs
chown -R www:www /home/wwwroot/default
