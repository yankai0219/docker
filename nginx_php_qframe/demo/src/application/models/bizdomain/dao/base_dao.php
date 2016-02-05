<?php
class BaseDao
{/*{{{*/
    public function add( $obj = null )
    {/*{{{*/
        
        if ( empty( $obj ) || !is_object( $obj ) )
        {
            return false;
        }
        if ( $this->addImp( $obj ) )
        {
            return $obj;
        }
        return false;
    }/*}}}*/
    public function getById( $id = '0', $cls )
    {/*{{{*/
        if ( empty( $id ) || empty( $cls ) || !is_numeric( $id ) )
        {
            return null;
        }

        $sql = "SELECT * ";
        $sql.= "FROM ".strtolower( $cls )." ";
        $sql.= "WHERE id = ? ";
        $row = $this->getExecutor()->query( $sql, array( $id ) );
        if ( is_null( $row ) )
        {
            return null;
        }

        $obj = new $cls( $row );
        return $obj;
    }/*}}}*/
    public function updateById( $id = '0', $prop = array(), $cls )
    {/*{{{*/
        if ( empty( $id ) || empty( $cls ) || empty($prop) || !is_numeric( $id ) )
        {
            return false;
        }

        $ary = array();
        $sql = "UPDATE ".strtolower( $cls )." SET ";
        foreach ( $prop as $k => $v )
        {
            $sql.= $k." = ?, ";
            $ary[] = $v;
        }
        $sql = rtrim( $sql, ", " )." ";
        $sql.= "WHERE id = ? ";
        $ary[] = $id;

        $result = $this->getExecutor()->exeNoQuery( $sql, $ary );
        if ( !$result )
        {
            return false;
        }
        return true;
    }/*}}}*/
    public function listById( $ids = array(), $cls )
    {/*{{{*/
        if ( empty( $ids ) || empty( $cls ) || !is_array( $ids ) )
        {
            return array();
        }

        $objs = array();
        $mark = array();
        foreach ( $ids as $id )
        {
            $mark[]    = '?';
            $objs[$id] = null;
        }

        $sql = "SELECT * ";
        $sql.= "FROM ".strtolower( $cls )." ";
        $sql.= "WHERE id IN (".implode( ',', $mark ).") ";
        $rows = $this->getExecutor()->querys( $sql, $ids );
        if ( empty( $rows ) )
        {
            return $objs;
        }
        foreach ( $rows as $row )
        {
            $objs[$row['id']] = new $cls( $row );
        }

        return $objs;
    }/*}}}*/
    private function addImp( $obj )
    {/*{{{*/
        $cols = array_keys( $obj->toAry() );
        $vals = array_values( $obj->toAry() );
        $hold = array_fill( 0, count( $cols ), '?' );
        $sql = 'INSERT '.$this->getTableName( $obj ).' ';
        $sql.= '( '.implode( ", ", $cols ).' ) ';
        $sql.= 'VALUES ';
        $sql.= '( '.implode( ", ", $hold ).' ); ';
        return $this->getExecutor()->exeNoQuery( $sql, $vals );
    }/*}}}*/
    public function adds( $objs = array() )
    {/*{{{*/
        if ( empty( $objs ) || !is_array( $objs ) )
        {
            return false;
        }
        if ( $this->addsImp( $objs ) )
        {
            return $objs;
        }
        return false;
    }/*}}}*/
    private function addsImp( $objs )
    {/*{{{*/
        $cols = array_keys( $objs[0]->toAry() );
        $hold = array_fill( 0, count( $cols ), '?' );
        $vals = array();
        foreach ( $objs as $obj )
        {
            $vals = array_merge( $vals, array_values( $obj->toAry() ) );
        }
        $len = count( $objs );
        $sql = 'INSERT '.$this->getTableName( $obj ).' ';
        $sql.= '( '.implode( ", ", $cols ).' ) ';
        $sql.= 'VALUES ';
        for ( $i = 0; $i < $len; $i++ )
        {
            $sql.= '( '.implode( ", ", $hold ).' ), ';
        }
        $sql = rtrim( $sql, ', ').';';
        return $this->getExecutor()->exeNoQuery( $sql, $vals );
    }/*}}}*/
    private function getTableName( $obj )
    {/*{{{*/
        $hash_key   = $obj->hashKey();
        $table_name = strtolower( get_class( $obj ) );
        if ( '' !== $hash_key )
        {
            $table_name.= '_'.$hash_key;
        }
        return $table_name;
    }/*}}}*/
    protected function getExecutor()
    {/*{{{*/
        return LoaderSvc::loadExecutor();
    }/*}}}*/
    public function getOne( $cls, $prop )
    {/*{{{*/
        if( empty( $prop ) )
        {
            return null;
        } 

        foreach( $prop as $k => $v )
        {
            $fields[] = " `".$k."` = ? ";
            $values[] = $v;
        }

        $sql = "SELECT * FROM "; 
        $sql.= strtolower( $cls )." ";
        $sql.= "WHERE ";
        $sql.= implode( " AND ", $fields );
        $row = $this->getExecutor()->query( $sql, $values );

        if( is_null( $row ) )
        {
            return null;
        } 

        return $row;
    }/*}}}*/
    public function getLastInsertId()
    {
        return $this->getExecutor()->getLastInsertID();
    }
	public function getAll( $cls )
    {/*{{{*/
        $sql = "SELECT * FROM "; 
        $sql.= strtolower( $cls );

        $row = $this->getExecutor()->querys( $sql );
        if( is_null( $row ) )
        {
            return null;
        } 
        return $row;
    }/*}}}*/
    public function delById( $id = '0', $cls )
    {/*{{{*/
        if( empty( $id ) || empty( $cls ) || !is_numeric( $id ) )
        {
            return false;
        }

        $ary = array();
        $sql = 'DELETE FROM '.strtolower( $cls ).' ';
        $sql.= 'WHERE id = ? ';
        $ary[] = $id;

        $result = $this->getExecutor()->exeNoQuery( $sql, $ary );
        if( !$result )
        {
            return false;
        }
        return true;
    }/*}}}*/

