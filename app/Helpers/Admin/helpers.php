<?php

use Illuminate\Support\Str;

if (!function_exists('adminRoute')) {
    function adminRoute($router, $array = null): string
    {

        return route($router, $array);
    }
}

if (!function_exists('responseTable')) {
    function responseTable(array $data, object $object, string $editUrl, string $delUrl): array
    {
        foreach ($data['data'] as $k => $val) {
            $val['editUrl'] = adminRoute($editUrl, $val['id']);
            $val['delUrl'] = adminRoute($delUrl, $val['id']);
            $data['data'][$k] = $val;
        }
        $data = [
            'data' => $data['data'],
            "links" => [
                "first" => $object->url(1),
                "last" => $object->url($object->lastPage()),
                "prev" => $object->previousPageUrl(),
                "next" => $object->nextPageUrl()
            ],
            "meta" => [
                "currentPage" => $object->currentPage(),
                "from" => 1,
                "lastPage" => $object->lastPage(),
                "path" => request()->url(),
                "perPage" => $object->perPage(),
                "total" => $object->total(),
            ],
        ];
        return $data;
    }
}

function rate(string $currency): float
{
    if (\Illuminate\Support\Facades\Cache::has('rate')) {
        return \Illuminate\Support\Facades\Cache::get('rate')[$currency];
    } else {
//        $client = new \GuzzleHttp\Client();
//        $response = $client->request('GET', 'https://v6.exchangerate-api.com/v6/4e140e3466f15bf23ce5df58/latest/USD');
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            "Content-Type" => " text/plain",
            "apikey" => " Y9AGJ0BH2qpUBdxmH4vrbvFfIjVMOK2B"
        ])->get('https://api.apilayer.com/exchangerates_data/latest?base=USD');

        if ($response->getStatusCode() == '200') {

            $result = json_decode($response->body(), true);
            if ($result['success']) {
                $rate = $result['rates'];
                \Illuminate\Support\Facades\Cache::put('rate', $rate, 604800);
                $export = export54($rate);
                $text = '<?php ' . PHP_EOL . 'return' . PHP_EOL . $export . ';';
                file_put_contents(config_path('currency.php'), $text);
            }

        } else {
            $rate = config('currency.rate');
        }

        return $rate[$currency];
    }
}

function export54($var, $indent = "")
{

    switch (gettype($var)) {
        case "string":
            return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
        case "array":
            $indexed = array_keys($var) === range(0, count($var) - 1);
            $r = [];
            foreach ($var as $key => $value) {
                $r[] = "$indent    "
                    . ($indexed ? "" : export54($key) . " => ")
                    . export54($value, "$indent    ");
            }
            return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
        case "boolean":
            return $var ? "TRUE" : "FALSE";
        default:
            return var_export($var, TRUE);
    }
}

if (!function_exists('generateSku')) {
    function generateSku(): string
    {
        $now = now()->format('ymdhis');
        return $now . substr(time(), -1, 4) . Str::random(5);
    }
}
