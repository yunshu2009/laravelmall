<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Mysql\UmsAddress;
use Faker\Generator as Faker;

$factory->define(UmsAddress::class, function (Faker $faker) {
    $date_time = $faker->date . ' ' . $faker->time;

    $addresses = [
        ["北京市", "市辖区", "东城区"],
        ["河北省", "石家庄市", "长安区"],
        ["江苏省", "南京市", "浦口区"],
        ["江苏省", "苏州市", "相城区"],
        ["广东省", "深圳市", "福田区"],
    ];
    $address   = $faker->randomElement($addresses);

    return [
        'name'           => $faker->name,
        'province'       => $address[0],
        'city'           => $address[1],
        'county'        => $address[2],
        'address_detail' => sprintf('第%d街道第%d号', $faker->randomNumber(2),
            $faker->randomNumber(3)),
        'area_code'      => $faker->postcode,
        'postal_code'    => $faker->postcode,
        'tel'            => $faker->phoneNumber,
        'created_at'     => $date_time,
        'updated_at'     => $date_time,
        'is_default'     => 0,
    ];
});
