<?php

namespace Model;

abstract class BaseModel extends \Litpi\Model\BaseModel
{
    public static function getInQuestionHolder($values)
    {
        return implode(',', array_fill(0, count($values), '?'));
    }
}
