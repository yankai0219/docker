mysql -uroot -pyankai0219 chong -e "create table topics (id int(11) NOT NULL AUTO_INCREMENT, \
    title varchar(45) DEFAULT NULL, \
    body text, \
    replies_count int(11) DEFAULT 0,\
    user_id int(11) NOT NULL, \
    create_at datetime DEFAULT NULL, \
    update_at datetime DEFAULT NULL,\
    token varchar(120) DEFAULT NULL, \
    PRIMARY KEY (id) \
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8"
