mysql -uroot -pyankai0219 chong -e "create table pictures (id int(11) NOT NULL AUTO_INCREMENT, \
    name varchar(45) DEFAULT NULL, \
    imageable_id int(11) NOT NULL, \
    imageable_type varchar(20) DEFAULT NULL, \
    create_at datetime DEFAULT NULL, \
    update_at datetime DEFAULT NULL,\
    token varchar(120) DEFAULT NULL, \
    PRIMARY KEY (id) \
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8"
