<?php

namespace App\Http\Controllers\Gerente;

use App\Http\Controllers\Controller;
use App\Models\PastaOption;
use Illuminate\Http\Request;

/**
 * CRUD das opções do "Monte sua Massa" (área do Gerente).
 * Cada opção é do tipo massa, molho ou ingrediente.
 */
class PastaOptionController extends Controller
{
    public function index()
    {
        $pastaOptions = PastaOption::orderBy('type')->orderBy('sort_order')->orderBy('name')->get();

        return view('gerente.pasta_options.index', compact('pastaOptions'));
    }

    public function create()
    {
        return view('gerente.pasta_options.form', ['pastaOption' => new PastaOption()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        PastaOption::create($data);

        return redirect()
            ->route('gerente.pasta-options.index')
            ->with('success', 'Opção cadastrada com sucesso.');
    }

    public function edit(PastaOption $pastaOption)
    {
        return view('gerente.pasta_options.form', compact('pastaOption'));
    }

    public function update(Request $request, PastaOption $pastaOption)
    {
        $data = $this->validateData($request);

        $pastaOption->update($data);

        return redirect()
            ->route('gerente.pasta-options.index')
            ->with('success', 'Opção atualizada com sucesso.');
    }

    public function destroy(PastaOption $pastaOption)
    {
        $pastaOption->delete();

        return back()->with('success', 'Opção excluída com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'type'        => ['required', 'in:massa,molho,ingrediente'],
            'name'        => ['required', 'string', 'max:255'],
            'extra_price' => ['nullable', 'numeric', 'min:0'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'active'      => ['nullable', 'boolean'],
        ], [
            'type.required' => 'Selecione o tipo da opção.',
            'name.required' => 'Informe o nome da opção.',
        ]) + [
            'active'      => $request->boolean('active'),
            'extra_price' => $request->input('extra_price') ?: 0,
            'sort_order'  => $request->integer('sort_order'),
        ];
    }
}
