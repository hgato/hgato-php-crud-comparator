# CRUD Comparator

This library is meant to compare arrays of models and return 3 arrays: to create, to update, to delete.

Logic of execution is following:
- array of old models is provided as a first argument
- array of new models is provided as a second argument
- array of id fields is provided as the third argument
- array of secondary fields is provided as fourth argument
- if the model with same ids (all id fields treated as 
  combined key) exists in both old and new array but secondary fields
  are different - model is treated as one for update
- if the model with same ids exists in both old and new array and secondary fields
  are same - model is ignored
- if model is in old array but not in new - model is meant to be deleted
- im model vice versa in noe, but not in old - model is meant for creation

Typical use case: frontend sends an array of models, but doesn't say what have changed.
Simultaneously some data is in database already. This library helps to fund difference.

## Example

```php
class TestObject
{
  public $id;
  public $key;
  public $name;
  public $description;
}

$oldArray = [/* Some instances of TestObject*/];
$newArray = [/* Other instances of TestObject*/];

$comparator = new ObjectComparator();
$comparator->compare(
    $oldArray,
    $newArray,
    ['id', 'key'],
    ['name', 'description']
);

$create = $comparator->getCreate();
$update = $comparator->getUpdate();
$delete = $comparator->getDelete();
```