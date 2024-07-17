<?php

namespace Corals\Foundation\Search;

use Illuminate\Support\Facades\DB;

/**
 * Class Indexable
 *
 * @package Corals\Foundation\SearchServiceProvider
 */
trait Indexable
{
    public bool $selfIndexed = false;
    public bool $replaceSpecialChar = true;
    public $indexedTitle = 'indexed_title';
    public $indexedContent = 'indexed_content';

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
        if ($this->selfIndexed) {
            return $this->hasOne(__CLASS__, 'id', 'id');
        } else {
            return $this->morphOne('Corals\Foundation\Search\IndexedRecord', 'indexable');
        }
    }

    public function indexRecord()
    {
        if ($this->selfIndexed) {
            $record = $this;
        } else {
            $record = $this->indexedRecord()->firstOrNew([]);
        }

        $record->updateIndex();
    }

    public function updateIndex($unIndex = false)
    {
        $title = $unIndex ? null : ($this->getIndexTitle() ?: null);
        $content = $unIndex ? null : ($this->getIndexContent() ?: null);
        DB::table($this->getTable())->where('id', $this->id)
            ->update([
                $this->indexedTitle => $title,
                $this->indexedContent => $content,
            ]);
    }

    public function unIndexRecord()
    {
        if ($this->selfIndexed) {
            $this->updateIndex(true);
        } else if (null !== $this->indexedRecord) {
            $this->indexedRecord->delete();
        }
    }

    protected function getIndexDataFromColumns($columns)
    {
        $indexData = [];
        foreach ($columns as $column) {
            if ($this->indexDataIsJSON($column)) {
                $indexData[] = TermBuilder::textCleanUp(data_get($this, $column, ''), $this->replaceSpecialChar);
            } elseif ($this->indexDataIsRelation($column)) {
                $indexData[] = TermBuilder::textCleanUp($this->getIndexValueFromRelation($column), $this->replaceSpecialChar);
            } else {
                $value = $this->{$column};

                if (is_array($value)) {
                    $value = json_encode($value);
                }

                $indexData[] = TermBuilder::textCleanUp($value, $this->replaceSpecialChar);
            }
        }
        return implode(' ', array_filter($indexData));
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
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

    public function getTermMapping($part)
    {
        return $part;
    }
}
