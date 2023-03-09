<?php

use Hgato\PhpCrudComparator\ObjectComparator;

require_once implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'src', 'ObjectComparator.php']);

class Test
{
    public function run()
    {
        // TODO refactor
        // TODO add tests for arrays
        // TODO add tests for mixed
        $obj1 = new stdClass(); // leave 1
        $obj1->id1 = 1;
        $obj1->id2 = 'a';
        $obj1->field1 = 1;
        $obj1->field2 = 'a';

        $obj2 = new stdClass(); // update 1
        $obj2->id1 = 2;
        $obj2->id2 = 'b';
        $obj2->field1 = 2;
        $obj2->field2 = 'b';

        $obj3 = new stdClass(); // create 1
        $obj3->id1 = 3;
        $obj3->id2 = 'c';
        $obj3->field1 = 2;
        $obj3->field2 = 'b';

        $obj4 = new stdClass(); // leave 1
        $obj4->id1 = 1;
        $obj4->id2 = 'a';
        $obj4->field1 = 1;
        $obj4->field2 = 'a';

        $obj5 = new stdClass(); // update 1
        $obj5->id1 = 2;
        $obj5->id2 = 'b';
        $obj5->field1 = 3;
        $obj5->field2 = 'c';

        $obj6 = new stdClass(); // delete 1
        $obj6->id1 = 4;
        $obj6->id2 = 'd';
        $obj6->field1 = 2;
        $obj6->field2 = 'b';

        $new = [$obj1, $obj2, $obj3];
        $old = [$obj4, $obj5, $obj6];

        $comparator = new ObjectComparator();
        $comparator->compare($old, $new, ['id1', 'id2'], ['field1', 'field2']);

        $create = $comparator->getCreate();
        if (count($create) !== 1 || $create[0]->id1 !== 3) {
            throw new AssertionError('Create result is incorrect');
        }

        $update = $comparator->getUpdate();
        if (
            count($update) !== 1 ||
            $update[0]->id1 !== $obj2->id1 ||
            $update[0]->id2 !== $obj2->id2 ||
            $update[0]->field1 !== $obj2->field1 ||
            $update[0]->field2 !== $obj2->field2
        ) {
            throw new AssertionError('Update result is incorrect');
        }

        $delete = $comparator->getDelete();
        if (count($delete) !== 1 || $delete[0]->id1 !== 4) {
            throw new AssertionError('Delete result is incorrect');
        }

        echo "success\n\r";
    }
}