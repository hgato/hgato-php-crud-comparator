<?php

namespace arrays;

class SimpleArrayBuilder
{
    public function build($id1, $id2, $field1, $field2)
    {
        return [
            'id1' => $id1,
            'id2' => $id2,
            'field1' => $field1,
            'field2' => $field2,
        ];
    }
}