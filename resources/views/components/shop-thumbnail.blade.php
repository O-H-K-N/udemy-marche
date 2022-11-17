<div>
  @if (empty($filename))
    <img src="{{ asset('images/no_image.jpeg') }}">
  @else
    <img src="{{ asset('storage/shops/' . $filename) }}">
  @endif
</div>