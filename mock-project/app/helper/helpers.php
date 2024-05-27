<?php

use Illuminate\Support\Facades\Cache;

if (!function_exists('responseApi')) {
    function responseApi($data = [], $status = true, $responseCode = 200)
    {
        return response()->json([
            'status' => $status,
            'payload' => $data,
        ], $responseCode);
    }
}

if (!function_exists('generate_organization_username_and_email')) {
    function generate_organization_username_and_email($name): array
    {
        $name = strtolower(\Pnlinh\VietnameseConverter\VietnameseConverter::make()->convert($name));

        $nameArr = explode(' ', $name);

        $lastName = array_pop($nameArr);

        $username = $lastName . array_reduce($nameArr, function ($carry, $item) {
                return $carry . substr($item, 0, 1);
            });

        $emailPrefix = $lastName . '.' . implode('', $nameArr);

        $usernameExisted = \App\Models\User::withTrashed()->where('username', 'like', $username . '%')->latest('id')->first();

        $lastSuffix = '';

        if ($usernameExisted) {
            $lastSuffix = substr($usernameExisted->username, strlen($username));

            $lastSuffix = is_numeric($lastSuffix) ? $lastSuffix + 1 : 1;
        }

        return [
            'username' => $username . $lastSuffix,
            'email' => $emailPrefix . $lastSuffix . '@' . config('util.organization_email_domain'),
        ];
    }
}

if (!function_exists('is_closure')) {
    function is_closure($param): bool
    {
        return $param instanceof \Closure;
    }
}

if (!function_exists('is_using_soft_deletes')) {
    function is_using_soft_deletes($model): bool
    {
        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model));
    }
}
