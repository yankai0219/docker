<?php
/**
 * Qihoo PHP FrameWork 
 * @ http://add.corp.qihoo.net:8360/pages/viewpage.action?pageId=1675831
 */
class QFrameDomainUser
{
    var $_login_url     = 'https://login.ops.qihoo.net:4430/sec/login';
    var $_bak_login_url = 'https://tool4.ops.dxt.qihoo.net:4430/sec/login';
    var $_timeout       = 86400; 
    var $_tcookie;
    var $_qcookie;
    var $_salt          = 'qIHoDomsy';
    var $_defaultDomain = '.qihoo.net';
    var $_allowDomain   = array('.qihoo.net','.360.cn');
    var $_qcInfo        = array();
    var $_tcInfo        = array();

    public function __construct()
    {/*{{{*/
       $this->_tcookie = isset($_COOKIE['TD']) ? $_COOKIE['TD'] : ''; 
       $this->_qcookie = isset($_COOKIE['QD']) ? $_COOKIE['QD'] : '';
    }/*}}}*/

    public function getInstance()
    {/*{{{*/
        static $instance;
        if('' == $instance)
        {
            return new QFrameDomainUser();
        }
        return $instance;
    }/*}}}*/

    public function checkQUCookie($destUrl='')
    {/*{{{*/
        $userInfo = null;
        if ( (false == $this->_isExistQC()) ||  (false == $this->_isValidLoginTime())
        || (false == $this->_isValidQC($userInfo)) )
        {
            //判断域sid是否有效
            $sid = $this->_getSid();
            if( !empty($sid) && ($userInfo=$this->_getUserInfoBySid($sid)))
            {
                //设置cookie
                $this->_setCookie($userInfo);
            }else
            {
            	$this->_sendUserToQUC($destUrl);
            }
        }
        return $userInfo;
    }/*}}}*/

    public function logout()
    {/*{{{*/
        setcookie('TD' , '' , time()-$this->_timeout , '/' , $this->_defaultDomain);
        setcookie('QD' , '' , time()-$this->_timeout , '/' , $this->_defaultDomain);
        return true;
    }/*}}}*/

    private function _isExistQC()
    {/*{{{*/
        if ( ('' == $this->_tcookie) || ('' == $this->_qcookie) )
        {
            return false;
        }
        return true;
    }/*}}}*/

    private function _isValidLoginTime()
    {/*{{{*/
        $this->_getTCInfo();
        if (1 == (int)$this->_tcInfo['keepAlive'])
        {
            return true;
        }
        $term = (float)(time() - ($this->_tcInfo['loginTime']));
        return ($term<= $this->_timeout);
    }/*}}}*/

    private function _getQCInfo()
    {/*{{{*/
        $arr = array();
        parse_str($this->_qcookie, $arr);
        $arr['u'] = isset($arr['u'])?$arr['u']:'';
        $arr['m'] = isset($arr['m'])?$arr['m']:'';
        $this->_qcInfo['userMail'] = $this->_decrypt($arr['m']);
        $this->_qcInfo['userName'] = $this->_decrypt($arr['u']);
    }/*}}}*/

    private function _getTCInfo()
    {/*{{{*/
        $arr = array();
        parse_str($this->_tcookie, $arr);
        $this->_tcInfo['loginTime'] = isset($arr['t'])?$arr['t']:'';
        $this->_tcInfo['signature'] = isset($arr['s'])?$arr['s']:'';
        $this->_tcInfo['keepAlive'] = isset($arr['a'])?$arr['a']:'';
    }/*}}}*/

    public function _isValidQC(&$userInfo)
    {/*{{{*/
        if ($this->_vSign())
        {
            $userInfo = $this->_fillUserInfo();
            return (false == empty($userInfo));
        }
        return false;
    }/*}}}*/
    
    private function _vSign()
    {/*{{{*/
        $this->_getQCInfo();
        return $this->_verify($this->_qcInfo['userName'].$this->_qcInfo['userMail'].$this->_tcInfo['loginTime'], $this->_tcInfo['signature']);
    }/*}}}*/
    
