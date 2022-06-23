<?php

namespace App\Imports;

use App\Models\Order;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class OrdersImport implements ToCollection
{
    public function collection(Collection $collection)
    {
//        Validator::make($collection->toArray(), [
//            '*.0' => 'required',
//            '*.1' => 'required',
//            '*.2' => 'required',
//            '*.4' => 'required',
//            '*.5' => 'required',
//            '*.6' => 'required',
//            '*.7' => 'required',
//            '*.8' => 'required',
//            '*.10' => 'required',
//            '*.11' => 'required',
//            '*.14' => 'required'
//        ])->validate();
        $column = $collection[0]->toArray();
        unset($collection[0]);
        $field = (new Order())->getFillable();

        $keyValue = array_map(function ($val) use ($column) {
            if (array_search($val, $column) !== false) {
                $key = array_search($val, $column);
                return ['key' => $key, 'value' => $val];
            }
        }, $field);
        $keyValueSort = array_column($keyValue, 'value', 'key');

        foreach ($collection as $item) {
            $data = [
                'trans_id' => $item[array_search('trans_id', $keyValueSort)] ?? trim($item[array_search('order_number', $keyValueSort)]),
                'order_number' => trim($item[array_search('order_number', $keyValueSort)]),
                'total' => $item[array_search('total', $keyValueSort)],
                'total_usd' => $item[array_search('total_usd', $keyValueSort)] ?? 0,
                'currency' => $item[array_search('currency', $keyValueSort)] ?? '',
                'name' => $item[array_search('name', $keyValueSort)],
                'phone' => $item[array_search('phone', $keyValueSort)],
                'email' => $item[array_search('email', $keyValueSort)],
                'postal' => $item[array_search('postal', $keyValueSort)],
                'country' => $item[array_search('country', $keyValueSort)],
                'state' => $item[array_search('state', $keyValueSort)] ?? '',
                'city' => $item[array_search('city', $keyValueSort)],
                'street1' => $item[array_search('street1', $keyValueSort)],
                'street2' => $item[array_search('street2', $keyValueSort)],
                'ip' => $item[array_search('ip', $keyValueSort)],
                'description' => $item[array_search('description', $keyValueSort)],
                'product_code' => $item[array_search('description', $keyValueSort)],

            ];
            if ($item[array_search('phone',$keyValueSort)]){
                Order::query()->create($data);
            }

        }
    }


}
