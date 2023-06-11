<?php

namespace Corals\Foundation\Search;

/**
 * Class Indexable
 *
 * @package Corals\Foundation\SearchServiceProvider
 */
trait Indexable
{
    public static function bootIndexable()
    {
        static::created(function ($model) {
            $model->indexRecord();
        });

        static::updated(function ($model) {
            $model->indexRecord();
        });
    }

    public function getIndexContent()
    {
        return $this->getIndexDataFromColumns($this->indexContentColumns);
    }

    public function getIndexTitle()
    {
        return $this->getIndexDataFromColumns($this->indexTitleColumns);
    }

    public function indexedRecord()
    {
        return $this->morphOne('Corals\Foundation\Search\IndexedRecord', 'indexable');
    }

    public function indexRecord()
    {
        $record = $this->indexedRecord()->firstOrNew([]);

        $record->updateIndex();
    }

    public function unIndexRecord()
    {
        if (null !== $this->indexedRecord) {
            $this->indexedRecord->delete();
        }
    }

    protected function getIndexDataFromColumns($columns)
    {
        $indexData = [];
        foreach ($columns as $column) {
            if ($this->indexDataIsJSON($column)) {
                $indexData[] = TermBuilder::textCleanUp(data_get($this, $column, ''));
            } elseif ($this->indexDataIsRelation($column)) {
                $indexData[] = TermBuilder::textCleanUp($this->getIndexValueFromRelation($column));
            } else {
                $value = $this->{$column};

                if (is_array($value)) {
                    $value = json_encode($value);
                }

                $indexData[] = TermBuilder::textCleanUp($value);
            }
        }
        return implode(' ', array_filter($indexData));
    }

    /**
     * @param $column
     * @return bool
     */
    protected function indexDataIsJSON($column): bool
    {
        if ((int)strpos($column, '.') < 0) {
            return false;
        }

        $columnName = explode('.', $column)[0] ?? null;

        return data_get($this->casts, $columnName) === 'json';
    }

    /**
     * @param $column
     * @return bool
     */
    protected function indexDataIsRelation($column)
    {
        return (int)strpos($column, '.') > 0;
    }

    /**
     * @param $column
     * @return string
     */
    protected function getIndexValueFromRelation($column)
    {
        list($relation, $column) = explode('.', $column);
        if (is_null($this->{$relation})) {
            return '';
        }

        return $this->{$relation}()->pluck($column)->implode(', ');
    }
}
