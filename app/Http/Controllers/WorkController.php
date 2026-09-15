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
                'slug' => 'ruco-printing',
                'title' => 'RUCO Products, Inc.: Retiring an ActiveX Print Dependency',
                'summary' => 'Removing a browser-deprecation time bomb from the whole app in one deploy, with no page-by-page rewrite.',
                'tags' => ['Legacy browser dependency', 'JavaScript', 'ClassicASP'],
                'placeholder' => false,
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
