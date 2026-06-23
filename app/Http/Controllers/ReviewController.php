<?php

namespace App\Http\Controllers;

use App\Support\ReviewContent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $level = $request->attributes->get('certification_level');
        $query = $request->string('q')->trim()->toString();

        return view('review.index', [
            'concepts' => ReviewContent::search($level, $query !== '' ? $query : null),
            'query' => $query,
        ]);
    }

    public function show(Request $request, string $section, string $concept): View
    {
        $level = $request->attributes->get('certification_level');
        $meta = ReviewContent::find($level, $concept);
        abort_if($meta === null, 404);

        return view('review.show', [
            'concept' => $meta,
            'linkedExercise' => ReviewContent::linkedExercise($level, $meta),
            'pageMetaTitle' => \App\Support\PageSeo::platformPageTitle($level, $meta['title']),
            'pageMetaDescription' => $meta['excerpt'] ?? \App\Support\CertificationLevel::seoDescription($level),
        ]);
    }
}
