<?php

namespace Database\Seeds;

require_once 'vendor/autoload.php';

use Database\AbstractSeeder;
use Faker\Factory;
use Carbon\Carbon;

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
        ],
        [
            'data_type' => 'DateTime',
            'column_name' => 'created_at'
        ],
        [
            'data_type' => 'DateTime',
            'column_name' => 'updated_at'
        ]
    ];

    public function createRowData(): array
    {
        $faker = Factory::create();
        $data = [];

        $min_year = strtotime('1960-01-01');
        $max_year = strtotime('2024-12-31');

        for ($i = 0; $i < 100000; $i++) {
            $randomTimeStampCreated = mt_rand($min_year, $max_year);
            $randomTimeStampUpdated = mt_rand($randomTimeStampCreated, $max_year);

            $row = [
                $faker->numberBetween(1, 1000),
                $faker->word(),
                $faker->text(),
                $faker->randomFloat(),
                $faker->randomNumber(1, true),
                Carbon::createFromTimestamp($randomTimeStampCreated)->toDateTime(),
                Carbon::createFromTimestamp($randomTimeStampUpdated)->toDateTime()
            ];

            $data[] = $row;
        }

        return $data;
    }
}
