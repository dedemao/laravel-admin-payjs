<?php

namespace Dedemao\Payjs\Console\Commands;

use Encore\Admin\Auth\Database\Menu;
use Illuminate\Console\Command;

class CreateMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payjs:createMenu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成菜单';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $menu = Menu::query()->firstOrCreate([
            'title' => 'Payjs',
        ],[
            'parent_id' => 0,
            'order' => 80,
            'icon' => 'fa-cny',
            'uri' => 'payjs/index'
        ]);

        Menu::query()->firstOrCreate([
            'title' => '支付设置',
        ],[
            'parent_id' => $menu->id,
            'order' => 80,
            'icon' => 'fa-cog',
            'uri' => 'payjs/index'
        ]);

        Menu::query()->firstOrCreate([
            'title' => '订单列表',
        ],[
            'parent_id' => $menu->id,
            'order' => 80,
            'icon' => 'fa-bars',
            'uri' => 'payjs/order',
        ]);

        $this->info('菜单生成完毕');
    }
}
