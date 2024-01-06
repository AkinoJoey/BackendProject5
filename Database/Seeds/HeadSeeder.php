<?php

namespace Database\Seeds;

use Database\Seeder;
use Faker\Factory as Faker;
use Models\ORM\Head;

class HeadSeeder implements Seeder
{

    public function seed(): void
    {
        $rows = $this->createRowData();

        foreach ($rows as $data) {
            Head::create($data);
        }
    }

    public function createRowData(): array
    {
        $faker = Faker::create();
        $rows = [];

        for ($i = 0; $i < 1000; $i++) {
            $rows[] = [
                'character_id'        => $i+1,
                'eye'       => $faker->numberBetween(1,100),
                'nose'      => $faker->numberBetween(1,100), 
                'chin'        => $faker->numberBetween(1,100),
                'hair'    => $faker->numberBetween(1,100),
                'eyebrows' => $faker->text,
                'hair_color'        => $faker->numberBetween(1, 100)
            ];
        }

        return $rows;
    }
}
