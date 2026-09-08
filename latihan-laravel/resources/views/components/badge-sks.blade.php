@props(['sks' => 0])

<span class="badge {{ $sks < 3 ? 'bg-danger' : 'bg-success' }}">
    {{ $sks }} SKS
</span>
