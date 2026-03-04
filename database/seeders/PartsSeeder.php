<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartsSeeder extends Seeder
{
    public function run(): void
    {
        $parts = [

            // ==================== TOYOTA VITZ ====================
            [
                'vehicle_make' => 'Toyota', 'vehicle_model' => 'Vitz',
                'vehicle_year_from' => 2014, 'vehicle_year_to' => 2020,
                'part_name' => 'Engine Oil Filter', 'part_category' => 'Filters',
                'oem_part_number' => '90915-YZZD2',
                'alternative_part_number' => 'DENSO 150-2004',
                'brand' => 'Toyota Genuine',
                'description' => 'Engine oil filter for 1KR-FE engine. Replace every 5000km.',
            ],
            [
                'vehicle_make' => 'Toyota', 'vehicle_model' => 'Vitz',
                'vehicle_year_from' => 2014, 'vehicle_year_to' => 2020,
                'part_name' => 'Air Filter', 'part_category' => 'Filters',
                'oem_part_number' => '17801-B1010',
                'alternative_part_number' => 'DENSO DCF137',
                'brand' => 'Toyota Genuine',
                'description' => 'Air filter for 1KR-FE engine. Replace every 15000km.',
            ],
            [
                'vehicle_make' => 'Toyota', 'vehicle_model' => 'Vitz',
                'vehicle_year_from' => 2014, 'vehicle_year_to' => 2020,
                'part_name' => 'Spark Plug', 'part_category' => 'Spark Plugs',
                'oem_part_number' => '90919-01253',
                'alternative_part_number' => 'NGK ILZKR7B-11S',
                'brand' => 'Toyota Genuine',
                'description' => 'Iridium spark plug for 1KR-FE engine. Replace every 30000km.',
            ],
            [
                'vehicle_make' => 'Toyota', 'vehicle_model' => 'Vitz',
                'vehicle_year_from' => 2014, 'vehicle_year_to' => 2020,
                'part_name' => 'Front Brake Pads', 'part_category' => 'Brakes',
                'oem_part_number' => '04465-52220',
                'alternative_part_number' => 'AKEBONO AN-696WK',
                'brand' => 'Toyota Genuine',
                'description' => 'Front brake pad set. Inspect every 20000km.',
            ],
            [
                'vehicle_make' => 'Toyota', 'vehicle_model' => 'Vitz',
                'vehicle_year_from' => 2014, 'vehicle_year_to' => 2020,
                'part_name' => 'Drive Belt', 'part_category' => 'Belts',
                'oem_part_number' => '90916-02647',
                'alternative_part_number' => 'GATES 6PK950',
                'brand' => 'Toyota Genuine',
                'description' => 'Alternator drive belt. Replace every 80000km.',
            ],

            // ==================== TOYOTA PREMIO ====================
            [
                'vehicle_make' => 'Toyota', 'vehicle_model' => 'Premio',
                'vehicle_year_from' => 2007, 'vehicle_year_to' => 2021,
                'part_name' => 'Engine Oil Filter', 'part_category' => 'Filters',
                'oem_part_number' => '90915-03003',
                'alternative_part_number' => 'DENSO 150-2007',
                'brand' => 'Toyota Genuine',
                'description' => 'Engine oil filter for 1ZZ-FE / 2ZR-FE engine. Replace every 5000km.',
            ],
            [
                'vehicle_make' => 'Toyota', 'vehicle_model' => 'Premio',
                'vehicle_year_from' => 2007, 'vehicle_year_to' => 2021,
                'part_name' => 'Air Filter', 'part_category' => 'Filters',
                'oem_part_number' => '17801-22020',
                'alternative_part_number' => 'DENSO DCF056',
                'brand' => 'Toyota Genuine',
                'description' => 'Air filter for 1ZZ-FE / 2ZR-FE engine. Replace every 15000km.',
            ],
            [
                'vehicle_make' => 'Toyota', 'vehicle_model' => 'Premio',
                'vehicle_year_from' => 2007, 'vehicle_year_to' => 2021,
                'part_name' => 'Spark Plug', 'part_category' => 'Spark Plugs',
                'oem_part_number' => '90919-01210',
                'alternative_part_number' => 'NGK ILFR6B',
                'brand' => 'Toyota Genuine',
                'description' => 'Iridium spark plug for 2ZR-FE engine. Replace every 30000km.',
            ],
            [
                'vehicle_make' => 'Toyota', 'vehicle_model' => 'Premio',
                'vehicle_year_from' => 2007, 'vehicle_year_to' => 2021,
                'part_name' => 'Front Brake Pads', 'part_category' => 'Brakes',
                'oem_part_number' => '04465-02260',
                'alternative_part_number' => 'AKEBONO AN-697WK',
                'brand' => 'Toyota Genuine',
                'description' => 'Front brake pad set. Inspect every 20000km.',
            ],
            [
                'vehicle_make' => 'Toyota', 'vehicle_model' => 'Premio',
                'vehicle_year_from' => 2007, 'vehicle_year_to' => 2021,
                'part_name' => 'Cabin Air Filter', 'part_category' => 'Filters',
                'oem_part_number' => '87139-02090',
                'alternative_part_number' => 'DENSO DCF108',
                'brand' => 'Toyota Genuine',
                'description' => 'Cabin/AC air filter. Replace every 15000km.',
            ],

            // ==================== SUZUKI ALTO ====================
            [
                'vehicle_make' => 'Suzuki', 'vehicle_model' => 'Alto',
                'vehicle_year_from' => 2009, 'vehicle_year_to' => 2021,
                'part_name' => 'Engine Oil Filter', 'part_category' => 'Filters',
                'oem_part_number' => '16510-84M00',
                'alternative_part_number' => 'BOSCH P3337',
                'brand' => 'Suzuki Genuine',
                'description' => 'Engine oil filter for K10B engine. Replace every 5000km.',
            ],
            [
                'vehicle_make' => 'Suzuki', 'vehicle_model' => 'Alto',
                'vehicle_year_from' => 2009, 'vehicle_year_to' => 2021,
                'part_name' => 'Air Filter', 'part_category' => 'Filters',
                'oem_part_number' => '13780-68L00',
                'alternative_part_number' => 'BOSCH S0014',
                'brand' => 'Suzuki Genuine',
                'description' => 'Air filter for K10B engine. Replace every 15000km.',
            ],
            [
                'vehicle_make' => 'Suzuki', 'vehicle_model' => 'Alto',
                'vehicle_year_from' => 2009, 'vehicle_year_to' => 2021,
                'part_name' => 'Spark Plug', 'part_category' => 'Spark Plugs',
                'oem_part_number' => '09482-00523',
                'alternative_part_number' => 'NGK SILZKR7B11',
                'brand' => 'Suzuki Genuine',
                'description' => 'Iridium spark plug for K10B engine. Replace every 30000km.',
            ],
            [
                'vehicle_make' => 'Suzuki', 'vehicle_model' => 'Alto',
                'vehicle_year_from' => 2009, 'vehicle_year_to' => 2021,
                'part_name' => 'Front Brake Pads', 'part_category' => 'Brakes',
                'oem_part_number' => '55200-68L10',
                'alternative_part_number' => 'AKEBONO AN-760WK',
                'brand' => 'Suzuki Genuine',
                'description' => 'Front brake pad set. Inspect every 20000km.',
            ],

            // ==================== HONDA FIT ====================
            [
                'vehicle_make' => 'Honda', 'vehicle_model' => 'Fit',
                'vehicle_year_from' => 2008, 'vehicle_year_to' => 2020,
                'part_name' => 'Engine Oil Filter', 'part_category' => 'Filters',
                'oem_part_number' => '15400-PLM-A02',
                'alternative_part_number' => 'DENSO 150-2013',
                'brand' => 'Honda Genuine',
                'description' => 'Engine oil filter for L13A / L15A engine. Replace every 5000km.',
            ],
            [
                'vehicle_make' => 'Honda', 'vehicle_model' => 'Fit',
                'vehicle_year_from' => 2008, 'vehicle_year_to' => 2020,
                'part_name' => 'Air Filter', 'part_category' => 'Filters',
                'oem_part_number' => '17220-RB0-000',
                'alternative_part_number' => 'DENSO DCF085',
                'brand' => 'Honda Genuine',
                'description' => 'Air filter for L13A / L15A engine. Replace every 15000km.',
            ],
            [
                'vehicle_make' => 'Honda', 'vehicle_model' => 'Fit',
                'vehicle_year_from' => 2008, 'vehicle_year_to' => 2020,
                'part_name' => 'Spark Plug', 'part_category' => 'Spark Plugs',
                'oem_part_number' => '98079-5614H',
                'alternative_part_number' => 'NGK IZFR6K-11',
                'brand' => 'Honda Genuine',
                'description' => 'Iridium spark plug for L15A engine. Replace every 30000km.',
            ],
            [
                'vehicle_make' => 'Honda', 'vehicle_model' => 'Fit',
                'vehicle_year_from' => 2008, 'vehicle_year_to' => 2020,
                'part_name' => 'Front Brake Pads', 'part_category' => 'Brakes',
                'oem_part_number' => '45022-SAA-G00',
                'alternative_part_number' => 'AKEBONO AN-800WK',
                'brand' => 'Honda Genuine',
                'description' => 'Front brake pad set. Inspect every 20000km.',
            ],

            // ==================== TOYOTA AQUA ====================
            [
                'vehicle_make' => 'Toyota', 'vehicle_model' => 'Aqua',
                'vehicle_year_from' => 2012, 'vehicle_year_to' => 2021,
                'part_name' => 'Engine Oil Filter', 'part_category' => 'Filters',
                'oem_part_number' => '04152-YZZA6',
                'alternative_part_number' => 'DENSO 150-2019',
                'brand' => 'Toyota Genuine',
                'description' => 'Engine oil filter for 1NZ-FXE hybrid engine. Replace every 5000km.',
            ],
            [
                'vehicle_make' => 'Toyota', 'vehicle_model' => 'Aqua',
                'vehicle_year_from' => 2012, 'vehicle_year_to' => 2021,
                'part_name' => 'Air Filter', 'part_category' => 'Filters',
                'oem_part_number' => '17801-21050',
                'alternative_part_number' => 'DENSO DCF146',
                'brand' => 'Toyota Genuine',
                'description' => 'Air filter for 1NZ-FXE hybrid engine. Replace every 15000km.',
            ],
            [
                'vehicle_make' => 'Toyota', 'vehicle_model' => 'Aqua',
                'vehicle_year_from' => 2012, 'vehicle_year_to' => 2021,
                'part_name' => 'Spark Plug', 'part_category' => 'Spark Plugs',
                'oem_part_number' => '90919-01249',
                'alternative_part_number' => 'NGK FK20HBR11',
                'brand' => 'Toyota Genuine',
                'description' => 'Spark plug for 1NZ-FXE hybrid engine. Replace every 30000km.',
            ],
            [
                'vehicle_make' => 'Toyota', 'vehicle_model' => 'Aqua',
                'vehicle_year_from' => 2012, 'vehicle_year_to' => 2021,
                'part_name' => 'Front Brake Pads', 'part_category' => 'Brakes',
                'oem_part_number' => '04465-52360',
                'alternative_part_number' => 'AKEBONO AN-4WK',
                'brand' => 'Toyota Genuine',
                'description' => 'Front brake pad set for hybrid. Inspect every 20000km.',
            ],
        ];

        DB::table('parts')->insert($parts);
    }
}