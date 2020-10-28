<?php

namespace Dedemao\Payjs\Console\Commands;

use Encore\Admin\Auth\Database\Menu;
use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payjs:install {--m|migrate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '安装payjs，创建数据表、生成菜单等';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--tag' => 'laravel-admin-payjs', '--force' => true]);

        $this->call('payjs:createMenu');

        if ($this->option('migrate')) {
            $this->call('migrate', ['--force' => true]);
        }

        $this->info('payjs install success');
    }
}
