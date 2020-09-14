<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private $districts = [
        'Quận 1', 
        'Quận 12', 
        'Quận Thủ Đức', 
        'Quận 9', 
        'Quận Gò Vấp',
        'Quận Bình Thạnh',
        'Quận Tân Bình',
        'Quận Tân Phú', 
        'Quận Phú Nhuận', 
        'Quận 2', 
        'Quận 3', 
        'Quận 10', 
        'Quận 11', 
        'Quận 4', 
        'Quận 5', 
        'Quận 6', 
        'Quận 6', 
        'Quận Bình Tân', 
        'Quận 7', 
        'Huyện Củ Chi', 
        'Huyện Hóc Môn', 
        'Huyện Bình Chánh', 
        'Huyện Nhà Bè', 
        'Huyện Cần Giờ'
    ];

    public function run()
    {
        DB::table('addresses')->insert([
            'street' => Str::random(10),
            'ward' => Str::random(10),
            'district' => $this->districts[rand(0, count($this->districts) - 1)],
            'city' => 'Hồ Chí Minh',
            'latitude' =>  0.25 * (mt_rand() / mt_getrandmax()) + 10.877112,
            'longitude' => -0.25 * (mt_rand() / mt_getrandmax()) + 106.809504,
            'type' => 'apartment'
        ]);
    }
}
