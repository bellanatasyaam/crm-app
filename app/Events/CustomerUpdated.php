<?php

namespace App\Events;

use App\Models\Customer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;


class CustomerUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $customer;
    public $message;

    public function __construct(Customer $customer, $message)
    {
        $this->customer = $customer;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('customers');
    }

    public function broadcastAs()
    {
        return 'customer.updated';
    }
}