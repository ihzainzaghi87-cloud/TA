<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // Melihat list
            new Middleware('permission:banners.index|banners.view', only: ['index' , 'show']),
            
            // FORM create + store
            new Middleware('permission:banners.create', only: ['create', 'store']),
            
            // FORM edit + update + show
            new Middleware('permission:banners.update', only: ['edit', 'update']),
            
            // Hapus banner
            new Middleware('permission:banners.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the banners.
     */
    public function index()
    {
        $banners = Banner::latest()->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean'
        ]);

        // Upload image
        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'image' => $imagePath,
            'title' => $request->title,
            'is_active' => $request->has('is_active') ? true : false
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner created successfully');
    }

    /**
     * Display the specified banner.
     */
    public function show(Banner $banner)
    {
        return view('admin.banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean'
        ]);

        // Update image if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            
            // Upload new image
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->title = $request->title;
        $banner->is_active = $request->has('is_active') ? true : false;
        $banner->save();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully');
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy(Banner $banner)
    {
        // Image will be deleted automatically by model's deleting event
        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted successfully');
    }

    /**
     * Toggle the is_active status of a banner.
     */
    public function toggleStatus(Banner $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();

        $status = $banner->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Banner {$status} successfully");
    }
}
