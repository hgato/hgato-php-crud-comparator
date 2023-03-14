<?php

namespace Hgato\PhpCrudComparator;

use Hgato\PhpCrudComparator\Exceptions\ModelTypeException;

class ObjectComparator
{
    private $old = [];
    private $new = [];

    private $update = [];
    private $create = [];
    private $delete = [];

    /**
     * @param array $old
     * @param array $new
     * @param array $ids
     * @param array $fields
     * @return void
     * @throws ModelTypeException
     */
    public function compare(array $old, array $new, array $ids, array $fields)
    {
        $this->hash($old, $new, $ids);

        foreach ($this->makeKeys() as $key) {
            $old = array_key_exists($key, $this->old) ? $this->old[$key] : null;
            $new = array_key_exists($key, $this->new) ? $this->new[$key] : null;

            $this->checkUpdated($old, $new, $fields);
            $this->checkCreate($old, $new);
            $this->checkDelete($old, $new);
        }
    }

    /**
     * @return array
     */
    public function getUpdate(): array
    {
        return $this->update;
    }

    /**
     * @return array
     */
    public function getCreate(): array
    {
        return $this->create;
    }

    /**
     * @return array
     */
    public function getDelete(): array
    {
        return $this->delete;
    }

    /**
     * @param $old
     * @param $new
     * @return void
     */
    private function checkDelete($old, $new)
    {
        if ($old && !$new) {
            $this->delete []= $old;
        }
    }

    /**
     * @param $old
     * @param $new
     * @return void
     */
    private function checkCreate($old, $new)
    {
        if (!$old && $new) {
            $this->create []= $new;
        }
    }

    /**
     * @param $old
     * @param $new
     * @param array $fields
     * @return void
     * @throws ModelTypeException
     */
    private function checkUpdated($old, $new, array $fields)
    {
        if ($old && $new) {
            foreach ($fields as $field) {
                if ($this->getValue($old, $field) !== $this->getValue($new, $field)) {
                    foreach ($fields as $f) {
                        $this->setValue($old, $f, $this->getValue($new, $f));
                    }
                    $this->update []= $old;
                    break;
                }
            }
        }
    }

    /**
     * @param array $old
     * @param array $new
     * @param array $ids
     * @return void
     * @throws ModelTypeException
     */
    private function hash(array $old, array $new, array $ids)
    {
        foreach ($old as $model) {
            $id = $this->makeKey($model, $ids);
            $this->old [$id] = $model;
        }
        foreach ($new as $model) {
            $id = $this->makeKey($model, $ids);
            $this->new [$id] = $model;
        }
    }

    /**
     * @return array
     */
    private function makeKeys() : array
    {
        return array_unique(array_merge(array_keys($this->old), array_keys($this->new)));
    }

    /**
     * @param $model
     * @param array $ids
     * @return string
     * @throws ModelTypeException
     */
    private function makeKey($model, array $ids) : string
    {
        $keyArray = [];
        foreach ($ids as $id) {
            $keyArray []= $this->getValue($model, $id);
        }
        return implode('-', $keyArray);
    }

    /**
     * @param $model
     * @param $key
     * @return mixed
     * @throws ModelTypeException
     */
    private function getValue($model, $key)
    {
        if (is_array($model)) {
            return $model[$key];
        }
        if (is_object($model)) {
            return $model->$key;
        }
        throw new ModelTypeException('Model must be of type array or object');
    }

    private function setValue(&$model, $key, $value)
    {
        if (is_array($model)) {
            $model[$key] = $value;
            return;
        }
        if (is_object($model)) {
            $model->$key = $value;
            return;
        }
        throw new ModelTypeException('Model must be of type array or object');
    }
}