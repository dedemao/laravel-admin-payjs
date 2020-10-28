<?php

namespace Dedemao\Payjs\Console\Commands;

use Encore\Admin\Auth\Database\Menu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payjs:deleteMenu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '删除菜单';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Menu::query()->where('uri','like','payjs%')->delete();
        $this->info('菜单删除完毕');
    }
}
