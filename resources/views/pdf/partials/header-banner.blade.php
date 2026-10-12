@php($banner = tenant()?->header_image_data_uri)

@if ($banner)
    <div class="doc-banner">
        <img src="{{ $banner }}" alt="En-tête">
    </div>
@endif
