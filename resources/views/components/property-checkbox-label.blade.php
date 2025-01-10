<div class="flex items-center gap-2">
    @if($iconUrl)
        <img src="{{ Storage::disk('public')->url( $iconUrl) }}" alt="icon" class="w-8 h-8 rounded-full">
    @endif
    <span>{{ $title }} ({{ $type }})</span>
</div>
