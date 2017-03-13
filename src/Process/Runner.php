<?php

namespace Amamarul\ModulesMaru\Process;

use Amamarul\ModulesMaru\Contracts\RunableInterface;
use Amamarul\ModulesMaru\Repository;

class Runner implements RunableInterface
{
    /**
     * The module instance.
     *
     * @var \Amamarul\ModulesMaru\Repository
     */
    protected $module;

    /**
     * The constructor.
     *
     * @param \Amamarul\ModulesMaru\Repository $module
     */
    public function __construct(Repository $module)
    {
        $this->module = $module;
    }

    /**
     * Run the given command.
     *
     * @param string $command
     */
    public function run($command)
    {
        passthru($command);
    }
}
