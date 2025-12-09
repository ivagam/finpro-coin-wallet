@if(request('success'))
    <div class="alert alert-success">{{ request('success') }}</div>
@endif
@if(request('error'))
    <div class="alert alert-danger">{{ request('error') }}</div>
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

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
