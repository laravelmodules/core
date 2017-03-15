<?php

namespace Amamarul\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class AdminMenuItemCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:admin-menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Admin menu item';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
