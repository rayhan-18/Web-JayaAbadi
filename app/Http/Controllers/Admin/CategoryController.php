<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index()
    {
        $categories = Category::withCount('products')
            ->paginate(10);

        return view('admin.category.index', compact('categories'));
    }

    // =========================
    // CREATE PAGE
    // =========================
    public function create()
    {
        return view('admin.category.create');
    }

    // =========================
    // STORE DATA
    // =========================
    public function store(Request $request)
    {
        
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'nullable',
            'status' => 'required',
            'image' => 'nullable'
        ]);
        Category::create([
            'name' => $request->nama,
            'description' => $request->deskripsi,
            'image' => $request->image ?? null,
            'is_active' => $request->status === 'Aktif' ? true : false,
            'slug' => Str::slug($request->nama),
        ]);
        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    // =========================
    // EDIT PAGE
    // =========================
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.category.edit', compact('category'));
    }

    // =========================
    // UPDATE DATA
    // =========================
    public function update(Request $request, $id)
    {
    $request->validate([
        'nama'        => 'required|string|max:255',
        'deskripsi'   => 'nullable|string',
        'image'       => 'nullable|string',
        'status'      => 'required|string',
    ]);

    $category = Category::findOrFail($id);

    $category->update([
        'name'        => $request->nama,
        'slug'        => Str::slug($request->nama),
        'description' => $request->deskripsi,
        'image'       => $request->image,
        'is_active'   => $request->status === 'Aktif' ? true : false,
    ]);

    return redirect()
        ->route('admin.category.index')
        ->with('success', 'Kategori berhasil diupdate');
    }
    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}