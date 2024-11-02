@push('head')

@endpush

<div class="h2 d-flex align-items-center">
    <link rel="icon" href="{{ asset('/images/123.jpg')}}">
    <p class="my-0 {{ auth()->check() ? 'd-none d-xl-block' : '' }}">
        Панель СКУД
    </p>
</div>
