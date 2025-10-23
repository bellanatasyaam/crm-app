<?php

namespace App\Events;

use App\Models\Company;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;


class CompanyUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $company;
    public $message;

    public function __construct(Company $company, $message)
    {
        $this->company = $company;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('companies');
    }

    public function broadcastAs()
    {
        return 'company.updated';
    }
}