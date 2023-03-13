<?php

namespace classes;

class PublicClass
{
    public $id1;
    public $id2;
    public $field1;
    public $field2;

    /**
     * @param $id1
     * @param $id2
     * @param $field1
     * @param $field2
     */
    public function __construct($id1, $id2, $field1, $field2)
    {
        $this->id1 = $id1;
        $this->id2 = $id2;
        $this->field1 = $field1;
        $this->field2 = $field2;
    }


}