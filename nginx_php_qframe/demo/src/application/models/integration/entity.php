<?php
class Entity extends SimpleObject
{/*{{{*/
    public function __construct( $attr = array() )
    {/*{{{*/
        parent::__construct( $attr );
    }/*}}}*/

    public function hashKey()
    {/*{{{*/
        return '';
    }/*}}}*/
    
	//过滤构造过程中产生的非法字段
    public function filterFields()
    {/*{{{*/
        $data = $this->toAry();
        $dataFields = array_keys($data);
        foreach ($dataFields as $field)
        {
            if (!in_array($field,$this->fields))
            {
                $this->remove($field);
            }
        }
    }/*}}}*/

    public function removeNullAttr()
    {/*{{{*/
        $data = $this->toAry();
        foreach( $data as $field=>$val)
        {
            if( is_null( $val ) )
            {
                $this->remove($field);
            }

        }
    }/*}}}*/

    //检查是否有变化
    public function isChange($field,$value,$strict=false)
    {/*{{{*/
        if (!$this->have($field))
        {
            return true;
        }
        if (false === $strict && $this->$field != $value)
        {
            return true;
        }
        if (true === $strict && $this->$field !== $value)
        {
            return true;
        }
        return false;
    }/*}}}*/
}/*}}}*/
?>
