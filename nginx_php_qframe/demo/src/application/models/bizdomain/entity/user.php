<?php
class User extends BaseEntity
{
    public $cls = __CLASS__;
	public $fields = array(
        'name',
        'nickname',
        'password',
        'gender',
        'opentype',
        'openid',
        'open_access_token',
        'tel',
        'type',
        'email',
        'address',
        'createline',
        'updateline',
        'other',
	);
}
