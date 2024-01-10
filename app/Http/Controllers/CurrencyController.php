<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function getRates()
    {
        try {
            $client = new Client([
                'base_uri' => 'http://api.exchangeratesapi.io/v1/',
            ]);
             
            // EUR bazında döviz kurlarını çek
            $response = $client->request('GET', 'latest', [
                'query' => [
                    'access_key' => 'cc347547c83513378933f2a2512677b0',
                    'symbols' => 'USD,TRY,EUR,GBP'  // USD, EUR, GBP ve TRY kurlarını al
                ]
            ]);
             
            if ($response->getStatusCode() == 200) {
                $body = $response->getBody();
                $arr_body = json_decode($body, true); // JSON'u diziye çevir

                // TRY/EUR kuru
                $eurToTry = $arr_body['rates']['TRY'];

                // Diğer para birimlerinin EUR karşılıklarını al
                $usdInEur = $arr_body['rates']['USD'];
                $gbpInEur = $arr_body['rates']['GBP'];

                // USD ve GBP'nin TRY karşılıklarını hesapla
                $usdToTry = $eurToTry / $usdInEur;
                $gbpToTry = $eurToTry / $gbpInEur;

                // Hesaplanan kurları döndür
                return response()->json([
                    'success' => true,
                    'rates' => [
                        'USD' => $usdToTry,
                        'EUR' => $eurToTry,
                        'GBP' => $gbpToTry
                    ]
                ]);
            }
        } catch (\Exception $e) {
            // Hata durumunda hata mesajı döndür
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
