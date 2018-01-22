<?php

namespace RLStudio\Laraman;

use Illuminate\Routing\Router;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Ramsey\Uuid\Uuid;

class ExportRoutesToPostman extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraman:export {--name=laraman-export} {--api} {--web} {--port=8000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all routes to a json file that can be imported in Postman';

    /**
     * The Laravel router.
     *
     * @var \Illuminate\Routing\Router
     */
    private $router;

    /**
     * The filesystem implementation.
     *
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    private $files;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Routing\Router $router
     * @param \Illuminate\Contracts\Filesystem\Filesystem $files
     */
    public function __construct(Router $router, Filesystem $files)
    {
        $this->router = $router;
        $this->files = $files;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->option('api') && !$this->option('web')) {
            $this->info("Please, specify the type of export with flags.\nYou can use --api or --web.");
        } else {
            $name = $this->option('name');
            $port = $this->option('port');
            // Set the base data.
            $routes = [
              'variables' => [],
              'info' => [
                  'name' => $name,
                  '_postman_id' => Uuid::uuid4(),
                  'description' => '',
                  'schema' => 'https://schema.getpostman.com/json/collection/v2.0.0/collection.json',
              ]
          ];

            foreach ($this->router->getRoutes() as $route) {
                foreach ($route->methods as $method) {
                    if ($method == 'HEAD') {
                        continue;
                    }
                    if ($this->option('api') && $route->middleware()[0] == "api") {
                        $routes['item'][] = [
                        'name' => $method.' | '.$route->uri(),
                        'request' => [
                            'url' => url(':' . $port . '/' . $route->uri()),
                            'method' => strtoupper($method),
                            'header' => [
                                [
                                    'key' => 'Content-Type',
                                    'value' => 'application/json',
                                    'description' => ''
                                ]
                            ],
                            'body' => [
                                'mode' => 'raw',
                                'raw' => '{\n    \n}'
                            ],
                            'description' => '',
                        ],
                        'response' => [],
                    ];
                    }
                    if ($this->option('web') && $route->middleware()[0] == "web") {
                        $routes['item'][] = [
                        'name' => $method.' | '.$route->uri(),
                        'request' => [
                            'url' => url(':' . $port . '/' . $route->uri()),
                            'method' => strtoupper($method),
                            'header' => [
                                [
                                    'key' => 'Content-Type',
                                    'value' => 'text/html',
                                    'description' => ''
                                ]
                            ],
                            'body' => [
                                'mode' => 'raw',
                                'raw' => '{\n    \n}'
                            ],
                            'description' => '',
                        ],
                        'response' => [],
                    ];
                    }
                }
            }
            if (! $this->files->put($name.'.json', json_encode($routes))) {
                $this->error('Export failed');
            } else {
                $this->info('Routes exported!');
            }
        }
    }
}
