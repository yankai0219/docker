<?php
class LoaderSvc
{/*{{{*/
	private static $_db = null;
	private static $_cache = null;
	private static $_mongoConfs = null;

    public static function init( $db = null, $log_path = null , $log_bizname = null, $log_opt = null)
    {/*{{{*/
        self::setExecutorConf( $db );
        LogSvc::init( $log_path , $log_bizname, $log_opt);
    }/*}}}*/

    public static function setExecutorConf( $conf )
    {/*{{{*/
        if ( !is_array( $conf ) )
        {
            return;
        }
        self::$_db = $conf;
    }/*}}}*/

    public static function loadExecutor()
    {/*{{{*/
        $db  = self::$_db;
        $key = 'SQLExecutor_'.$db['port'].$db['user'].$db['name'];
        $obj = ObjectFinder::find( $key );

        if ( is_object( $obj ) )
        {
            return $obj;
        }

        if ( is_null( self::$_db ) )
        {
            return null;
        }
        $obj = VUtilsExecutor::loadSqlExecutor(self::$_db);
        //    new SQLExecutor($db['host'], $db['user'], $db['pass'], $db['name'], $db['port']);
        ObjectFinder::register( $key, $obj );
        return $obj;
    }/*}}}*/

    public static function destoryExecutor()
    {/*{{{*/
        $db  = self::$_db;
        $key = 'SQLExecutor_'.$db['host'].$db['port'].$db['user'].$db['name'];
        ObjectFinder::destory( $key );
        ObjectFinder::destory( 'IDGenter' );
    }/*}}}*/

    public static function loadIdGenter()
    {/*{{{*/
        $obj = ObjectFinder::find( 'IDGenter' );
        if ( is_object( $obj ) )
        {
            return $obj;
        }

        $obj = new IDGenter( self::loadExecutor() );
        ObjectFinder::register( 'IDGenter', $obj );
        return $obj;
    }/*}}}*/

    public static function setCacheConf( $conf )
    {/*{{{*/
        if ( !is_array( $conf ) )
        {
            return;
        }
        self::$_cache = $conf;
    }/*}}}*/

    public static function loadCache()
    {/*{{{*/
        $obj = ObjectFinder::find( 'MemCacheDriver' );
        if ( is_object( $obj ) )
        {
            return $obj;
        }

        if ( !is_array( self::$_cache ) )
        {
            return null;
        }

        if ( class_exists( 'Memcached' ) )
        {
            $obj = new MemCachedDriver( self::$_cache );
        }
        else
        {
            $obj = new MemCacheDriver( self::$_cache );
        }
        ObjectFinder::register( 'MemCacheDriver', $obj );
        return $obj;
    }/*}}}*/

    public static function loadDao( $entity )
    {/*{{{*/
        $cls = $entity.'Dao';
        $dao = ObjectFinder::find( $cls );
        if ( is_object( $dao ) )
        {
            return $dao;
        }

        $dao = new $cls();
        ObjectFinder::register( $cls, $dao );
        return $dao;
    }/*}}}*/
    public static function loadMarker()
    {/*{{{*/
        $obj = ObjectFinder::find( 'Marker' );
        if ( is_object( $obj ) )
        {
            return $obj;
        }
        $obj = new Marker( self::loadExecutor() );
        ObjectFinder::register( 'Marker', $obj );
        return $obj;
    }/*}}}*/
    public static function destoryMarker()
    {/*{{{*/
        ObjectFinder::destory( 'Marker' );
    }/*}}}*/
    public static function destoryCache()
    {/*{{{*/
        ObjectFinder::destory( 'MemCacheDriver' );
    }/*}}}*/
    
	public static function setMongoConf( $conf_key='MONGO_CONF' )
    {/*{{{*/
        $conf = QFrameConfig::getConfig($conf_key);
        self::$_mongoConfs[$conf_key] = $conf;
    }/*}}}*/
    public static function loadMongo( $conf_key='MONGO_CONF' )
    {/*{{{*/
        $key = 'MongoDriver_'.$conf_key;
        $obj = ObjectFinder::find( $key );
        if ( is_object( $obj ) )
        {
            return $obj;
        }

        if ( !is_array( self::$_mongoConfs[$conf_key] ) )
        {
            self::setMongoConf($conf_key);
        }
		$mongo = self::$_mongoConfs[$conf_key];
		$options = array();
		
		if($mongo['user'])
		{
			$options['username'] = $mongo['user'];
		}
		if($mongo['pass'])
		{
			$options['password'] = $mongo['pass'];
		}
		if($mongo['timeout'])
		{
			$options['timeout'] = $mongo['timeout'];
		}
		if(isset($mongo['persistent']))
		{
			$options['persistent'] = $mongo['persistent'];
		}

		$obj = new MongoSdk( $mongo['appname'], $mongo['table'], $options );
        ObjectFinder::register( $key, $obj );
        return $obj;
    }/*}}}*/
	public static function destoryMongo( $conf_key='MONGO_CONF')
	{/*{{{*/
        $key = 'MongoDriver_'.$conf_key;
        ObjectFinder::destory( $key );
	}/*}}}*/
}/*}}}*/
?>
