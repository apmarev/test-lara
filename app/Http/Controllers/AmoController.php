<?php

namespace App\Http\Controllers;

use App\Models\Amo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AmoController extends Controller
{
    public function amoKey() {
        $data = [
            'client_id' => '82281748-6462-4b05-9497-12fbef801743',
            'client_secret' => 'rU3CK0kjMVIqNajlpRB5E1Un4NyczMT2fqH5leHQgAVdlLAxNMilliX7Q1cIyEYC',
            'grant_type' => 'authorization_code',
            'code' => 'def502000d6496f90592903c6d1a0b1e981a9dc7db3d219fd8392d23e4d084d7d9acca1d0edb70301fc21497e90d5a2e5e8ec4af9b058479c4d0d26371447ef5f9d40123f1513bac675cc416d0b392a091d1dbeb44494ace568e4054a8d199df8ddc1f68f63e47e76d9573c64457ac547f19512c20e1c3f86987583e40fcf452711dcbd5859bcdadd802023c90122978b2411dd90bdf54955edc7028b0e8cdbe888be59007295766d9390effea6e331ce9b2ef5b19e7242f466cf4608904c08f53737a5bb48494cddee89c5088b95ba7327e8fe0ec294c14614a388dcf1661190d375a2b08d31e47845ab6c6584d1cab5c36d4680fabd5918cb4c7e8da2c856f98df0314b3b65adc5ca599d294ac17f5d2fca26cacc529b25510bbae7e27644b734be0e66bd28df0dce8fd646eba6a42c3a222c0c189e47bd3e96bb5961cce7520470a1fcb1d212af18f2293793ece85d49802a8f717be51c8548c0cad6fb4a11c671d895d72c3b773b36b857e941c29870d1b2859e582812dff9000af3155bcfaaa1f500d57452f409088da7c2d3b8b8aeeaaaef2aecd7ca13adbf137029bfbcfd78235c7baf2a30c348be870e1d18931892311d4767f6d703d0a2d392a35886ad5659603dc5d6d7bec4eb34e2c0b4773e5bd99157c3b',
            'redirect_uri' => 'https://onix-tech.ru/'
        ];
        $domain = config('app.amoDomain');
        $response = Http::post("https://{$domain}.amocrm.ru/oauth2/access_token", $data);

        if(isset($response['access_token']) && $response['access_token'] != '' &&
            isset($response['refresh_token']) && $response['refresh_token'] != '' &&
            isset($response['expires_in']) && $response['expires_in'] > 0 ) {
            $amoModel = new Amo();
            $amoModel->__set('access_token', $response['access_token']);
            $amoModel->__set('refresh_token', $response['refresh_token']);
            $amoModel->__set('expires_in', time() + $response['expires_in']);
            $amoModel->save();
            return $amoModel;
        } else {
            return 0;
        }
    }

    public function getAmo() {
        $amo = Amo::find(1);
        if($amo['expires_in'] <= time()) {
            $newAmo = $this->editAmo($amo['refresh_token']);
            return $newAmo['access_token'];
        } else {
            return $amo['access_token'];
        }
    }

    public function editAmo($refresh_token) {
        $data = [
            'client_id' => '82281748-6462-4b05-9497-12fbef801743',
            'client_secret' => 'rU3CK0kjMVIqNajlpRB5E1Un4NyczMT2fqH5leHQgAVdlLAxNMilliX7Q1cIyEYC',
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
            'redirect_uri' => 'https://onix-tech.ru/'
        ];
        $domain = config('app.amoDomain');
        $response = Http::post("https://{$domain}.amocrm.ru/oauth2/access_token", $data);

        if(isset($response['access_token']) && $response['access_token'] != '' &&
            isset($response['refresh_token']) && $response['refresh_token'] != '' &&
            isset($response['expires_in']) && $response['expires_in'] > 0 ) {
            $amoModel = new Amo(1);
            $amoModel->__set('access_token', $response['access_token']);
            $amoModel->__set('refresh_token', $response['refresh_token']);
            $amoModel->__set('expires_in', time() + $response['expires_in']);
            $amoModel->save();
            return $amoModel;
        } else {
            return 0;
        }
    }
}
