<?php

use App\Models\Manager;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('manager_notification_channel_.{managerId}', function (Manager $user, $managerId) {
    return $user->id == $managerId;
});
