<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('gallery.manage');

        $query = Album::query();

        if ($request->has('type') && $request->type) {
            $query->where('album_type', $request->type);
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $albums = $query->withCount('images')->orderBy('created_at', 'desc')->paginate(12);

        return view('admin.gallery.index', [
            'title' => 'Gallery',
            'page_title' => 'Gallery Management',
            'albums' => $albums,
        ]);
    }

    public function create()
    {
        $this->authorize('gallery.manage');

        return view('admin.gallery.create', [
            'title' => 'Create Album',
            'page_title' => 'Create New Album',
            'album' => null,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('gallery.manage');

        $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'album_type' => 'required|in:photo,video',
        ]);

        Album::create([
            'title' => $request->title,
            'title_bn' => $request->title_bn,
            'description' => $request->description,
            'album_type' => $request->album_type,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Album created successfully.');
    }

    public function show(Album $album)
    {
        $this->authorize('gallery.manage');

        $album->load('images');

        return view('admin.gallery.show', [
            'title' => 'Album Details',
            'page_title' => $album->title,
            'album' => $album,
        ]);
    }

    public function edit(Album $album)
    {
        $this->authorize('gallery.manage');

        return view('admin.gallery.edit', [
            'title' => 'Edit Album',
            'page_title' => 'Edit: ' . $album->title,
            'album' => $album,
        ]);
    }

    public function update(Request $request, Album $album)
    {
        $this->authorize('gallery.manage');

        $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $album->update($request->only(['title', 'title_bn', 'description']));

        return redirect()->route('admin.gallery.show', $album)
            ->with('success', 'Album updated successfully.');
    }

    public function destroy(Album $album)
    {
        $this->authorize('gallery.manage');

        foreach ($album->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            if ($image->thumbnail_path && Storage::disk('public')->exists($image->thumbnail_path)) {
                Storage::disk('public')->delete($image->thumbnail_path);
            }
        }

        $album->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Album deleted successfully.');
    }

    public function toggleStatus(Album $album)
    {
        $this->authorize('gallery.manage');

        $album->update(['is_active' => !$album->is_active]);

        return redirect()->back()->with('success', 'Album status updated.');
    }

    public function uploadImages(Request $request, Album $album)
    {
        $this->authorize('gallery.manage');

        $request->validate([
            'images.*' => 'required|image|max:5120',
        ]);

        $type = $album->album_type;

        foreach ($request->file('images') as $file) {
            $path = $file->store('gallery/' . $album->id, 'public');

            GalleryImage::create([
                'album_id' => $album->id,
                'image_path' => $path,
                'type' => $type,
                'title' => $file->getClientOriginalName(),
            ]);
        }

        return redirect()->back()->with('success', 'Images uploaded successfully.');
    }

    public function addVideo(Request $request, Album $album)
    {
        $this->authorize('gallery.manage');

        $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'required|url',
        ]);

        GalleryImage::create([
            'album_id' => $album->id,
            'title' => $request->title,
            'video_url' => $request->video_url,
            'type' => 'video',
        ]);

        return redirect()->back()->with('success', 'Video added successfully.');
    }

    public function deleteImage(GalleryImage $image)
    {
        $this->authorize('gallery.manage');

        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        if ($image->thumbnail_path && Storage::disk('public')->exists($image->thumbnail_path)) {
            Storage::disk('public')->delete($image->thumbnail_path);
        }

        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    public function toggleFeatured(GalleryImage $image)
    {
        $this->authorize('gallery.manage');

        $image->update(['is_featured' => !$image->is_featured]);

        return redirect()->back()->with('success', 'Featured status updated.');
    }

    // Public methods
    public function publicIndex(Request $request)
    {
        $type = $request->get('type', 'photo');
        
        $albums = Album::active()
            ->ofType($type)
            ->withCount('images')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('public.gallery.index', [
            'title' => $type === 'photo' ? 'Photo Gallery' : 'Video Gallery',
            'albums' => $albums,
            'type' => $type,
        ]);
    }

    public function publicShow(Album $album)
    {
        if (!$album->is_active) {
            abort(404);
        }

        $album->load('images');

        return view('public.gallery.show', [
            'title' => $album->title,
            'album' => $album,
        ]);
    }
}
