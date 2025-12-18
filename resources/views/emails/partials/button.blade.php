@props(['url' => '#', 'label' => 'Acessar'])

<div style="margin:18px 0;">
  <a href="{{ $url }}"
     style="display:inline-block;background:#111827;color:#ffffff;text-decoration:none;font-weight:700;
            padding:12px 16px;border-radius:10px;font-size:14px;">
    {{ $label }}
  </a>
</div>
