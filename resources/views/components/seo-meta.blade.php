@props([
    'pageMetaTitle' => null,
    'pageMetaDescription' => null,
])

@php
    use App\Support\PageSeo;

    $metaTitleSection = trim($__env->yieldContent('meta_title'));
    $metaDescriptionSection = trim($__env->yieldContent('meta_description'));
    $sharedMetaTitle = trim((string) ($pageMetaTitle ?? ''));
    $sharedMetaDescription = trim((string) ($pageMetaDescription ?? ''));
    $metaRobotsSection = trim($__env->yieldContent('meta_robots'));
    $legacyTitleSection = trim($__env->yieldContent('title'));

    $documentTitle = $metaTitleSection !== ''
        ? PageSeo::formatDocumentTitle($metaTitleSection)
        : ($sharedMetaTitle !== ''
            ? PageSeo::formatDocumentTitle($sharedMetaTitle)
            : (($platformSeo['title'] ?? null)
                ?: ($legacyTitleSection !== ''
                    ? PageSeo::formatDocumentTitle($legacyTitleSection)
                    : PageSeo::formatDocumentTitle('NREMT® & NCLEX-PN™ Exam Prep — '.PageSeo::SITE_TAGLINE))));

    $metaDescription = $metaDescriptionSection !== ''
        ? $metaDescriptionSection
        : ($sharedMetaDescription !== ''
            ? $sharedMetaDescription
            : ($platformSeo['description'] ?? PageSeo::siteDescription()));

    $metaRobots = $metaRobotsSection !== ''
        ? $metaRobotsSection
        : ($platformSeo['robots'] ?? 'index, follow');

    $canonicalUrl = PageSeo::canonicalUrl(request());
@endphp

<title>{{ $documentTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="robots" content="{{ $metaRobots }}">
<link rel="canonical" href="{{ $canonicalUrl }}">

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ PageSeo::SITE_NAME }}">
<meta property="og:title" content="{{ $documentTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ $documentTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">

@stack('structured_data')
