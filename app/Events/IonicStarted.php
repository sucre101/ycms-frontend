<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class IonicStarted implements ShouldBroadcast
{
  use InteractsWithSockets, SerializesModels;

  private $user;
  public $host;

  public function __construct(User $user, $host)
  {
    $this->user = $user;
    $this->host = $host;
  }

  public function broadcastOn()
  {
    return new PrivateChannel('user.'.$this->user->id);
  }

  public function broadcastAs()
  {
    return 'ionic.started';
  }
}