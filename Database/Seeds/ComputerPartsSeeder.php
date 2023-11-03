<?php

namespace Database\Seeds;

require_once 'vendor/autoload.php';
use Database\AbstractSeeder;
use Faker\Factory;

class ComputerPartsSeeder extends AbstractSeeder
{
    protected ?string $tableName = 'computer_parts';
    protected array $tableColumns = [
        [
            'data_type' => 'string',
            'column_name' => 'name'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'type'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'brand'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'model_number'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'release_date'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'description'
        ],
        [
            'data_type' => 'int',
            'column_name' => 'performance_score'
        ],
        [
            'data_type' => 'float',
            'column_name' => 'market_price'
        ],
        [
            'data_type' => 'float',
            'column_name' => 'rsm'
        ],
        [
            'data_type' => 'float',
            'column_name' => 'power_consumptionw'
        ],
        [
            'data_type' => 'float',
            'column_name' => 'lengthm'
        ],
        [
            'data_type' => 'float',
            'column_name' => 'widthm'
        ],
        [
            'data_type' => 'float',
            'column_name' => 'heightm'
        ],
        [
            'data_type' => 'int',
            'column_name' => 'lifespan'
        ]
    ];

    public function createRowData(): array
    {
        $faker = Factory::create();
        $data = [];

        for($i = 0; $i < 10000; $i++){
            $row = [
                $faker->word(),
                $faker->randomElement(['CPU', 'GPU', 'SSD', 'HDD', 'RAM']),
                $faker->company(),
                $faker->swiftBicNumber(),
                $faker->date(),
                $faker->text(),
                $faker->numberBetween(10, 100),
                $faker->randomFloat(2),
                $faker->randomFloat(2),
                $faker->randomFloat(),
                $faker->randomFloat(),
                $faker->randomFloat(2),
                $faker->randomFloat(3),
                $faker->numberBetween(1, 10)
            ];

            $data[] = $row;
        }

        return $data;
    }
}
