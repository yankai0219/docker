<?php
class ErrDef
{
    static public $errno = 0;
    static public $error;

    const VALID_DATA       = 0;

    const NICKNAME_HAS_REG = 100;
    const TEL_HAS_REG      = 101;
    const EMAIL_HAS_REG    = 102;
    const TEL_IS_WRONG     = 103;
    const A_P_IS_NOT_MATCHED = 104;
    const USER_NOT_LOGIN     = 105;


    const ENTITY_IC_CREATE_ERROR        = 200;
    const ENTITY_IC_UPDATE_ERROR        = 201;
    const IC_REPEAT_IN_ONE_MINUTE       = 202;
    const IC_EXPIRED_AFTER_LIMIT_MINUTE = 203;
    const IC_NOT_MATCHED                = 204;

    static private $_errMsg = array(
        self::NICKNAME_HAS_REG => '该昵称已经被注册',
        self::TEL_HAS_REG      => '该电话已经被注册',
        self::EMAIL_HAS_REG    => '该邮箱已经被注册',
        self::TEL_IS_WRONG     => '电话号码错误',
        self::A_P_IS_NOT_MATCHED => '用户名或密码错误',
        self::USER_NOT_LOGIN     => '用户没有登录',
    );
    static public function getErrMsg($errno)
    {
        return self::$_errMsg[$errno];
    }
}
