<?php

use arrays\SimpleArrayBuilder;
use classes\PublicClass;
use Hgato\PhpCrudComparator\ObjectComparator;

require_once implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'src', 'ObjectComparator.php']);
require_once implode(DIRECTORY_SEPARATOR, [__DIR__, 'classes', 'PublicClass.php']);
require_once implode(DIRECTORY_SEPARATOR, [__DIR__, 'arrays', 'SimpleArrayBuilder.php']);

class Test
{
    public function testPureObjects()
    {
        // TODO add tests for mixed
        $obj1 = new PublicClass(1, 'a', 1, 'a'); // leave 1
        $obj2 = new PublicClass(2, 'b', 2, 'b'); // update 1
        $obj3 = new PublicClass(3, 'c', 3, 'c'); // create 1
        $obj4 = new PublicClass(1, 'a', 1, 'a'); // leave 1
        $obj5 = new PublicClass(2, 'b', 3, 'c'); // update 1
        $obj6 = new PublicClass(4, 'd', 4, 'd'); // delete 1

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

    public function testPureArrays()
    {
        $obj1 = (new SimpleArrayBuilder)->build(1, 'a', 1, 'a'); // leave 1
        $obj2 = (new SimpleArrayBuilder)->build(2, 'b', 2, 'b'); // update 1
        $obj3 = (new SimpleArrayBuilder)->build(3, 'c', 3, 'c'); // create 1
        $obj4 = (new SimpleArrayBuilder)->build(1, 'a', 1, 'a'); // leave 1
        $obj5 = (new SimpleArrayBuilder)->build(2, 'b', 3, 'c'); // update 1
        $obj6 = (new SimpleArrayBuilder)->build(4, 'd', 4, 'd'); // delete 1

        $new = [$obj1, $obj2, $obj3];
        $old = [$obj4, $obj5, $obj6];

        $comparator = new ObjectComparator();
        $comparator->compare($old, $new, ['id1', 'id2'], ['field1', 'field2']);

        $create = $comparator->getCreate();
        if (count($create) !== 1 || $create[0]['id1'] !== 3) {
            throw new AssertionError('Create result is incorrect');
        }

        $update = $comparator->getUpdate();
        if (
            count($update) !== 1 ||
            $update[0]['id1'] !== $obj2['id1'] ||
            $update[0]['id2'] !== $obj2['id2'] ||
            $update[0]['field1'] !== $obj2['field1'] ||
            $update[0]['field2'] !== $obj2['field2']
        ) {
            throw new AssertionError('Update result is incorrect');
        }

        $delete = $comparator->getDelete();
        if (count($delete) !== 1 || $delete[0]['id1'] !== 4) {
            throw new AssertionError('Delete result is incorrect');
        }

        echo "success\n\r";
    }
}