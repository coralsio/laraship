<?php

namespace Corals\Foundation\Search;

use Corals\Foundation\Models\BaseModel;

class IndexedRecord extends BaseModel
{

    protected $table = 'fulltext_search';

    public function indexable()
    {
        return $this->morphTo();
    }

    public function updateIndex()
    {
        $this->setAttribute('indexed_title', $this->indexable->getIndexTitle());
        $this->setAttribute('indexed_content', $this->indexable->getIndexContent());
        $this->save();
    }
}
