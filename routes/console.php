<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    $dateThreshold = Carbon::now()->subYear();

    $updated = Post::where('status', 'published')
        ->where('created_at', '<=', $dateThreshold)
        ->update(['status' => 'archived']);

    echo "Archived $updated posts.\n";
})->daily();
