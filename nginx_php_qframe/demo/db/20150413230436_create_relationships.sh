mysql -uroot -pyankai0219 chong -e "create table relationships (id int(11) NOT NULL AUTO_INCREMENT, \
    follower_id int(11) NOT NULL, \
    followed_id int(11) NOT NULL, \
    create_at datetime DEFAULT NULL, \
    update_at datetime DEFAULT NULL,\
    PRIMARY KEY (id) \
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8"
