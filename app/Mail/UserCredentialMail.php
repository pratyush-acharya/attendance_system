<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCredentialMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $name;
    protected $roles;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, array $roles)
    {
        $this->name = $user->name;
        $this->email = $user->email;
        $this->roles = $roles;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('User Credentials')
                    ->view('layouts.email.userCredential')->with([
                        'name' => $this->name,
                        'email' => $this->email,
                        'roles' => $this->roles,
        ]);
    }
}
