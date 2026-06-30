<?php

namespace App\Http\Controllers\Gerente;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * CRUD de Produtos (área do Gerente).
 */
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('name')->paginate(15);

        return view('gerente.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('gerente.products.form', [
            'product'    => new Product(),
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['image'] = $this->handleImage($request);

        Product::create($data);

        return redirect()
            ->route('gerente.products.index')
            ->with('success', 'Produto cadastrado com sucesso.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('gerente.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request);

        $newImage = $this->handleImage($request);
        if ($newImage !== null) {
            $data['image'] = $newImage;
        }

        $product->update($data);

        return redirect()
            ->route('gerente.products.index')
            ->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Produto excluído com sucesso.');
    }

    /**
     * Regras de validação compartilhadas.
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'category_id'          => ['required', 'exists:categories,id'],
            'name'                 => ['required', 'string', 'max:255'],
            'description'          => ['nullable', 'string'],
            'serves'               => ['nullable', 'string', 'max:50'],
            'price'                => ['required', 'numeric', 'min:0'],
            'available'            => ['nullable', 'boolean'],
            'is_customizable'      => ['nullable', 'boolean'],
            'requires_meat_point'  => ['nullable', 'boolean'],
        ], [
            'category_id.required' => 'Selecione a categoria.',
            'name.required'        => 'Informe o nome do produto.',
            'price.required'       => 'Informe o preço.',
            'price.numeric'        => 'O preço deve ser um número.',
        ]) + [
            'available'           => $request->boolean('available'),
            'is_customizable'     => $request->boolean('is_customizable'),
            'requires_meat_point' => $request->boolean('requires_meat_point'),
        ];
    }

    /**
     * Trata o upload de imagem (estrutura preparada — placeholder por enquanto).
     * Retorna o caminho salvo, ou null se nenhuma imagem foi enviada.
     */
    private function handleImage(Request $request): ?string
    {
        if ($request->hasFile('image')) {
            return $request->file('image')->store('products', 'public');
        }

        return null;
    }
}
