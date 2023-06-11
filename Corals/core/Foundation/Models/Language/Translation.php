<?php

namespace Corals\Foundation\Models\Language;

use Corals\Foundation\Traits\ModelHelpersTrait;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use ModelHelpersTrait;
    /**
     * @var string
     */
    protected $table = 'translatable_translations';

    protected $fillable = ['key', 'locale', 'translation', 'translation_language_code'];

    /**
     * Get all of the owning translatable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function translatable()
    {
        return $this->morphTo();
    }
}
