<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* mPDF: plain CSS only (no flexbox / grid / Tailwind). */
        body { font-family: dejavusans, sans-serif; font-size: 9pt; color: #222222; }
        h1 { font-size: 16pt; color: #3730a3; margin: 0 0 2pt 0; }
        h2 { font-size: 11pt; color: #3730a3; margin: 14pt 0 4pt 0; }
        .sub { color: #666666; font-size: 8.5pt; margin: 0 0 10pt 0; }
        .org { color: #888888; font-size: 8pt; text-transform: uppercase; margin: 0 0 2pt 0; }

        table.data { width: 100%; border-collapse: collapse; }
        table.data th { background-color: #3730a3; color: #ffffff; padding: 5pt 4pt; text-align: left; font-size: 8pt; }
        table.data td { padding: 4pt; border-bottom: 0.5pt solid #dddddd; font-size: 8pt; vertical-align: top; }
        table.data tr.alt td { background-color: #f4f4fb; }
        table.data tfoot td, table.data tr.total td { font-weight: bold; border-top: 1pt solid #333333; background-color: #ffffff; }

        table.info { width: 100%; border-collapse: collapse; }
        table.info td { padding: 3pt 4pt; font-size: 8.5pt; border-bottom: 0.5pt solid #eeeeee; }
        table.info td.k { color: #666666; width: 18%; }

        .right { text-align: right; }
        .red { color: #b91c1c; font-weight: bold; }
        .yellow { color: #a16207; font-weight: bold; }
        .muted { color: #999999; }
        .empty { text-align: center; color: #888888; padding: 12pt; }
        .summary { margin-top: 10pt; font-size: 9pt; }
    </style>
</head>
<body>
    <div class="org">{{ config('app.name') }}</div>
    <h1>{{ $title }}</h1>
    <div class="sub">{{ $subtitle ?? '' }}</div>

    @yield('content')
</body>
</html>
