mysql -uroot -pyankai0219 chong -e "alter table replies MODIFY column create_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00';"
mysql -uroot -pyankai0219 chong -e "alter table replies MODIFY column update_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;"
