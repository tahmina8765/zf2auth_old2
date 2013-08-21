<?php

namespace Zf2auth\Table;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zf2auth\Entity\RoleResources;

class RoleResourcesTable extends AbstractTableGateway
{

    protected $table = 'role_resources';

    public function __construct(Adapter $adapter)
    {
        $this->adapter            = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new RoleResources());

        $this->initialize();
    }

    public function fetchAll(Select $select = null)
    {
        $adapter = $this->adapter;
        if (null === $select)
            $select  = new Select();
        $select->from($this->table);

//        echo "<pre>";
//        echo $select->getSqlString();
//        die();

        $sql       = new Sql($adapter);
        $statement = $sql->getSqlStringForSqlObject($select);
        $resultSet = $adapter->query($statement, $adapter::QUERY_MODE_EXECUTE);
        $resultSet->buffer();
        return $resultSet;
    }

    public function getRoleResources($id)
    {
        $id     = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row    = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveRoleResources(RoleResources $formdata)
    {
        $data = array(
            'role_id'     => $formdata->role_id,
            'resource_id' => $formdata->resource_id,
        );

        $id = (int) $formdata->id;
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getRoleResources($id)) {
                $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteRoleResources($id)
    {
        $this->delete(array('id' => $id));
    }

}

