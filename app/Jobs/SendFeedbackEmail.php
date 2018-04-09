<?php

namespace App\Jobs;

use App\Mail\FeedbackMail;
use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendFeedbackEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $feedback;

    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    public function handle()
    {
        if (!env('MAIL_FEEBDACK_TO')) {
            abort(500, 'Missed MAIL_FEEBDACK_TO parameter in env!');
        }
        Mail::to(env('MAIL_FEEBDACK_TO'))->send(new FeedbackMail($this->feedback));
    }
}
