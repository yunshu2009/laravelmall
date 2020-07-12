<?php

use Illuminate\Database\Seeder;
use App\Models\Mysql\UmsAddress;

class UmsAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = ['2','7','8'];
        $faker = app(Faker\Generator::class);

        $addresses = factory(UmsAddress::class)->times(8)->make()->each(function ($status) use ($faker, $userIds) {
            $status->user_id = $faker->randomElement($userIds);
        });

        UmsAddress::insert($addresses->toArray());

        foreach ($userIds as $userId) {
            $address = UmsAddress::where('user_id', $userId)->first();
            if ($address) {
                $address->is_default = 1;
                $address->save();
            }
        }
    }
}
