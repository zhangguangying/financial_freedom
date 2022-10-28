<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;

class CrawlQieMan extends Command
{
    protected $signature = 'crawl:qieman';

    protected $description = '获取且慢账户金额';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $accessToken = $this->getAccessToken();
        $balance     = $this->getAccountBalance($accessToken);
        Account::query()
            ->where('name', '且慢')
            ->update(['value' => $balance]);
    }

    protected function getAccountBalance($accessToken)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => 'https://qieman.com/pmdj/v2/asset/detail?all=true',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => array(
                'x-request-id: albus.174C058E43EA772E9508',
                'x-sign: 16669629015359DF4CABC3335CEE1CCA17007EAD79E83',
                'User-Agent: apifox/1.0.0 (https://www.apifox.cn)',
                'Authorization: Bearer '.$accessToken,
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        curl_close($curl);

        return $response['totalAsset'] ?? 0;
    }

    /**
     * 获取 accessToken
     *
     * @return mixed|string
     */
    protected function getAccessToken()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => 'https://qieman.com/pmdj/v1/user/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => '{"user":"13693313072","password":"xingkongxia51"}',
            CURLOPT_HTTPHEADER     => array(
                'x-request-id: albus.174C058E43EA772E9508',
                'x-sign: 16669629015359DF4CABC3335CEE1CCA17007EAD79E83',
                'User-Agent: apifox/1.0.0 (https://www.apifox.cn)',
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);

        curl_close($curl);
        return $response['accessToken'] ?? '';
    }
}
