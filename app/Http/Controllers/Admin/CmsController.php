<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CmsPageStoreRequest;
use App\Http\Requests\CmsPageUpdateRequest;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CmsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('settings.cms');

        $query = CmsPage::query();

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('title_bn', 'like', '%' . $request->search . '%');
        }

        if ($request->has('page_type') && $request->page_type) {
            $query->where('page_type', $request->page_type);
        }

        $data = [
            'title' => 'CMS Pages',
            'page_title' => 'Content Management',
            'pages' => $query->orderBy('position')->paginate(15),
            'pageTypes' => CmsPage::PAGE_TYPES,
        ];

        return view('admin.cms.index', $data);
    }

    public function create()
    {
        $this->authorize('settings.cms');

        $data = [
            'title' => 'Create CMS Page',
            'page_title' => 'Create New Page',
            'pageTypes' => CmsPage::PAGE_TYPES,
        ];

        return view('admin.cms.create', $data);
    }

    public function store(CmsPageStoreRequest $request)
    {
        $this->authorize('settings.cms');

        try {
            $data = $request->only([
                'title', 'title_bn', 'content', 'content_bn', 
                'excerpt', 'excerpt_bn', 'page_type', 'status'
            ]);

            if (!$request->slug) {
                $data['slug'] = Str::slug($request->title);
            }

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('cms', 'public');
            }

            $data['icon'] = $request->icon;
            $data['position'] = $request->position ?? 0;
            $data['meta_title'] = $request->meta_title ?? $request->title;
            $data['meta_description'] = $request->meta_description;
            $data['meta_keywords'] = $request->meta_keywords;

            CmsPage::create($data);

            Cache::flush();

            Log::info('CMS Page created', ['title' => $request->title, 'created_by' => auth()->id()]);

            return redirect()->route('admin.cms.index')->with('success', 'CMS Page created successfully.');
        } catch (\Exception $e) {
            Log::error('CMS Page creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to create CMS page: ' . $e->getMessage())->withInput();
        }
    }

    public function show(CmsPage $cms)
    {
        $this->authorize('settings.cms');

        $data = [
            'title' => 'View CMS Page',
            'page_title' => $cms->title,
            'page' => $cms,
        ];

        return view('admin.cms.show', $data);
    }

    public function edit(CmsPage $cms)
    {
        $this->authorize('settings.cms');

        $data = [
            'title' => 'Edit CMS Page',
            'page_title' => 'Edit: ' . $cms->title,
            'page' => $cms,
            'pageTypes' => CmsPage::PAGE_TYPES,
        ];

        return view('admin.cms.edit', $data);
    }

    public function update(CmsPageUpdateRequest $request, CmsPage $cms)
    {
        $this->authorize('settings.cms');

        try {
            $data = $request->only([
                'title', 'title_bn', 'slug', 'content', 'content_bn',
                'excerpt', 'excerpt_bn', 'page_type', 'status'
            ]);

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($cms->image) {
                    \Storage::disk('public')->delete($cms->image);
                }
                $data['image'] = $request->file('image')->store('cms', 'public');
            }

            $data['icon'] = $request->icon;
            $data['position'] = $request->position ?? 0;
            $data['meta_title'] = $request->meta_title ?? $request->title;
            $data['meta_description'] = $request->meta_description;
            $data['meta_keywords'] = $request->meta_keywords;

            $cms->update($data);

            Cache::flush();

            Log::info('CMS Page updated', ['id' => $cms->id, 'title' => $cms->title, 'updated_by' => auth()->id()]);

            return redirect()->route('admin.cms.index')->with('success', 'CMS Page updated successfully.');
        } catch (\Exception $e) {
            Log::error('CMS Page update failed', ['id' => $cms->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update CMS page: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(CmsPage $cms)
    {
        $this->authorize('settings.cms');

        try {
            // Delete image if exists
            if ($cms->image) {
                \Storage::disk('public')->delete($cms->image);
            }

            Log::info('CMS Page deleted', ['id' => $cms->id, 'title' => $cms->title, 'deleted_by' => auth()->id()]);

            $cms->delete();
            Cache::flush();

            return redirect()->route('admin.cms.index')->with('success', 'CMS Page deleted successfully.');
        } catch (\Exception $e) {
            Log::error('CMS Page deletion failed', ['id' => $cms->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to delete CMS page.');
        }
    }

    public function quickEdit(Request $request, CmsPage $cms)
    {
        $this->authorize('settings.cms');

        $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'content_bn' => 'nullable|string',
        ]);

        $cms->update($request->only(['title', 'title_bn', 'content', 'content_bn']));
        Cache::flush();

        return response()->json(['success' => true, 'message' => 'Page updated successfully.']);
    }
}
