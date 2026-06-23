<?php

namespace Tests\Feature;

use App\Support\CertificationLevel;
use App\Support\PageSeo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_emt_basic_landing_page_has_search_friendly_meta_tags(): void
    {
        $this->get('/emt-basic')
            ->assertOk()
            ->assertSee('<title>NREMT® EMT-Basic Prep — Adaptive Tests, Flashcards &amp; More | SalusPrep</title>', false)
            ->assertSee('meta name="description"', false)
            ->assertSee('Prepare for the NREMT® EMT-Basic exam with adaptive practice tests', false)
            ->assertSee('rel="canonical" href=', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('application/ld+json', false);
    }

    public function test_nclex_pn_landing_page_uses_nclex_pn_mark_in_title(): void
    {
        $this->get('/nclex-pn')
            ->assertOk()
            ->assertSee('<title>NCLEX-PN™ Practical Nurse Prep — Adaptive Tests, Flashcards &amp; More | SalusPrep</title>', false)
            ->assertSee('NCLEX-PN™ Practical Nurse prep with adaptive practice tests', false);
    }

    public function test_paramedic_landing_page_uses_nremt_mark_in_title(): void
    {
        $this->get('/paramedic')
            ->assertOk()
            ->assertSee('NREMT® Paramedic Prep — Adaptive Tests, Flashcards &amp; More | SalusPrep', false);
    }

    public function test_robots_txt_and_sitemap_are_available(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Sitemap:')
            ->assertSee('Disallow: /admin');

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<loc>'.url('/emt-basic').'</loc>', false)
            ->assertSee('<loc>'.url('/nclex-pn').'</loc>', false);
    }

    public function test_certification_level_seo_helpers_include_exam_marks(): void
    {
        $this->assertSame(
            'NREMT® EMT-Basic Prep — Adaptive Tests, Flashcards & More',
            CertificationLevel::seoTitle(CertificationLevel::EMT_BASIC),
        );

        $this->assertSame(
            'NCLEX-PN™ Practical Nurse Prep — Adaptive Tests, Flashcards & More',
            CertificationLevel::seoTitle(CertificationLevel::NCLEX_PN),
        );

        $this->assertStringContainsString('NREMT®', PageSeo::platformPageTitle(CertificationLevel::PARAMEDIC, 'Test Center'));
    }
}
