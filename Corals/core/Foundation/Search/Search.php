<?php

namespace Corals\Foundation\Search;

class Search implements SearchInterface
{
    /**
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Collection|\Corals\Foundation\Search\IndexedRecord[]
     */
    public function run($search, $config)
    {
        $query = $this->searchQuery($search, $config);
        return $query->get();
    }

    /**
     * @param $search
     * @param $class
     * @return mixed
     */
    public function runForClass($search, $class, $config)
    {
        $query = $this->searchQuery($search, $config);
        $query = $query->where('indexable_type', $class);

        return $query->get();
    }

    /**
     * @param $query
     * @param $search
     * @param $class
     * @param array $config
     * @return mixed
     */
    public function AddSearchPart($query, $search, $class, $config = [])
    {
        if (strlen($search) < 2) {
            return $query;
        }

        $terms = TermBuilder::terms($search, $config);

        $query->leftJoin('fulltext_search', function ($join) use ($class) {
            $join->on($class::getTableName() . '.id', '=', 'fulltext_search.indexable_id');
        })->where('fulltext_search.indexable_type', '=', getModelMorphMap($class));

        $termsMatch = '' . $terms->implode(' ');
        $termsBool = '+' . $terms->implode(' +');


        $titleWeight = str_replace(',', '.', (float)data_get($config, 'title_weight', 1.5));
        $contentWeight = str_replace(',', '.', (float)data_get($config, 'content_weight', 1.0));

        $isBoolean = data_get($config, 'boolean', true);

        $query
            ->when($isBoolean, function ($query) use ($termsBool) {
                $query->whereRaw('MATCH (indexed_title, indexed_content) AGAINST (? IN BOOLEAN MODE)', [$termsBool]);
            })
            ->when(!$isBoolean, function ($query) use ($termsMatch) {
                $query->
                whereRaw('MATCH (indexed_title, indexed_content) AGAINST (? IN NATURAL LANGUAGE MODE)', [$termsMatch]);
            })->orderByRaw(
                '(' . $titleWeight . ' * (MATCH (indexed_title) AGAINST (?)) +
              ' . $contentWeight . ' * (MATCH (indexed_title, indexed_content) AGAINST (?))
             ) DESC',
                [$termsMatch, $termsMatch]
            );

        return $query;
    }

    /**
     * @param string $search
     * @param array $config
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchQuery($search, $config)
    {
        $terms = TermBuilder::terms($search, $config);

        $termsMatch = '' . $terms->implode(' ');
        $termsBool = '+' . $terms->implode(' +');

        $titleWeight = str_replace(',', '.', (float)$config['title_weight'] ?? 1.5);
        $contentWeight = str_replace(',', '.', (float)$config['content_weight'] ?? 1.0);

        $query = \Corals\Foundation\Search\IndexedRecord::query()
            ->whereRaw('MATCH (indexed_title, indexed_content) AGAINST (? IN BOOLEAN MODE)', [$termsBool])
            ->orderByRaw(
                '(' . $titleWeight . ' * (MATCH (indexed_title) AGAINST (?)) +
              ' . $contentWeight . ' * (MATCH (indexed_title, indexed_content) AGAINST (?))
             ) DESC',
                [$termsMatch, $termsMatch]
            )
            ->limit($config['limit_results'] ?? 10)
            ->with('indexable');
        return $query;
    }
}
