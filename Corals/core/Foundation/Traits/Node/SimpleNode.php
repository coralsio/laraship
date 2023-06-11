<?php

namespace Corals\Foundation\Traits\Node;

use Illuminate\Database\Eloquent\Collection;

/**
 * Simple Node model trait.
 *
 * Simple tree implementation, for advanced implementation see:
 * October\Rain\Database\Traits\NestedNode
 *
 * SimpleNode is the bare minimum needed for tree functionality, the
 * methods defined here should be implemented by all "tree" traits.
 *
 * Usage:
 *
 * Model table must have parent_id table column.
 * In the model class definition:
 *
 *   use \October\Rain\Database\Traits\SimpleNode;
 *
 * General access methods:
 *
 *   $model->getChildren(); // Returns children of this node
 *   $model->getChildCount(); // Returns number of all children.
 *   $model->getAllChildren(); // Returns all children of this node
 *   $model->getAllRoot(); // Returns all root level nodes (eager loaded)
 *   $model->getAll(); // Returns everything in correct order.
 *
 * Query builder methods:
 *
 *   $query->listsNested(); // Returns an indented array of key and value columns.
 *
 * You can change the sort field used by declaring:
 *
 *   const PARENT_ID = 'my_parent_column';
 */
trait SimpleNode
{
    public function getRoot($parentCode = null)
    {
        if ($this->parent) {
            if ($this->parent->code == $parentCode) {
                return $this;
            }
            return $this->parent->getRoot($parentCode);
        }

        return $this;
    }

    /**
     * Get the parent that owns the node.
     */
    public function parent()
    {
        return $this->belongsTo(get_class($this), $this->getParentColumnName());
    }

    /**
     * Get the children belongs to the parent node.
     */
    public function children()
    {
        $orderBy = 'id';

        if (property_exists($this, 'orderField')) {
            $orderBy = $this->orderField;
        }

        return $this->hasMany(get_class($this), $this->getParentColumnName())->orderBy($orderBy);
    }

    public function isAChild()
    {
        return !empty($this->{$this->getParentColumnName()});
    }

    /**
     * Returns all nodes and children.
     *
     * @return Collection
     */
    public function getAll()
    {
        $collection = [];

        foreach ($this->getAllRoot() as $rootNode) {
            $collection[] = $rootNode;
            $collection = $collection + $rootNode->getAllChildren()->getDictionary();
        }

        return new Collection($collection);
    }

    /**
     * Returns a list of all root nodes, eager loaded.
     * @return mixed
     */
    public function getAllRoot()
    {
        return $this->get()->toNested();
    }

    public function scopeRoot($query)
    {
        return $query->where($this->getParentColumnName(), 0);
    }

    public function isRoot()
    {
        return $this->{$this->getParentColumnName()} == 0;
    }

    /**
     * @param null $status
     * @return Collection
     */
    public function getAllChildren($status = null)
    {
        $result = [];
        $children = $this->getChildren($status);

        foreach ($children as $child) {
            $result[] = $child;

            $childResult = $child->getAllChildren($status);

            foreach ($childResult as $subChild) {
                $result[] = $subChild;
            }
        }

        return new Collection($result);
    }

    /**
     * @param null $status
     * @return mixed
     */
    public function getChildren($status = null)
    {
        if ($status) {
            return $this->children()->where('status', $status)->get();
        }

        return $this->children;
    }

    /**
     * @param null $status
     * @return bool
     */
    public function hasChildren($status = null)
    {
        return count($this->getAllChildren($status)) > 0;
    }

    /**
     * Returns number of all children below it.
     *
     * @return int
     */
    public function getChildCount()
    {
        return count($this->getAllChildren());
    }

    /**
     * Gets an array with values of a given column. Values are indented according to their depth.
     *
     * @param string $column Array values
     * @param string $key Array keys
     * @param string $indent Character to indent depth
     *
     * @return array
     */
    public function scopeListsNested($query, $column, $key = null, $indent = '&nbsp;&nbsp;&nbsp;')
    {
        /*
         * Recursive helper function
         */
        $buildCollection = function ($items, $depth = 0) use (&$buildCollection, $column, $key, $indent) {
            $result = [];

            $indentString = str_repeat($indent, $depth);

            foreach ($items as $item) {
                if ($key !== null) {
                    $result[$item->{$key}] = $indentString . $item->{$column};
                } else {
                    $result[] = $indentString . $item->{$column};
                }

                /*
                 * Add the children
                 */
                $childItems = $item->getChildren();

                if ($childItems->count() > 0) {
                    $result = $result + $buildCollection($childItems, $depth + 1);
                }
            }

            return $result;
        };

        /*
         * Build a nested collection
         */
        $rootItems = $query->get()->toNested();
        $result = $buildCollection($rootItems);

        return $result;
    }

    /**
     * Get parent column name.
     *
     * @return string
     */
    public function getParentColumnName()
    {
        return defined('static::PARENT_ID') ? static::PARENT_ID : 'parent_id';
    }

    /**
     * Get fully qualified parent column name.
     *
     * @return string
     */
    public function getQualifiedParentColumnName()
    {
        return $this->getTable() . '.' . $this->getParentColumnName();
    }

    /**
     * Get value of the model parent_id column.
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->getAttribute($this->getParentColumnName());
    }
}