    public function _createDestUrl($destUrl)
    {/*{{{*/
    	$destUrl = trim($destUrl);
        if ('' == trim($destUrl))
        {
            $server = getenv('SERVER_NAME');
            $uri    = getenv('REQUEST_URI');

            /*
             * 如果HTTP请求，是非80端口的，则在跳转时，加上端口号
             */
            $port   = '';
            if ( '80' != getenv('SERVER_PORT') )
            {
                $port = ':'.getenv('SERVER_PORT');
            }

            $destUrl = 'http://'.$server.$port.$uri;
        }
        return urlencode($destUrl);
    }/*}}}*/

    private function _fillUserInfo()
    {/*{{{*/
        $userInfo['userMail']  = $this->_qcInfo['userMail'];
        $userInfo['userName']  = $this->_qcInfo['userName'];
        $userInfo['loginTime'] = $this->_tcInfo['loginTime'];
        return $userInfo;
    }/*}}}*/

    private function _decrypt($data)
    {/*{{{*/
        return str_rot13($data);
    }/*}}}*/

    private function _verify($data, $signature)
    {/*{{{*/
        $newStr = md5($data.$this->_salt);
        return ($newStr == $signature);
    }/*}}}*/

    private function _getSid()
    {/*{{{*/
        $sid = $_GET['sid']; 
        if(!$sid) return false;
        return $sid;
    }/*}}}*/
    
    public function _sendUserToQUC($destUrl='')
    {/*{{{*/
        $destUrl    = $this->_createDestUrl($destUrl);
        $url        = $this->_login_url.'?ref='.$destUrl;
        header('Location: '.$url);
        exit;
    }/*}}}*/

    private function _getUserInfoBySid($sid)
    {/*{{{*/
        $userInfo   = array();
        $url        = $this->_login_url."?sid=".$sid; 
        $ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    	$result = curl_exec($ch);
    	curl_close($ch);
        if(!empty($result) && 'None' !== $result)
        {
            $decodeArray = json_decode($result, true);
            $userInfo['userMail'] = $decodeArray['mail'];
            $userInfo['userName'] = $decodeArray['user'];
        }
        return $userInfo;
    }/*}}}*/

    private function _checkLoginUrlValid()
    {/*{{{*/
        $url = $this->_login_url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$contents = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($http_code != 200 && $http_code != 302)
		{
			$url = $this->_bak_login_url;
		}
		return  $url ;
    }/*}}}*/
    
    private function _setCookie($userInfo)
    {/*{{{*/
 		$td = $this->_getTd($userInfo);
 		$qd = $this->_getQd($userInfo);
        $domain = $this->_createDomain();
 		setcookie('TD' , $td , time()+$this->_timeout , '/' , $domain);
 		setcookie('QD' , $qd , time()+$this->_timeout , '/' , $domain);
 		$this->_tcookie = $td;
 		$this->_qcookie = $qd;
 		return true;
 	}/*}}}*/

    private function _createDomain()
    {/*{{{*/
        $domain  = $this->_defaultDomain;
        $httparr = parse_url($_SERVER['HTTP_HOST']);
        if (!array_key_exists('scheme', $httparr)) 
        {
            $httparr = parse_url("http://".$_SERVER['HTTP_HOST']);
        }
        $host    = $httparr['host'];
        $hostarr = explode('.',$host);
        $count   = count($hostarr);
        if(in_array(".".$hostarr[$count-2].".".$hostarr[$count-1],$this->_allowDomain))
        {
            $domain = ".".$hostarr[$count-2].".".$hostarr[$count-1];
        }
        return $domain;
    }/*}}}*/

    private function _getTd($userInfo)
    {/*{{{*/
 		$loginTime = time();
 		$signData = array_merge($userInfo,array('loginTime'=>$loginTime));
 		return 't='.$loginTime.'&s='.$this->_getSign($signData).'&a=1';
 	}/*}}}*/

    private function _getQd($userInfo)
    {/*{{{*/
 		return 'u='.$this->_decrypt($userInfo['userName']).'&m='.$this->_decrypt($userInfo['userMail']);
 	}/*}}}*/

    private function _getSign($data)
    {/*{{{*/
 		return md5($data['userName'].$data['userMail'].$data['loginTime'].$this->_salt);
 	}/*}}}*/

}
