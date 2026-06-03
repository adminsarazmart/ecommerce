<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Banner;
use App\Models\Slider;
use App\Models\Menu;
use App\Services\CmsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CmsController extends Controller
{
    protected CmsService $cmsService;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
    }

    public function pages()
    {
        $pages = Page::orderBy('title')->get();

        return Inertia::render('Admin/CMS/Pages', ['pages' => $pages]);
    }

    public function pagesStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'layout' => 'nullable|string|max:100',
        ]);

        try {
            $page = Page::create($validated);

            activity()->performedOn($page)->causedBy(auth()->user())->log('Page created');

            return redirect()->route('admin.cms.pages')->with('success', 'Page created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function pagesUpdate(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'layout' => 'nullable|string|max:100',
        ]);

        try {
            $page->update($validated);

            activity()->performedOn($page)->causedBy(auth()->user())->log('Page updated');

            return redirect()->route('admin.cms.pages')->with('success', 'Page updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function pagesDestroy(Page $page)
    {
        try {
            $page->delete();

            return redirect()->route('admin.cms.pages')->with('success', 'Page deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function banners()
    {
        $banners = Banner::orderBy('sort_order')->get();

        return Inertia::render('Admin/CMS/Banners', ['banners' => $banners]);
    }

    public function bannersStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:hero,promotional,sidebar',
            'image' => 'nullable|image|max:2048',
            'link' => 'nullable|url|max:255',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $banner = Banner::create($validated);

            if ($request->hasFile('image')) {
                $banner->addMedia($request->file('image'))->toMediaCollection('banners');
            }

            activity()->performedOn($banner)->causedBy(auth()->user())->log('Banner created');

            return redirect()->route('admin.cms.banners')->with('success', 'Banner created');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function bannersDestroy(Banner $banner)
    {
        try {
            $banner->delete();

            return redirect()->route('admin.cms.banners')->with('success', 'Banner deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sliders()
    {
        $sliders = Slider::orderBy('sort_order')->get();

        return Inertia::render('Admin/CMS/Sliders', ['sliders' => $sliders]);
    }

    public function slidersStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:255',
            'button_text' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $slider = Slider::create($validated);

            if ($request->hasFile('image')) {
                $slider->addMedia($request->file('image'))->toMediaCollection('sliders');
            }

            activity()->performedOn($slider)->causedBy(auth()->user())->log('Slider created');

            return redirect()->route('admin.cms.sliders')->with('success', 'Slider created');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function slidersDestroy(Slider $slider)
    {
        try {
            $slider->delete();

            return redirect()->route('admin.cms.sliders')->with('success', 'Slider deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function menus()
    {
        $menus = Menu::with('children')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Admin/CMS/Menus', ['menus' => $menus]);
    }

    public function menusStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:menus,id',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            Menu::create($validated);

            return redirect()->route('admin.cms.menus')->with('success', 'Menu item created');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function menusReorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menus,id',
            'items.*.sort_order' => 'required|integer|min:0',
            'items.*.parent_id' => 'nullable|exists:menus,id',
        ]);

        try {
            foreach ($request->items as $item) {
                Menu::where('id', $item['id'])->update([
                    'sort_order' => $item['sort_order'],
                    'parent_id' => $item['parent_id'] ?? null,
                ]);
            }

            return redirect()->back()->with('success', 'Menu reordered');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function menusDestroy(Menu $menu)
    {
        try {
            $menu->children()->delete();
            $menu->delete();

            return redirect()->route('admin.cms.menus')->with('success', 'Menu item deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
