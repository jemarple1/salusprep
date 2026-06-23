<?php

use App\Support\PageSeo;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Allow: /',
        'Disallow: /admin',
        'Disallow: /*/unlock',
        'Disallow: /*/exam/*/paywall',
        '',
        'Sitemap: '.url('/sitemap.xml'),
    ];

    return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->name('robots');

Route::get('/sitemap.xml', function () {
    return response()
        ->view('sitemap', ['entries' => PageSeo::sitemapEntries()])
        ->header('Content-Type', 'application/xml; charset=UTF-8');
})->name('sitemap');
