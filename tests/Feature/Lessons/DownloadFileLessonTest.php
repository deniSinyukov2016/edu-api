<?php
//
//namespace Tests\Feature\Lessons;
//
//use App\Models\File;
//use App\Models\Lesson;
//use Illuminate\Http\UploadedFile;
//use Illuminate\Support\Facades\Storage;
//use Tests\TestCase;
//use Illuminate\Foundation\Testing\WithFaker;
//use Illuminate\Foundation\Testing\RefreshDatabase;
//
//class DownloadFileLessonTest extends TestCase
//{
//    use RefreshDatabase;
//    use WithFaker;
//
//    /** @var Lesson $lesson */
//    private $lesson;
//    /** @var UploadedFile $image */
//    private $file;
//
//    public function setUp()
//    {
//        parent::setUp();
//        $this->lesson       = create(Lesson::class);
//
//        /** @var UploadedFile $file */
//        $this->file = UploadedFile::fake()->create("document.pdf");
//
//        $this->lesson->files()->create([
//            'fileable_id'       => $this->lesson->id,
//            'fileable_type'     => Lesson::class,
//            'file'              => $this->lesson->getFileDir() . $this->file->hashName(),
//            'type'              => $this->faker->mimeType,
//            'size'              => $this->faker->randomDigitNotNull,
//            'original_name'     => $this->file->getClientOriginalName()
//        ]);
//
//    }
//
////    public function it_can_download_file_for_lesson()
////    {
////
////        Storage::fake('local');
////        $this->file->store($this->lesson->getFileDir());
////
////        Storage::disk('local')->assertExists($this->lesson->getFileDir() . $this->file->hashName());
////
////        $this->assertDatabaseHas('files', [
////            'fileable_id'       => $this->lesson->id,
////            'fileable_type'     => Lesson::class,
////            'file'              => $this->lesson->getFileDir() . $this->file->hashName()
////        ]);
////////
//////        dd($this->signIn()
//////            ->getJson(route('lessons.download.file', [
//////                $this->lesson,
//////                $this->lesson->files()->first()->id
//////            ]))->json());
////    }
//}
