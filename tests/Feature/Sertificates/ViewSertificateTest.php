<?php

namespace Tests\Feature\Sertificates;

use App\Models\Course;
use App\Models\File;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewSertificateTest extends TestCase
{
    use RefreshDatabase;

    /** @var Course $course */
    private $course;

    public function setUp()
    {
        parent::setUp();

        $this->course       = create(Course::class);
    }

    /** @test */
    public function it_can_view_all_sertificates()
    {
        create(File::class, [
            'fileable_id'   => $this->course->id,
            'is_sertificate' => true
        ], 5);
        create(File::class, [
            'fileable_id'   => create(Course::class)->id,
            'is_sertificate' => false
        ], 4);

        $response = $this->signIn()
             ->getJson(route('sertificate.index'))
             ->assertStatus(200)
             ->json();

        $this->assertCount(5, $response['data']);
    }

    /** @test */
    public function it_can_not_view_all_sertificates_if_not_auth()
    {
        $this->getJson(route('sertificate.index'))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_view_all_sertificates_for_course()
    {
        create(File::class, [
            'fileable_id'   => $this->course->id,
            'is_sertificate' => true
        ], 5);
        create(File::class, [
            'fileable_id'   => create(Course::class)->id,
            'is_sertificate' => false
        ], 4);

        $response = $this->signIn()
            ->getJson(route('courses.sertificate.show', $this->course))
            ->assertStatus(200)
            ->json();

        $this->assertCount(5, $response);
    }

    /** @test */
    public function it_can_not_view_all_sertificates_for_course_if_not_auth()
    {
        $this->getJson(route('courses.sertificate.show', $this->course))
             ->assertStatus(401);
    }


}
