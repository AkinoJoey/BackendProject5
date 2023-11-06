<?php

namespace Database\Seeds;

require_once 'vendor/autoload.php';

use Database\AbstractSeeder;
use Faker\Factory;
use Carbon\Carbon;

class CarSeeder extends AbstractSeeder {

    protected ?string $tableName = 'cars';

    // TODO: tableColumns配列を割り当ててください。
    protected array $tableColumns = [
        [
            'data_type' => 'string',
            'column_name' => 'make'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'model'
        ],
        [
            'data_type' => 'int',
            'column_name' => 'year'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'color'
        ],
        [
            'data_type' => 'float',
            'column_name' => 'price'
        ],
        [
            'data_type' => 'float',
            'column_name' => 'mileage'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'transmission'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'engine'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'status'
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

        for ($i = 0; $i < 1000; $i++) {    
            $randomTimeStampCreated = mt_rand($min_year, $max_year);
            $randomTimeStampUpdated = mt_rand($randomTimeStampCreated, $max_year);

            $row = [
                $faker->word(),
                $faker->word(),
                (int)$faker->year(),
                $faker->colorName(),
                $faker->randomFloat(),
                $faker->randomFloat(),
                $faker->word(),
                $faker->word(),
                $faker->word(),
                Carbon::createFromTimestamp($randomTimeStampCreated)->toDateTime(),
                Carbon::createFromTimestamp($randomTimeStampUpdated)->toDateTime()
            ];

            $data[] = $row;
        }

        return $data;
    }
}