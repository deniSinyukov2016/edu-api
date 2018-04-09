<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\User::class           => \App\Policies\UserPolicy::class,
        \App\Models\Category::class       => \App\Policies\CategoryPolicy::class,
        \App\Models\Course::class         => \App\Policies\CoursePolicy::class,
        \App\Models\Module::class         => \App\Policies\ModulePolicy::class,
        \App\Models\Lesson::class         => \App\Policies\LessonPolicy::class,
        \App\Models\Test::class           => \App\Policies\TestPolicy::class,
        \App\Models\Feedback::class       => \App\Policies\FeedbackPolicy::class,
        \App\Models\Question::class       => \App\Policies\QuestionPolicy::class,
        \App\Models\Answer::class         => \App\Policies\AnswerPolicy::class,
        \App\Models\TypeLesson::class     => \App\Policies\TypeLessonPolicy::class,
        \App\Models\Event::class          => \App\Policies\EventPolicy::class,
        \App\Models\TypeEvent::class      => \App\Policies\TypeEventPolicy::class,
        \App\Models\TargetAudience::class => \App\Policies\TargetAudiencePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
