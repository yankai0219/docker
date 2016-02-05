mysql -uroot -pyankai0219 chong -e "create table sitters (id int(11) NOT NULL AUTO_INCREMENT, \
    title varchar(45) DEFAULT NULL, \
    name varchar(45) NOT NULL, \
    tel varchar(11) NOT NULL, \
    gender int(11) DEFAULT NULL COMMENT '1 female 2 male', \
    address varchar(500) DEFAULT NULL, \
    createline timestamp NOT NULL DEFAULT '0000-00-00 00:00:00', \
    updateline timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',\
    PRIMARY KEY (id) \
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8"
