<?php

namespace Tests\Unit;

use App\Models\Test;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class TestModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_not_create_test_if_not_exist_lesson_id()
    {
        $this->expectException(ModelNotFoundException::class);

        create(Test::class, ['lesson_id' => null]);
    }

    /** @test */
    public function it_can_not_create_test_with_count_attemps_less_zero()
    {
        $this->expectException(Exception::class);

        create(Test::class, ['count_attemps' => -1]);
    }

    /** @test */
    public function it_can_not_create_test_with_count_correct_less_zero()
    {
        $this->expectException(Exception::class);

        create(Test::class, ['count_correct' => -5]);
    }
}
