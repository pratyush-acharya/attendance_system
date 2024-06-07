<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Feedback;
class FeedbackMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $name;
    protected $title;
    protected $description;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Feedback $feedback)
    {
        $this->name = $feedback->user->name;
        $this->title = $feedback->title;
        $this->description = $feedback->description;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Feedback On AMS')
                    ->view('layouts.email.feedback')->with([
                        'name' => $this->name,
                        'title' => $this->title,
                        'description' => $this->description,
                        
        ]);
    }
}
