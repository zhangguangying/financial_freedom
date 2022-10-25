<?php

namespace App\Console\Commands;

use App\Models\Fund;
use App\Models\FundValue;
use Illuminate\Console\Command;

class CrawlFundValue extends Command
{
    protected $signature = 'crawl:fund_value';

    protected $description = '爬取基金净值';

    protected $url = "https://fundf10.eastmoney.com/F10DataApi.aspx?type=lsjz&code=%s&sdate=%s";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $codes = Fund::query()->pluck('code', 'id')->toArray();
        $date = date('Y-m-d', strtotime('-1 day'));
        foreach ($codes as $fund_id => $code) {
            $url  = sprintf($this->url, $code, $date);
            $data = file_get_contents($url);
            preg_match('/<td class=\'tor bold\'>(\d+\.?\d+)<\/td>/', $data, $match);
            if (empty($match)) {
                continue;
            }
            $value = $match[1];
            FundValue::query()->updateOrCreate(['fund_id' => $fund_id, 'date' => $date], [
                'net_worth' => $value,
            ]);
        }
    }
}
