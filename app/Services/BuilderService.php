<?php

namespace App\Services;

use App\Models\HeaderBuilder;
use App\Models\FooterBuilder;
use App\Models\HomepageBuilder;
use Illuminate\Support\Facades\Cache;

class BuilderService
{
    public function buildHeader(array $data): HeaderBuilder
    {
        return HeaderBuilder::updateOrCreate(
            ['id' => $data['id'] ?? null],
            $data
        );
    }

    public function buildFooter(array $data): FooterBuilder
    {
        return FooterBuilder::updateOrCreate(
            ['id' => $data['id'] ?? null],
            $data
        );
    }

    public function buildHomepage(array $sections): HomepageBuilder
    {
        $homepage = HomepageBuilder::updateOrCreate(
            ['is_active' => true],
            ['sections' => $sections, 'is_active' => true]
        );

        Cache::forget('homepage_sections');
        return $homepage;
    }

    public function renderSection(string $type, array $data): ?string
    {
        $sectionMap = [
            'hero' => 'components.sections.hero',
            'featured_products' => 'components.sections.featured-products',
            'categories' => 'components.sections.categories',
            'banner' => 'components.sections.banner',
            'products_grid' => 'components.sections.products-grid',
            'testimonials' => 'components.sections.testimonials',
            'brands' => 'components.sections.brands',
            'newsletter' => 'components.sections.newsletter',
        ];

        $view = $sectionMap[$type] ?? null;
        if (!$view) {
            return null;
        }

        return view($view, $data)->render();
    }

    public function getHomepageSections(): array
    {
        return Cache::remember('homepage_sections', 3600, function () {
            $homepage = HomepageBuilder::where('is_active', true)->first();
            return $homepage?->sections ?? [];
        });
    }
}
