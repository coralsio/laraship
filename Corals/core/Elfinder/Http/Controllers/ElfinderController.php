<?php namespace Corals\Elfinder\Http\Controllers;

use Corals\Elfinder\Connector;
use Corals\Elfinder\Session\LaravelSession;
use Corals\Foundation\Http\Controllers\BaseController;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Request;

class ElfinderController extends BaseController
{
    protected $package = 'elfinder';

    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        parent::__construct();
        $this->app = $app;
    }

    public function showIndex()
    {
        return $this->app['view']
            ->make($this->package . '::elfinder')
            ->with($this->getViewVars());
    }

    public function showTinyMCE()
    {
        return $this->app['view']
            ->make($this->package . '::tinymce')
            ->with($this->getViewVars());
    }

    public function showTinyMCE4()
    {
        return $this->app['view']
            ->make($this->package . '::tinymce4')
            ->with($this->getViewVars());
    }

    public function showCKeditor4()
    {
        return $this->app['view']
            ->make($this->package . '::ckeditor4')
            ->with($this->getViewVars());
    }

    public function showPopup($input_id)
    {
        return $this->app['view']
            ->make($this->package . '::standalonepopup')
            ->with($this->getViewVars())
            ->with(compact('input_id'));
    }

    public function showFilePicker($input_id)
    {
        $type = Request::input('type');
        $mimeTypes = implode(',', array_map(function ($t) {
            return "'" . $t . "'";
        }, explode(',', $type)));
        return $this->app['view']
            ->make($this->package . '::filepicker')
            ->with($this->getViewVars())
            ->with(compact('input_id', 'type', 'mimeTypes'));
    }

    public function showConnector()
    {
        $roots = $this->app->config->get('elfinder.roots', []);
        if (empty($roots)) {
            if (user()->hasPermissionTo('Administrations::admin.core')) {
                $dirs = (array)$this->app['config']->get('elfinder.dir.root', []);
            } else {
                $dirs = (array)$this->app['config']->get('elfinder.dir.private', []);
                $dirs = array_map(function ($dir) {
                    return $dir . '_' . user()->hashed_id;
                }, $dirs);
            }

            foreach ($dirs as $dir) {
                if (!\File::exists($dir)) \File::makeDirectory($dir, 0775, true);

                $data = [
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => public_path($dir), // path to files (REQUIRED)
                    'URL' => url($dir), // URL to files (REQUIRED)
                    'defaults' => array('read' => true, 'write' => true),
                    'accessControl' => $this->app->config->get('elfinder.access'), // filter callback (OPTIONAL)
                    'uploadAllow' => array('image/png', 'image/jpeg', 'image/gif', 'application/zip', 'application/pdf'),
                    'uploadDeny' => array('all'),
                    'uploadOrder' => 'deny,allow'
                ];

                $data = \Filters::do_filter('el_finder_root', $data);


                $roots[] = $data;
            }

            $disks = (array)$this->app['config']->get('elfinder.disks', []);
            foreach ($disks as $key => $root) {
                if (is_string($root)) {
                    $key = $root;
                    $root = [];
                }
                $disk = app('filesystem')->disk($key);
                if ($disk instanceof FilesystemAdapter) {
                    $defaults = [
                        'driver' => 'Flysystem',
                        'filesystem' => $disk->getDriver(),
                        'alias' => $key,
                    ];
                    $roots[] = array_merge($defaults, $root);
                }
            }
        }

        if (app()->bound('session.store')) {
            $sessionStore = app('session.store');
            $session = new LaravelSession($sessionStore);
        } else {
            $session = null;
        }

        $rootOptions = $this->app->config->get('elfinder.root_options', array());
        foreach ($roots as $key => $root) {
            $roots[$key] = array_merge($rootOptions, $root);
        }

        $opts = $this->app->config->get('elfinder.options', array());
        $opts = array_merge($opts, ['roots' => $roots, 'session' => $session]);

        // run elFinder
        $connector = new Connector(new \elFinder($opts));
        $connector->run();
        return $connector->getResponse();
    }

    protected function getViewVars()
    {
        //TODO::Corals Move elfinder to core
        $dir = \Theme::url('plugins/' . $this->package);
        $assetPath = \Theme::current()->assetPath;

        //TODO::Corals locale switcher
        $locale = str_replace("-", "_", $this->app->config->get('app.locale'));

        if (!file_exists($this->app['path.public'] . "/$assetPath/plugins/{$this->package}/js/i18n/elfinder.$locale.js")) {
            $locale = false;
        }

        $csrf = true;

        $title = trans('elfinder::module.elfinder.title');

        return compact('dir', 'locale', 'csrf', 'title');
    }
}
