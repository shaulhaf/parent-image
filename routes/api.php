<?php

use Capitalc\AdvancedNovaMediaLibrary\Http\Controllers\DownloadMediaController;

Route::get('/download/{media}', [DownloadMediaController::class, 'show']);
