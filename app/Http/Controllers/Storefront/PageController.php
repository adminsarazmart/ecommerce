<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\CmsService;
use Inertia\Inertia;

class PageController extends Controller
{
    protected CmsService $cmsService;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
    }

    public function show($slug)
    {
        $page = $this->cmsService->getPage($slug);

        if (!$page) {
            abort(404);
        }

        return Inertia::render('Page', [
            'page' => $page,
            'seo' => [
                'title' => $page->meta_title ?: $page->title,
                'description' => $page->meta_description,
            ],
        ]);
    }
}
