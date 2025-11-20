@if(session('success'))
    <div class="alert alert-success alert-auto-close">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-auto-close">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-auto-close">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
