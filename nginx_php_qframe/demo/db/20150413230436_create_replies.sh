mysql -uroot -pyankai0219 chong -e "create table replies (id int(11) NOT NULL AUTO_INCREMENT, \
    body text, \
    user_id int(11) NOT NULL, \
    topic_id int(11) NOT NULL, \
    create_at datetime DEFAULT NULL, \
    update_at datetime DEFAULT NULL,\
    token varchar(120) DEFAULT NULL, \
    PRIMARY KEY (id) \
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8"
