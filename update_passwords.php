<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Update ID 8
$u8 = User::find(8);
if ($u8) {
    $u8->password = Hash::make('keyGacor1');
    $u8->save();
    echo "✅ User ID 8 ({$u8->name}) - password updated to: keyGacor1\n";
} else {
    echo "❌ User ID 8 not found\n";
}

// Update ID 9
$u9 = User::find(9);
if ($u9) {
    $u9->password = Hash::make('ebyGacor2');
    $u9->save();
    echo "✅ User ID 9 ({$u9->name}) - password updated to: ebyGacor2\n";
} else {
    echo "❌ User ID 9 not found\n";
}
