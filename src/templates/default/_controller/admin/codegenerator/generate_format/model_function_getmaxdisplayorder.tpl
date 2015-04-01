

    private function getMaxDisplayOrder()
    {
        $sql = 'SELECT MAX({{FUNCTION_DISPLAYORDER_COLUMN}})
                FROM ' . TABLE_PREFIX . '{{TABLE_WITHOUT_PREFIX}}
                {{FUNCTION_DISPLAYORDER_GROUPWHERE}}';

        return ($this->db->query($sql, array({{FUNCTION_DISPLAYORDER_GROUPBINDING}}))->fetchColumn(0) + 1);
    }