    public function getList( $cls, $fields = array(), $order = '', $bgn = 0, $cnt = 0 )
    {/*{{{*/
        $condInf = $this->sqlCond($fields);
        $cond    = $condInf['cond'];
        $values  = $condInf['values'];

        $sql = "SELECT * FROM "; 
        $sql.= strtolower( $cls )." ";
        //WHERE
        if ( !empty( $cond ) )                                      
        {                                                          
            $sql.= " WHERE ";                                      
            $sql.= implode( " AND ", $cond );                      
        }                                                          
        //ORDER                                                    
        if ( !empty( $order ) )                                     
        {                                                          
            $sql.= " ORDER BY ".$order." ";                        
        }                                                          
        //LIMIT                                                    
        if ( $cnt != 0 )                                            
        {                                                          
            $sql.= " LIMIT ".$bgn.",".$cnt." ";                    
        }                                                          

        $rows = $this->getExecutor()->querys( $sql, $values );     

        if ( is_null( $rows ) )                                     
        {                                                          
            return null;                                           
        }                                                          

        return $rows;                                              
    }/*}}}*/                                                       

    public function getCount( $cls, $fields = array() )
    {/*{{{*/
        $condInf = $this->sqlCond($fields);
        $cond    = $condInf['cond'];
        $values  = $condInf['values'];

        $sql = "SELECT COUNT(*) AS total FROM "; 
        $sql.= strtolower( $cls )." ";
        //WHERE
        if ( !empty( $cond ) )                                      
        {                                                          
            $sql.= " WHERE ";                                      
            $sql.= implode( " AND ", $cond );                      
        }                                                          

        $row = $this->getExecutor()->query( $sql, $values );     

        if ( is_null( $row ) )                                     
        {                                                          
            return 0;                                           
        }                                                          

        return $row['total'];                                              
    }/*}}}*/                                                       
    protected function basicSqlCond($fields)
    {
        $cond   = array();
        $values = array();
        foreach ($fields as $field => $value)
        {
            if (in_array($field, $this->_limitFields))
            {
                $cond[] = $field . '= ?';
                $values[] = $value;
            }
        }
        return array($cond, $values);
    }
}/*}}}*/
