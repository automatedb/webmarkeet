<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UnSubscribePlan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var  User */
    private $id;

    /** @var bool  */
    private $delete;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $id, bool $delete = false)
    {
        $this->id = $id;
        $this->delete = $delete;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userModel = new User();

        $user = $userModel->where('id', $this->id)->first();
        Log::info($user);

        if(!empty($user)) {
            if($user->subscribed('monthly', 'RT0001')) {
                $user->subscription('monthly')->cancel();
            }

            if($this->delete) {
                $user->delete();
            }
        }
    }
}
