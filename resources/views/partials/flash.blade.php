{{-- Mensagens de sucesso e erro --}}
@if(session('success'))
    <div class="alert alert-success"><i class="ti ti-circle-check"></i> {{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-error"><i class="ti ti-alert-triangle"></i> {{ $errors->first() }}</div>
@endif
