<?php namespace Corals\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Lang;

class TranslateLocalisation extends Command
{
    /*
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'translate {--module=} {--theme=} {--namespace=} {--from=} {--to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate Laravel Localisation Files using Google Translator';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @var string $from
     */
    private $from;

    /**
     * @var string $to
     */
    private $to;

    /**
     * @var string $modules
     */
    private $module;

    /**
     * @var string $namespace
     */
    private $namespace;

    /**
     * @var string $theme
     */
    private $theme;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->module = $this->option('module');
        $this->namespace = $this->option('namespace');
        $this->theme = $this->option('theme');

        $this->from = $this->option('from') ?? 'en';
        $this->to = $this->option('to') ?? 'pt-br';;

        $result = $this->translate();

        $this->info($result);
    }

    public function translate()
    {
        $translation_modules = [];

        if ($this->module) {

            $module_paths = config('settings.models.module.paths');
            $moduleSettings = \Modules::getModulesSettings($this->module);
            $module_path = $module_paths[$moduleSettings->type] . "/" . $moduleSettings->folder;
            $translation_modules[] = $module_path . '/resources/lang';

        } else if ($this->theme) {
            $translation_modules[] = 'resources/themes/' . $this->theme . '/lang';
        }


        $this->iterateTranslationFolders($translation_modules);

        return 'Translation saved successfully';
    }

    public function iterateTranslationFolders($translation)
    {
        foreach ($translation as $key => $directory) {
            $dir = base_path() . '/' . $directory . '/' . $this->from . '/';
            $translationFiles = scandir($dir, 1);
            $this->info('----- TRANSLATING ' . $directory . ' -----');
            $this->iterateTranslationFiles($translationFiles, $directory);
        }
    }


    public function iterateTranslationFiles($translationFiles, $directory)
    {
        foreach ($translationFiles as $file) {
            $fileInfo = pathinfo($file, PATHINFO_EXTENSION);
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            if ($fileInfo === 'php') {
                $translated = $this->iterateTranslationLines($fileName);
                $translationPath = base_path() . '/' . $directory . '/' . $this->to;
                if (!is_dir($translationPath)) {
                    mkdir($translationPath, 0700);
                }

                file_put_contents($translationPath . '/' . $fileName . '.php',
                    "<?php " . "\r\n \r\n  return [ " . " \r\n \r\n " . $translated . "\r\n \r\n" . "];"
                );
            }
        }
    }

    public function iterateTranslationLines($fileName)
    {
        $translationQueue = Lang::get($this->namespace . "::" . $fileName);
        if (!is_array($translationQueue)) {
            $translationQueue = [];
        }
        $translated = "";
        $this->info('----- TRANSLATING ' . $fileName . ' -----');
        foreach ($translationQueue as $item => $value) {
            $this->info('Translating ' . '\'' . $item . '\'');
            if (is_array($value)) {
                $translated = $translated . '\'' . $item . '\'' . ' => [' . "\r\n";
                foreach ($value as $key => $val) {
                    if (is_array($val)) {
                        $translated = $translated . '\'' . $key . '\'' . ' => [' . "\r\n";

                        foreach ($val as $key2 => $val2) {
                            if (preg_match('/:\S+/', $val2, $matches)) {
                                $translatedWord = $this->translateWithParameters($val2);
                                $translated = $translated . '\'' . $key2 . '\'' . ' => ' . '\'' . $translatedWord . '\', ' . "\r\n";
                            } else {
                                $translatedWord = str_replace('\'', '\\\'', (string)$this->googleTranslation($val2));
                                $translated = $translated . '\'' . $key2 . '\'' . ' => ' . '\'' . $translatedWord . '\', ' . "\r\n";
                            }
                            sleep(1);

                        }

                        $translated = $translated . "],\r\n";

                    } else {
                        if (preg_match('/:\S+/', $val, $matches)) {
                            $translatedWord = $this->translateWithParameters($val);
                            $translated = $translated . '\'' . $key . '\'' . ' => ' . '\'' . $translatedWord . '\', ' . "\r\n";
                        } else {
                            $translatedWord = str_replace('\'', '\\\'', (string)$this->googleTranslation($val));
                            $translated = $translated . '\'' . $key . '\'' . ' => ' . '\'' . $translatedWord . '\', ' . "\r\n";
                        }
                        sleep(1);

                    }


                }
                $translated = $translated . "],\r\n";

            } else {
                if (preg_match('/:\S+/', $value, $matches)) {
                    $translatedWord = $this->translateWithParameters($value);
                    $translated = $translated . '\'' . $item . '\'' . ' => ' . '\'' . $translatedWord . '\', ' . "\r\n";
                } else {
                    $translatedWord = str_replace('\'', '\\\'', (string)$this->googleTranslation($value));
                    $translated = $translated . '\'' . $item . '\'' . ' => ' . '\'' . $translatedWord . '\', ' . "\r\n";
                }
                sleep(1);
            }
        }
        return $translated;
    }

    public function translateWithParameters($line)
    {
        preg_match('/:\S+/', $line, $matches);
        $line = str_replace($matches[0], '$$$$$$$$$', $line);
        return str_replace('$$$$$$$$$', $matches[0], str_replace('\'', '\\\'', (string)$this->googleTranslation($line)));
    }

    public function googleTranslation($line)
    {
        $url = "https://translate.google.com/translate_a/single?client=at&dt=t&dt=ld&dt=qca&dt=rm&dt=bd&dj=1&hl=es-ES&ie=UTF-8&oe=UTF-8&inputm=2&otf=2&iid=1dd3b944-fa62-4b55-b330-74909a99969e";
        $fields = array(
            'sl' => urlencode($this->from),
            'tl' => urlencode($this->to),
            'q' => urlencode($line)
        );
        if (strlen($fields['q']) >= 5000)
            return $line;

        // URL-ify the data for the POST
        $fields_string = "";
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'AndroidTranslate/5.3.0.RC02.130475354-53000263 5.1 phone TRANSLATE_OPM5_TEST_1');
        $result = curl_exec($ch);
        curl_close($ch);

        $sentencesArray = json_decode($result, true);
        $sentences = "";
        if (is_array($sentencesArray["sentences"])) {
            foreach ($sentencesArray["sentences"] as $s) {
                $sentences .= isset($s["trans"]) ? $s["trans"] : '';
            }
        } else {
            $sentences .= $line;
        }

        return $sentences;

    }
}
