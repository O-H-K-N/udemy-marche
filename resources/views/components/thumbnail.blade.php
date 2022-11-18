{{-- 参照先画像ファイルの条件分岐 --}}
@php
    if ($type === 'shops') {
        $path = 'storage/shops/';
    }
    if ($type === 'products') {
        $path = 'storage/products/';
    }
@endphp

<div>
  @if (empty($filename))
    <img src="{{ asset('images/no_image.jpeg') }}">
  @else
    <img src="{{ asset($path . $filename) }}">
  @endif
</div>