<?php

namespace App\Services;

use App\Models\Page;
use App\Models\Banner;
use App\Models\Slider;
use App\Models\Menu;
use Illuminate\Support\Facades\Cache;

class CmsService
{
    public function managePages(array $data): Page
    {
        return Page::updateOrCreate(
            ['slug' => $data['slug']],
            $data
        );
    }

    public function manageBanners(array $data): Banner
    {
        return Banner::create($data);
    }

    public function manageSliders(array $data): Slider
    {
        return Slider::create($data);
    }

    public function manageMenus(array $data): Menu
    {
        return Menu::updateOrCreate(
            ['slug' => $data['slug'] ?? $data['name']],
            $data
        );
    }

    public function getPage(string $slug): ?Page
    {
        return Cache::remember("page.{$slug}", 3600, function () use ($slug) {
            return Page::where('slug', $slug)->where('is_active', true)->first();
        });
    }

    public function getActiveBanners(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('active_banners', 3600, function () {
            return Banner::where('is_active', true)->orderBy('sort_order')->get();
        });
    }

    public function getActiveSliders(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('active_sliders', 3600, function () {
            return Slider::where('is_active', true)->orderBy('sort_order')->get();
        });
    }

    public function getMenus(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('menus', 3600, function () {
            return Menu::where('is_active', true)->with('children')->orderBy('sort_order')->get();
        });
    }
}
