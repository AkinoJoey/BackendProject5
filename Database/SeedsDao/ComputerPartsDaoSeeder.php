<?php

namespace Database\SeedsDao;

require_once 'vendor/autoload.php';

use Database\DataAccess\DAOFactory;
use Models\ComputerPart;
use Faker\Factory;

class ComputerPartsDaoSeeder{

    public function seed(): void{
        $partDao = DAOFactory::getComputerPartDAO();
        $part = $this->createDummyComputerPart();
        $partDao->create($part);
    }

    public function createDummyComputerPart() : ComputerPart {
        $faker = Factory::create();

        $part = new ComputerPart(
            $faker->word(),
            $faker->randomElement(['CPU', 'GPU', 'SSD', 'HDD', 'RAM']),
            $faker->company(),
            null,
            $faker->numerify(),
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
        );

        return $part;
    }

}