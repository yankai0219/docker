<?php
class BaseEntity extends Entity
{
    public function create()
    {
        if (! $this->have('createline'))
        {
            $this->createline = date('Y-m-d H:i:s', time());
        }
        if (! $this->have('updateline'))
        {
            $this->updateline = date('Y-m-d H:i:s', time());
        }
        $dao = LoaderSvc::loadDao($this->cls);
        return $dao->add($this);
    }
    public function updateByProp($prop)
    {
        if (! $this->have('updateline'))
        {
            $this->updateline = time();
        }
        $dao = LoaderSvc::loadDao($this->cls);
        return $dao->updateById($this->id, $prop, $this->cls);
    }
    public function del()
    {
        $dao = LoaderSvc::loadDao($this->cls);
        return $dao->delById($this->id, $this->cls);
    }
	//过滤构造过程中产生的非法字段
	public function filterFields()
	{
	    $data = $this->toAry();
		$dataFields = array_keys($data);         
		foreach ($dataFields as $field)
		{
			if (!in_array($field,$this->fields))
			{
				$this->remove($field);
			}
		}
	}
    public function doCreate()
    {
        $this->filterFields();
        return $this->create();
    }
    public function getLastInsertId()
    {
        $dao = LoaderSvc::loadDao($this->cls);
        return $dao->getLastInsertId();
    }
}
