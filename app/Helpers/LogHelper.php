<?php

namespace App\Helpers;

use App\Models\Log;

class LogHelper
{
    public static function add($customerId, $activity, $type = null, $detail = null)
    {
        // Jika detail berupa array, filter token & method
        if (is_array($detail)) {
            unset($detail['_token'], $detail['_method']);
        }

        // Kalau detail berupa JSON string, decode dulu supaya bisa difilter
        if (is_string($detail)) {
            $decoded = json_decode($detail, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                unset($decoded['_token'], $decoded['_method']);
                $detail = json_encode($decoded);
            }
        }

        Log::create([
            'customer_id'     => $customerId,
            'user_id'         => auth()->id(),
            'activity'        => $activity,
            'activity_type'   => $type,
            'activity_detail' => $detail,
        ]);
    }
}
