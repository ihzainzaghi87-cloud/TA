<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;

class ArticleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // View index
            (new ControllerMiddleware('permission:articles.index'))->only(['index']),
            // FORM create + store
            (new ControllerMiddleware('permission:articles.create'))->only(['create','store']),
            // FORM edit + update
            (new ControllerMiddleware('permission:articles.update'))->only(['edit','update']),
            // Hapus article
            (new ControllerMiddleware('permission:articles.delete'))->only(['destroy']),
            // toggle publish status
            (new ControllerMiddleware('permission:articles.publish|articles.update'))->only(['togglePublish']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi tanpa content dulu
        $validated = $request->validate([
            'title' => 'required|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_published' => 'boolean'
        ]);

        // Ambil content dari Trix field
        $trixContent = $request->input('article-trixFields.content');
        
        // Validasi content secara manual
        if (empty($trixContent)) {
            return back()->withErrors(['content' => 'The content field is required.'])->withInput();
        }

        // Tambahkan content ke validated data
        $validated['content'] = $trixContent;

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('articles/thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        // Auto set published_at jika artikel dipublikasikan
        if ($request->has('is_published') && $request->is_published) {
            $validated['published_at'] = now();
        }

        // Create article
        $article = Article::create($validated);

        // Save Trix rich text relation
        if ($trixContent) {
            $article->trixRichText()->create([
                'field' => 'content',
                'content' => $trixContent
            ]);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('admin.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        // Validasi tanpa content dulu
        $validated = $request->validate([
            'title' => 'required|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_published' => 'boolean'
        ]);

        // Ambil content dari Trix field
        $trixContent = $request->input('article-trixFields.content');
        
        // Validasi content secara manual
        if (empty($trixContent)) {
            return back()->withErrors(['content' => 'The content field is required.'])->withInput();
        }

        // Tambahkan content ke validated data
        $validated['content'] = $trixContent;

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($article->thumbnail && \Storage::disk('public')->exists($article->thumbnail)) {
                \Storage::disk('public')->delete($article->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('articles/thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        // Set published_at jika status berubah menjadi published
        if ($request->has('is_published') && $request->is_published && !$article->is_published) {
            $validated['published_at'] = now();
        }

        // Unset published_at jika status berubah menjadi draft
        if ($request->has('is_published') && !$request->is_published) {
            $validated['published_at'] = null;
        }

        // Update article
        $article->update($validated);

        // Update Trix rich text
        $article->trixRichText()->updateOrCreate(
            ['field' => 'content'],
            ['content' => $trixContent]
        );

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // Hapus thumbnail jika ada
        if ($article->thumbnail && \Storage::disk('public')->exists($article->thumbnail)) {
            \Storage::disk('public')->delete($article->thumbnail);
        }

        // Hapus rich text content dan attachments
        $article->trixRichText()->delete();
        
        // Hapus file attachments dari storage
        if ($article->trixAttachments->isNotEmpty()) {
            $article->trixAttachments->each->purge();
        }

        // Hapus artikel
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article deleted successfully!');
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(Article $article)
    {
        $article->update([
            'is_published' => !$article->is_published,
            'published_at' => !$article->is_published ? now() : null
        ]);

        $status = $article->is_published ? 'published' : 'drafted';

        return redirect()->back()
            ->with('success', "Article {$status} successfully!");
    }
}
