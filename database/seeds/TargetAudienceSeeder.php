<?php

use App\Models\TargetAudience;
use Illuminate\Database\Seeder;

class TargetAudienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        create(TargetAudience::class, [], 10);
    }
}
