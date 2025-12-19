<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Order Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration untuk order/transaction system
    |
    */

    // Order Number Prefix
    'order_number_prefix' => env('ORDER_NUMBER_PREFIX', 'ORD'),
    
    // Payment deadline (dalam jam)
    'payment_deadline_hours' => 24,
    
    // Delivery Status IDs
    'delivery_status' => [
        'pending'       => 1,
        'processing'    => 2,
        'shipped'       => 3,
        'delivered'     => 4,
        'cancelled'     => 5,
        'returned'      => 6,
    ],
    
    // Return Status
    'return_status' => [
        'pending'       => 'Pending',
        'approved'      => 'Disetujui',
        'rejected'      => 'Ditolak',
        'processing'    => 'Diproses',
        'completed'     => 'Selesai',
    ],
    
    // Refund Status
    'refund_status' => [
        'pending'       => 'Pending',
        'processing'    => 'Diproses',
        'completed'     => 'Selesai',
        'failed'        => 'Gagal',
    ],
    
    // Notification Priority
    'notification_priority' => [
        'low'       => 'low',
        'normal'    => 'normal',
        'high'      => 'high',
        'urgent'    => 'urgent',
    ],
    
];
