<?php

namespace Corals\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LanguageController extends PublicBaseController
{
    /**
     * Set locale if it's allowed.
     * @param Request $request
     * @param $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLocale(Request $request, $locale)
    {
        \Language::setLanguage($locale);

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function datatableLanguage(Request $request)
    {
        $language = \Language::getNameEnglish(\App::getLocale());

        $i18nArray = \Cache::remember('datatable_i18n_' . $language, config('corals.cache_ttl'), function () use ($language) {
            $languagePath = "assets/corals/plugins/datatables.net/i18n/$language.lang";

            if (file_exists(public_path($languagePath))) {
                $languagePath = public_path($languagePath);

                $content = File::get($languagePath, true);

                $data = json_decode(cleanJSONFileContent($content), true);

                return $data;
            } else {
                return '';
            }
        });

        return $i18nArray;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getModelTranslation(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);


            $model = $data['model']::findByHash($data['hashed_id']);
            $model = $model->in($data['lang_code']);
            $config = with(new $data['model'])->config;
            $attributes = $model->getAttributes();

            $translatables = [];

            foreach (config($config . '.' . 'translatable') as $translatable) {
                $translatables[$translatable] = $attributes[$translatable];
            }

            return ['hashed_id' => $data['hashed_id'], 'translateables' => $translatables];
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return ['hashed_id' => null, 'translateables' => []];
        }
    }
}
