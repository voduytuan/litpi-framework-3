<?php

namespace Litpi;

class MyPdoTransaction
{

    private $db = null;
    private $finished = false;

    public function __construct($db)
    {
        $this->db = $db;
        $this->db->beginTransaction();
    }

    public function __destruct()
    {
        if (!$this->finished) {
            $this->db->rollback();
        }
    }

    public function commit()
    {
        $this->finished = true;
        $this->db->commit();
    }

    public function rollback()
    {
        $this->finished = true;
        $this->db->rollback();
    }
}
