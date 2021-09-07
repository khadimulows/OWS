<?php

namespace Pitangent\Workflow\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class AuthMakeCommand extends Command
{
    /**
     * Store the command API or WEB.
     *
     * @var string
     */
    protected $isApi = false;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pitangent:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create auth relates classs';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->isApi = $this->option('api') ? true :  true;

        $params = ['name' => 'AuthController', '--model'=> 'User', '--auth' => true];

        if( $this->isApi ){
            $params['--api'] = true;
        }

        $this->call('pitangent:controller', $params);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['api', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be an API controller'],
        ];
    }
}
