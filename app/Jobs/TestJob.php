<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct($userId)
    {
//        $this->user = User::query()->withWhereHas('bookings', function ($query){
//            $query->where('id', 41);
//        })->find($userId);

        $this->user = User::query()->with('bookings')->find($userId);

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        dd($this->user->bookings);
    }
}
