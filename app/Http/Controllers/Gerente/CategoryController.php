<?php

namespace App\Http\Controllers\Gerente;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * CRUD de Categorias (área do Gerente).
 */
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->get();

        return view('gerente.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('gerente.categories.form', ['category' => new Category()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        Category::create($data);

        return redirect()
            ->route('gerente.categories.index')
            ->with('success', 'Categoria cadastrada com sucesso.');
    }

    public function edit(Category $category)
    {
        return view('gerente.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validateData($request);
        $category->update($data);

        return redirect()
            ->route('gerente.categories.index')
            ->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy(Category $category)
    {
        // Impede exclusão se houver produtos vinculados
        if ($category->products()->exists()) {
            return back()->withErrors(['delete' => 'Não é possível excluir: existem produtos nesta categoria.']);
        }

        $category->delete();

        return back()->with('success', 'Categoria excluída com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'icon'       => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'active'     => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Informe o nome da categoria.',
        ]) + ['active' => $request->boolean('active'), 'sort_order' => $request->integer('sort_order')];
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
