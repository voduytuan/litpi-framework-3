<?php

namespace {{MODULE_NAMESPACE}};

use \Litpi\Helper as Helper;

/**
 * {{MODULE}} Class
 *
 * File contains the class used for {{MODULE}} Model
 *
 * @category Litpi
 * @package {{MODULE_NAMESPACE}}
 * @author Vo Duy Tuan <tuanmaster2012@gmail.com>
 * @copyright Copyright (c) 2013 - Litpi Framework (http://www.litpi.com)
 */

class {{MODULE}} extends {{MODULE_BASECLASS}}
{
    {{PROPERTY}}
    public function __construct(${{PRIMARY_PROPERTY}} = 0)
    {
        parent::__construct();

        if ($id > 0) {
            $this->getData(${{PRIMARY_PROPERTY}});
        }
    }

    /**
     * Insert object data to database
     * @return int The inserted record primary key
     */
    public function addData()
    {
        {{DATECREATED_ASSIGN}}
        $sql = 'INSERT INTO ' . TABLE_PREFIX . '{{TABLE_WITHOUT_PREFIX}} (
                    {{PROPERTY_ADD_LIST}}
                )
                VALUES({{PROPERTY_ADD_QUESTIONMARK}})';
        $rowCount = $this->{{DB_OBJECT}}->query($sql, array(
            {{PROPERTY_ADD_BINDING}}
        ))->rowCount();

        $this->{{PRIMARY_PROPERTY}} = $this->{{DB_OBJECT}}->lastInsertId();

        return $this->{{PRIMARY_PROPERTY}};
    }

    /**
     * Update database
     *
     * @return boolean Indicate query success or not
     */
    public function updateData()
    {
        {{DATEMODIFIED_ASSIGN}}
        $sql = 'UPDATE ' . TABLE_PREFIX . '{{TABLE_WITHOUT_PREFIX}}
                SET {{PROPERTY_UPDATE_LIST}}
                WHERE {{PRIMARY_FIELD}} = ?';

        $stmt = $this->{{DB_OBJECT}}->query($sql, array(
            {{PROPERTY_UPDATE_BINDING}},
            (int)$this->{{PRIMARY_PROPERTY}}
        ));

        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the object data base on primary key
     * @param int $id : the primary key value for searching record.
     */
    public function getData(${{PRIMARY_PROPERTY}})
    {
        ${{PRIMARY_PROPERTY}} = (int)${{PRIMARY_PROPERTY}};
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . '{{TABLE_WITHOUT_PREFIX}} {{TABLE_ALIAS_NODOT}}
                WHERE {{TABLE_ALIAS_DOT}}{{PRIMARY_FIELD}} = ?';
        $row = $this->{{DB_OBJECT}}->query($sql, array(${{PRIMARY_PROPERTY}}))->fetch();

        $this->getDataByArray($row);
    }

    public function getDataByArray($row)
    {
        {{PROPERTY_ASSIGN_DATA_THIS}}
    }

    /**
     * Delete current object from database, base on primary key
     *
     * @return int the number of deleted rows (in this case, if success is 1)
     */
    public function delete()
    {
        $sql = 'DELETE FROM ' . TABLE_PREFIX . '{{TABLE_WITHOUT_PREFIX}}
                WHERE {{PRIMARY_FIELD}} = ?';
        $rowCount = $this->{{DB_OBJECT}}->query($sql, array($this->{{PRIMARY_PROPERTY}}))->rowCount();

        return $rowCount;
    }

    /**
     * Count the record in the table base on condition in $where
     *
     * @param string $where: the WHERE condition in SQL string.
     */
    private static function countList($where, $bindParams)
    {
        ${{DB_OBJECT}} = self::getDb();

        $sql = 'SELECT COUNT(*) FROM ' . TABLE_PREFIX . '{{TABLE_WITHOUT_PREFIX}} {{TABLE_ALIAS_NODOT}}';

        if ($where != '') {
            $sql .= ' WHERE ' . $where;
        }

        return ${{DB_OBJECT}}->query($sql, $bindParams)->fetchColumn(0);
    }

    /**
     * Get the record in the table with paginating and filtering
     *
     * @param string $where the WHERE condition in SQL string
     * @param string $order the ORDER in SQL string
     * @param string $limit the LIMIT in SQL string
     */
    private static function getList($where, $order, $limit, $bindParams)
    {
        ${{DB_OBJECT}} = self::getDb();

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . '{{TABLE_WITHOUT_PREFIX}} {{TABLE_ALIAS_NODOT}}';

        if ($where != '') {
            $sql .= ' WHERE ' . $where;
        }

        if ($order != '') {
            $sql .= ' ORDER BY ' . $order;
        }

        if ($limit != '') {
            $sql .= ' LIMIT ' . $limit;
        }

        $outputList = array();
        $stmt = ${{DB_OBJECT}}->query($sql, $bindParams);
        while ($row = $stmt->fetch()) {
            $my{{MODULE}} = new self();
            $my{{MODULE}}->getDataByArray($row);
            $outputList[] = $my{{MODULE}};
        }

        return $outputList;
    }

    /**
     * Select the record, Interface with the outside (Controller Action)
     *
     * @param array $formData : filter array to build WHERE condition
     * @param string $sortby : indicating the order of select
     * @param string $sorttype : DESC or ASC
     * @param string $limit: the limit string, offset for LIMIT in SQL string
     * @param boolean $countOnly: flag to counting or return datalist
     *
     */
    public static function get{{MODULE_SIMPLIFY}}s($formData, $sortby, $sorttype, $limit = '', $countOnly = false)
    {
        $whereString = '';
        $bindParams = array();

        {{MODULE_FILTERABLE}}

        {{MODULE_SEARCHABLETEXT}}

        //checking sort by & sort type
        if ($sorttype != 'DESC' && $sorttype != 'ASC') {
            $sorttype = 'DESC';
        }

        {{MODULE_SORTABLE}}

        if ($countOnly) {
            return self::countList($whereString, $bindParams);
        } else {
            return self::getList($whereString, $orderString, $limit, $bindParams);
        }
    }{{FUNCTION_GETMAXDISPLAYORDER}}{{FUNCTION_CONSTANT}}
}
