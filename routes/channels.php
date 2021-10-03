<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('manager_notification_channel_.{managerId}', function ($user, $managerId) {
    return true;
});
