{{-- resources/views/parking/index.blade.php --}}
{{-- Standalone page. To use your own layout: replace <html>..</body> with @extends('layouts.app') @section('content') --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Parking – Ground Floor</title>
  <style>
    :root { --bg:#f6f7f9; --card:#fff; --text:#1d2430; --muted:#6b7280; --line:#e3e6eb;
            --free:#e8f6ec; --free-b:#9ad3a8; --busy:#fdeceb; --busy-b:#eda3a0; --accent:#2563eb; }
    @media (prefers-color-scheme: dark) {
      :root { --bg:#14171c; --card:#1c2027; --text:#e8ebf0; --muted:#9aa3b0; --line:#2c323c;
              --free:#16301f; --free-b:#2f6b43; --busy:#37201f; --busy-b:#8a4440; --accent:#6ea0ff; }
    }
    * { box-sizing:border-box }
    body { margin:0; padding:24px; background:var(--bg); color:var(--text); font:15px/1.5 system-ui,sans-serif }
    h1 { margin:0 0 4px; font-size:22px } .sub { color:var(--muted); margin-bottom:20px }
    .flash { padding:10px 14px; border-radius:8px; margin-bottom:16px }
    .ok { background:var(--free); border:1px solid var(--free-b) }
    .err { background:var(--busy); border:1px solid var(--busy-b) }
    .zone { background:var(--card); border:1px solid var(--line); border-radius:12px; padding:16px; margin-bottom:20px }
    .zone h2 { margin:0 0 12px; font-size:16px }
    .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:12px }
    .slot { border-radius:10px; padding:14px; border:2px solid; min-height:150px; display:flex; flex-direction:column; gap:6px }
    .slot.free { background:var(--free); border-color:var(--free-b) }
    .slot.busy { background:var(--busy); border-color:var(--busy-b) }
    .code { font-size:22px; font-weight:700 }
    .tag { font-size:12px; color:var(--muted) }
    .reg { font-weight:600; word-break:break-word }
    select, button { font:inherit; padding:6px 8px; border-radius:6px; border:1px solid var(--line); background:var(--card); color:var(--text); width:100% }
    button { cursor:pointer; background:var(--accent); color:#fff; border-color:var(--accent); margin-top:6px }
    button.danger { background:transparent; color:var(--text); border-color:var(--busy-b) }
    form { margin-top:auto }
  </style>
</head>
<body>
  <h1>Ground Floor Parking</h1>
  <div class="sub">{{ $slots->flatten()->filter(fn($s) => $s->vehicle)->count() }} of {{ $slots->flatten()->count() }} slots occupied · {{ $unparked->count() }} vehicle(s) without a slot</div>

  @if(session('success')) <div class="flash ok">{{ session('success') }}</div> @endif
  @if($errors->any()) <div class="flash err">{{ $errors->first() }}</div> @endif

  @foreach($slots as $zone => $zoneSlots)
    <section class="zone">
      <h2>Zone {{ $zone }}</h2>
      <div class="grid">
        @foreach($zoneSlots as $slot)
          @php $v = $slot->vehicle; @endphp
          <div class="slot {{ $v ? 'busy' : 'free' }}">
            <div class="code">{{ $slot->code }}</div>

            @if($v)
              <div class="reg">{{ $v->registration_no }}</div>
              <div>{{ $v->brand }} {{ $v->model }}</div>
              <div class="tag">{{ ucfirst(str_replace('_', ' ', $v->status)) }}</div>
              <form method="POST" action="{{ route('parking.release', $slot) }}"
                    onsubmit="return confirm('Free slot {{ $slot->code }}?')">
                @csrf @method('DELETE')
                <button class="danger" type="submit">Release</button>
              </form>
            @else
              <div class="tag">Available</div>
              <form method="POST" action="{{ route('parking.assign', $slot) }}">
                @csrf
                <select name="vehicle_id" required>
                  <option value="">Select vehicle…</option>
                  @foreach($unparked as $u)
                    <option value="{{ $u->id }}">{{ $u->registration_no }} – {{ $u->brand }} {{ $u->model }}</option>
                  @endforeach
                </select>
                <button type="submit" @disabled($unparked->isEmpty())>Park here</button>
              </form>
            @endif
          </div>
        @endforeach
      </div>
    </section>
  @endforeach
</body>
</html>
