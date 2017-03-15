<?php

namespace Amamarul\Modules\Process;

use Amamarul\Modules\Contracts\RunableInterface;
use Amamarul\Modules\Repository;

class Runner implements RunableInterface
{
    /**
     * The module instance.
     *
     * @var \Amamarul\Modules\Repository
     */
    protected $module;

    /**
     * The constructor.
     *
     * @param \Amamarul\Modules\Repository $module
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
