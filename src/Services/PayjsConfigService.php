<?php
namespace Dedemao\Payjs\Services;

use Carbon\Carbon;
use Dedemao\Payjs\Models\PayjsConfigs;
use Illuminate\Support\Facades\Cache;
use Dedemao\Payjs\Facades\PayjsService;

class PayjsConfigService
{

    /**
     * 获取后台当前操作的 Payjs 配置信息
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getCurrent()
    {
        $key = 'payjs.config';
        $payjsConfig = Cache::get($key);
        if (!$payjsConfig) {
            $config = PayjsConfigs::query()->first();

            if (!$config) {
                return null;
            }

            Cache::put($key,$config->toArray(), Carbon::now()->addHours(2));

            return $config;
        }

        return $payjsConfig;
    }

    /**
     * 通过 mchid 获取支付实例
     * @param string $mchId
     * @return mixed
     */
    public function getInstanceByMchId(string $mchId)
    {
        $config = Cache::get('payjs.config.mchid.'.$mchId);

        if (!$config) {
            $model = PayjsConfigs::query()->where('mchid', $mchId)->firstOrFail();

            $config = ['mchid' => $model->mch_id, 'appkey' => $model->key, 'pay_channel' => $model->pay_channel, 'notify_url' => $model->notify_url];

            Cache::forever('payjs.config.mchid.'.$mchId, $config);
        }

        return PayjsService::payment([
            'mchid' => $config['mchid'],
            'appkey' => $config['appkey'],
            'pay_channel' => $config['pay_channel'],
            'notify_url' => $config['notify_url'],
        ]);
    }
}
