<?php

namespace Corals\Foundation\Traits\Language;

use Corals\Foundation\Models\Language\Translation;
use Corals\Theme\Commands\refreshCache;
use Illuminate\Support\Facades\App;

trait Translatable
{
    protected $queuedTranslations = [];

    public function hasTranslatableFields()
    {
        $translatable = config($this->config . '.' . 'translatable');

        return $translatable
            && is_array($translatable)
            && !empty($translatable);
    }

    /**
     * Boot the audit trait for a model.
     *
     * @return void
     */
    public static function bootTranslatable()
    {
        static::saving(function ($item) {

            unset($item->translation_submit);

            $languageCode = $item->translation_language_code ?? request()->input('translation_language_code');

            unset($item->translation_language_code);

            if (!$item->hasTranslatableFields()) {
                return;
            }

            if (empty($languageCode) || config('app.fallback_locale') == $languageCode) {
                return;
            }

            foreach ($item->getConfig('translatable') as $key) {
                $item->queuedTranslations[$languageCode][$key] = request()->input($key) ?? $item->attributes[$key];

                if ($item->exists) {
                    unset($item->attributes[$key]);
                }
            }
        });

        static::saved(function ($item) {
            foreach ($item->queuedTranslations as $languageCode => $translatables) {
                $item->setTranslationByArray($languageCode, $translatables);
            }
        });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function allTranslations()
    {
        $translations = collect([]);

        $attributes = $this->getAttributes();

        $locales = $this->translations()->get()->groupBy('locale')->keys();

        foreach ($locales as $locale) {
            $translation = collect([]);

            foreach ($attributes as $attribute => $value) {
                if ($this->isTranslatableAttribute($attribute) && $this->hasTranslation($locale, $attribute)) {
                    $translation->put($attribute, $this->getTranslation($attribute, $locale));
                } else {
                    $translation->put($attribute, parent::getAttributeValue($attribute));
                }
            }

            $translations->put($locale, $translation);
        }

        return $translations;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        if (!$this->isTranslatableAttribute($key) || config('app.fallback_locale') == App::getLocale()) {
            return parent::getAttributeValue($key);
        }

        $translationValue = $this->getTranslation($key, App::getLocale());

        if (empty($translationValue)) {
            return parent::getAttributeValue($key);
        } elseif ($this->hasCast($key)) {
            return $this->castAttribute($key, $translationValue);
        } else {
            return $translationValue;
        }
    }

    /**
     * returns all attributes that are translatable.
     *
     * @return array
     */
    public function getTranslatableAttributes()
    {
        return ($this->hasTranslatableFields())
            ? $translatableAttributes = config($this->config . '.' . 'translatable')
            : [];
    }

    /**
     * @param $locale
     *
     * @return Translatable
     */
    public function in($locale)
    {
        $translatedModel = new self();

        foreach ($this->getAttributes() as $attribute => $value) {
            if ($this->isTranslatableAttribute($attribute)) {
                if ($this->hasTranslation($locale, $attribute)) {

                    $translationValue = $this->getTranslation($attribute, $locale);

                    if (empty($translationValue)) {
                        $translationValue = parent::getAttributeValue($attribute);
                    } elseif ($this->hasCast($attribute)) {
                        $translationValue = $this->castAttribute($attribute, $translationValue);
                    }

                    $translatedModel->setAttribute($attribute, $translationValue);
                } elseif (config('app.fallback_locale') == $locale) {
                    $translatedModel->setAttribute($attribute, parent::getAttributeValue($attribute));
                } else {
                    $translatedModel->setAttribute($attribute, $this->getAttribute($attribute));
                }
            } else {
                $translatedModel->setAttribute($attribute, $this->getAttribute($attribute));
            }
        }

        return $translatedModel;
    }

    /**
     * @param $locale
     */
    public function removeTranslationIn($locale)
    {
        $this->translations()
            ->where('locale', $locale)
            ->delete();
    }

    /**
     * @param $locale
     * @param $attribute
     */
    public function removeTranslation($locale, $attribute)
    {
        $this->translations()
            ->where('locale', $locale)
            ->where('key', $attribute)
            ->delete();
    }

    /**
     * @return mixed
     */
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * returns the translation of a key for a given key/locale pair.
     *
     * @param $key
     * @param $locale
     *
     * @return mixed
     */
    public function getTranslation($key, $locale)
    {
        return $this->translations()
            ->where('key', $key)
            ->where('locale', $locale)
            ->value('translation');
    }

    /**
     * @param $locale
     * @param $attribute
     *
     * @return bool
     */
    public function hasTranslation($locale, $attribute)
    {
        $translation = $this->translations()
            ->where('locale', $locale)
            ->where('key', $attribute)
            ->first();

        return $translation !== null;
    }

    /**
     * returns if given key is translatable.
     *
     * @param $key
     *
     * @return bool
     */
    public function isTranslatableAttribute($key)
    {
        return in_array($key, $this->getTranslatableAttributes());
    }

    /**
     * @param $locale
     * @param $attribute
     * @param $translation
     *
     * @return void
     */
    public function setTranslation($locale, $attribute, $translation)
    {
        $this->translations()->updateOrCreate(['key' => $attribute, 'locale' => $locale,], [
            'translation' => $translation
        ]);
    }

    /**
     * @param $locale
     * @param $translations
     *
     * @return void
     */
    public function setTranslationByArray($locale, $translations)
    {
        foreach ($translations as $attribute => $translation) {
            if ($this->isTranslatableAttribute($attribute)) {
                $this->setTranslation($locale, $attribute, $translation);
            }
        }
    }

    /**
     * returns the translation of a key for a given key/locale pair.
     *
     * @param $key
     * @param $locale
     *
     * @return mixed
     */
    public function translateAttribute($key, $locale)
    {
        if (!$this->isTranslatableAttribute($key) || config('app.fallback_locale') == $locale) {
            return parent::getAttributeValue($key);
        }

        return $this->getTranslation($key, $locale);
    }

    /**
     * @param $locale
     * @param $attribute
     * @param $translation
     *
     * @return void
     */
    public function updateTranslation($locale, $attribute, $translation)
    {
        $this->translations()
            ->where('key', $attribute)
            ->where('locale', $locale)
            ->update([
                'translation' => $translation,
            ]);
    }
}
