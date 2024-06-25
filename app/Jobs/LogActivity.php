<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Phattarachai\LineNotify\Facade\Line;

class LogActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $name;
    protected $payload;
    protected $notify;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, string $name, array $payload = [], bool $notify = true)
    {
        $this->user = $user;
        $this->name = $name;
        $this->payload = $payload;
        $this->notify = $notify;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $activity = $this->user->activities()->create([
            'name' => $this->name,
            'payload' => json_encode($this->payload)
        ]);

        if ($activity && $this->notify) {
            try {
                Line::setToken($this->user->lineToken)
                    ->send(trans("activity.{$this->name}"));
            } catch (\Throwable $th) {
                // Handle exception
            }
        }
    }
}
