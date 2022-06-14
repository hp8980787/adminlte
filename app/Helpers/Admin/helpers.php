<?php
if (!function_exists('adminRoute')) {
    function adminRoute($router,  $array = null): string
    {

        return route('admin.' . $router, $array);
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
