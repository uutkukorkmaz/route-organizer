<?php

namespace App\Console\Commands;


use \Illuminate\Console\GeneratorCommand;
use \Illuminate\Filesystem\Filesystem;
use \Illuminate\Support\Composer;
use \Illuminate\Support\Str;
use \Symfony\Component\Console\Input\InputArgument;

class MakeRouteGroupCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:route {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new route group class';


    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Route';

    /**
     * The type of stub
     *
     * @var string
     */
    protected $stubType = "empty"; // empty | group-only | resource


    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;
    private $placeholders;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct($files);
        $this->composer = $composer;
    }

    public function handle()
    {
        if (!$this->argument('name')) {
            throw new \http\Exception\InvalidArgumentException('Missing required argument: Route Group Name');
        }
        $this->replace('namespace',$this->getDefaultNamespace());
        $this->replace('class',$this->argument('name'));
        if (in_array($this->choice("Would you like to create group with prefix?", ["y" => "yes", "n" => "no"], "yes"), ["y", "yes"])) {
            $route_group = $this->ask("Please specify a prefix for the route group");
            $this->stubType = "group-only";
            $this->replace('route_group',$route_group);
        }
        if (in_array($this->choice("Would you like to bind your routes to a model?", ["y" => "yes", "n" => "no"], "no"), ["y", "yes"])) {
            $model = $this->ask("Please specify a model name: (This action creates a Resourceful Controller if there is none)");
            $this->stubType = "resource";
            if ($this->qualifyModel($model)) {
                \Artisan::call('make:model', [
                    "name" => $model,
                    "-r" => true
                ]);
            }
            if (!class_exists($model.'Controller')) {
                \Artisan::call('make:controller', [
                    "name" => $model.'Controller',
                    "--model" => $model
                ]);
            }
            $this->replace('model',$model);
            $this->replace('model_lowercase',Str::lower($model));
            $this->replace('controller',$model . 'Controller');
            if (!isset($args["route_group"])) {
                $this->replace('route_group', $this->getRouteGroup());
            }
        }
        $this->generate($this->getStub());
        return 0;
    }

    protected function generate($stub)
    {
        $path = $this->getFilePath();
        $this->makeDirectory($path);
        $this->files->put($path,$this->build($stub));
        $this->info('Route group created successfully.');
    }

    protected function build($stub)
    {
        $template = file_get_contents($stub);
        return str_replace(array_keys($this->placeholders),array_values($this->placeholders),$template);
    }

    protected function replace($key, $value)
    {
        $this->placeholders['{{ ' . $key . ' }}'] = $value;
    }

    protected function getFilePath()
    {
        return app_path() . DIRECTORY_SEPARATOR . Str::plural($this->type) . DIRECTORY_SEPARATOR . $this->argument('name') . '.php';
    }

    protected function getRouteGroup()
    {
        $name = Str::kebab($this->argument('name'));
        if (Str::contains($name, '-routes')) {
            $name = Str::plural(Str::replace('-routes', null, $name));
        }
        return Str::plural($name);
    }

    public function getDefaultNamespace($rootNamespace = 'App')
    {
        return $rootNamespace . "\\" . Str::ucfirst(Str::plural($this->type));
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub($type = null)
    {
        $type = $type ?? $this->stubType;
        return base_path('stubs/route.' . $type . '.stub');
    }

    protected function getArguments()
    {
        return parent::getArguments();
    }

    protected function getOptions()
    {
        return [
            ['group', InputArgument::OPTIONAL, 'Set the prefix and name of the route group']
        ];
    }


}
