<?php

namespace App\Mail;

use App\Models\Batch;
use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyAttendanceReport extends Mailable
{
    use Queueable, SerializesModels;


    protected $batch;
    protected $mainGroups;
    protected $electiveGroups;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Batch $batch, $mainGroups, $electiveGroups)
    {
        $this->batch = $batch;
        $this->mainGroups = $mainGroups;
        $this->electiveGroups = $electiveGroups;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('layouts.email.dailyAttendanceReport')->with([
            'batch' => $this->batch,
            'mainGroups' => $this->mainGroups,
            'electiveGroups' => $this->electiveGroups
        ]);
    }
}