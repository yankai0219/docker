<?php
class UserSvc
{
    static private $ENTITY = 'User';
    static public function reg($nickName, $tel, $password, $email = '')
    {
        $userInfo = array(
            'nickname' => $nickName,
            'tel'      => $tel,
            'password' => sha1($password),
            'email'    => $email,
        );
        $userEntity = new self::$ENTITY($userInfo);
        $oneUser = $userEntity->doCreate();
        $user = $oneUser->toAry();
        $user['id'] = $userEntity->getLastInsertId();
        return $user;
    }
    static public function oauthReg($opentype, $openid)
    {
        $userInfo = array(
            'opentype' => $opentype,
            'openid'   => $openid,
            );
        $oneUser = self::getOneUserByOpen($opentype, $openid);
        if (! $oneUser)
        {
            $userEntity = new self::$ENTITY($userInfo);
            $oneUser = $userEntity->doCreate();
            return $oneUser->toAry();
        }
        else
        {
            return $oneUser;
        }
    }
    static public function getOneUserByOpen($opentype, $openid)
    {
        $prop = array(
            'opentype' => $opentype,
            'openid'   => $openid,
            );
        return LoaderSvc::loadDao(self::$ENTITY)->getOne(self::$ENTITY, $prop);
    }
    static public function updateUserInfoBasedQQ($userId, $qqinfo)
    {
        $prop = array(
            'nickname' => $qqinfo['nickname'],
            'gender'   => ($qqinfo['gender'] == '男') ? OAuth::MALE : OAuth::FEMALE,
            'addr_province' => $qqinfo['province'],
            'addr_detail'   => $qqinfo['city'],
        );
        return self::updateUserInfoById($userId, $prop);
    }
    static public function updateUserInfoById($id, $prop)
    {
        return LoaderSvc::loadDao(self::$ENTITY)->updateById($id, $prop, self::$ENTITY);
    }
    static public function login($loginName, $password)
    {
        $oneUser = self::_getOneUser($loginName, $password);
        return ($oneUser) ? true : false;
    }
    static public function getCntAllUser()
    {
        return LoaderSvc::loadDao(self::$ENTITY)->getCount(self::$ENTITY);
    }
    static public function getOneUserById($id)
    {
        $prop = array(
            'id' => $id,
            );
        return LoaderSvc::loadDao(self::$ENTITY)->getOne(self::$ENTITY, $prop);
    }
    static public function getOneUserByProp($prop)
    {
        return LoaderSvc::loadDao(self::$ENTITY)->getOne(self::$ENTITY, $prop);
    }
    static public function getOneUser($loginName, $password)
    {
        return self::_getOneUser($loginName, $password);
    }
    static private function _getOneUser($loginName, $password)
    {
        $telMatchNum = preg_match('#^[0-9]{11}$#', $loginName);
        $isEmail = (strpos($loginName, '@') !== false) ? true : false;
        if ($telMatchNum)
        {
            $prop = array(
                'tel'      => $loginName,
                'password' => sha1($password),
                );
        }
        else if ($isEmail) 
        {
            $prop = array(
                'email'    => $loginName,
                'password' => sha1($password),
                );
        }
        else 
        {
            $prop = array(
                'nickname' => $loginName,
                'password' => sha1($password),
                );
        }
        return LoaderSvc::loadDao(self::$ENTITY)->getOne(self::$ENTITY, $prop);
    }
}
