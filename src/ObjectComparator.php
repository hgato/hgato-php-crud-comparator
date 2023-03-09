<?php

namespace Hgato\PhpCrudComparator;

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
     */
    private function checkUpdated($old, $new, array $fields)
    {
        if ($old && $new) {
            foreach ($fields as $field) {
                if ($old->$field !== $new->$field) {
                    $this->update []= $new;
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
     */
    private function makeKey($model, array $ids) : string
    {
        $keyArray = [];
        foreach ($ids as $id) {
            $keyArray []= $model->$id;
        }
        return implode('-', $keyArray);
    }
}