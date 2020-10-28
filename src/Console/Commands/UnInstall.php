<?php

namespace Dedemao\Payjs\Console\Commands;

use Encore\Admin\Auth\Database\Menu;
use Illuminate\Console\Command;

class UnInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payjs:uninstall {--m|migrate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '卸载payjs。删除数据表、去掉菜单等';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $this->call('vendor:publish', ['--tag' => 'laravel-admin-payjs', '--force' => true]);

        $this->call('payjs:deleteMenu');

        if ($this->option('migrate')) {
            $this->call('migrate:rollback', ['--force' => true]);
        }

        $this->info('payjs uninstall success');
    }
}
