<?php

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

abstract class BaseSeeder extends Seeder
{
    /** @var \Faker\Generator $faker */
    protected $faker;
    /** @var array */
    protected $users;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->users = User::query()->pluck('id')->toArray();
    }

    abstract public function run();

    /**
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        return $this->faker;
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        return $this->users;
    }

}
