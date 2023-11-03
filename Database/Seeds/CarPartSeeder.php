<?php

namespace Database\Seeds;

require_once 'vendor/autoload.php';

use Database\AbstractSeeder;
use Faker\Factory;

class CarPartSeeder extends AbstractSeeder
{

    protected ?string $tableName = 'car_parts';

    // TODO: tableColumns配列を割り当ててください。
    protected array $tableColumns = [
        [
            'data_type' => 'int',
            'column_name' => 'carID'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'name'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'description'
        ],
        [
            'data_type' => 'float',
            'column_name' => 'price'
        ],
        [
            'data_type' => 'int',
            'column_name' => 'quantityInStock'
        ]
    ];

    public function createRowData(): array
    {
        $faker = Factory::create();
        $data = [];

        for ($i = 0; $i < 100000; $i++) {
            $row = [
                $faker->numberBetween(10, 1000),
                $faker->word(),
                $faker->text(),
                $faker->randomFloat(),
                $faker->randomNumber(1, true)
            ];

            $data[] = $row;
        }

        return $data;
    }
}
