<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class WorkController extends Controller
{
    /**
     * Case study index data. Each non-placeholder entry's `slug` must have
     * a matching resources/views/work/{slug}.blade.php detail template —
     * show() resolves the view from the slug directly.
     */
    protected static function caseStudies(): array
    {
        return [
            [
                'slug' => 'ruco',
                'title' => 'RUCO Products, Inc.',
                'summary' => 'Retooling a 20 year old document management page.',
                'tags' => ['UX research', 'Internal tooling', 'ClassicASP / SQL Server'],
                'placeholder' => false,
            ],
            [
                'slug' => 'vantage',
                'title' => 'Vantage Finance, LLC',
                'summary' => 'Recreated markup of the auto-finance dispatch search dashboard, with interactive cycling filters.',
                'tags' => ['UX design', 'Internal tooling', 'Web components'],
                'placeholder' => false,
            ],
            [
                'slug' => null,
                'title' => 'Lorem Ipsum Dolor Sit Amet',
                'summary' => 'Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'tags' => ['Lorem', 'Ipsum'],
                'placeholder' => true,
            ],
            [
                'slug' => null,
                'title' => 'Ut Enim Ad Minim Veniam',
                'summary' => 'Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                'tags' => ['Dolor', 'Sit Amet'],
                'placeholder' => true,
            ],
            [
                'slug' => null,
                'title' => 'Duis Aute Irure Dolor',
                'summary' => 'In reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
                'tags' => ['Consectetur'],
                'placeholder' => true,
            ],
        ];
    }

    public function index(): View
    {
        return view('work.index', [
            'caseStudies' => static::caseStudies(),
        ]);
    }

    public function show(string $slug): View
    {
        $caseStudy = collect(static::caseStudies())
            ->firstWhere('slug', $slug);

        abort_unless($caseStudy, 404);

        return view("work.{$slug}", [
            'caseStudy' => $caseStudy,
        ]);
    }
}
