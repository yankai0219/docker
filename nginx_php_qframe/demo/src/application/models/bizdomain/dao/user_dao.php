<?php
class UserDao extends BaseDao
{
    protected $_limitFields = array(
        'name',
        'nickname',
        'email',
        'password',
        'opentype',
        'openid',
        'open_access_token',
        'tel',
    
    );
    public function sqlCond($fields)
    {
        list($cond, $values) = $this->basicSqlCond($fields);
        return array(
            'cond'   => $cond,
            'values' => $values,
            );
    }
}

