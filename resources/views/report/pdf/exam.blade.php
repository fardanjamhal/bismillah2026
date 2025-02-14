<html>
<head>
    <title>{{ $exam->lessonCategory->name }} - {{ $exam->title }}</title>
    <style>
        table {
            border-collapse: collapse;
            font-size:12px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        table th, table td {
            padding:8px;
            text-align: left;
        }
        .title {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        @media print {
            .new-page {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    @if($setting)
        <br>
        <img src="{{ $setting->app_url }}/storage/upload_files/settings/{{ $setting->logo }}" alt="" height="50px" style="position: absolute; margin-top:-50px;">
    @endif
    <center>
        <div class="title">
            <h3>REKAPITULASI NILAI {{ strtoupper($exam->title) }}</h3>
        </div>
    </center>

    @php
        $fields = collect($setting['authentication_field'])->where('is_active', '1')->keyBy('name');
    @endphp

    <table width="100%" border="1" style="font-size:8;">
        <thead>
            <tr>
                <th>No</th>
                @if($fields->has('code'))
                    <th>{{ $fields['code']['translate'] ?? 'Label Tidak Tersedia' }}</th>
                @endif
                @if($fields->has('name'))
                    <th>{{ $fields['name']['translate'] ?? 'Label Tidak Tersedia' }}</th>
                @endif
                @if($fields->has('gender'))
                    <th>{{ $fields['gender']['translate'] ?? 'Label Tidak Tersedia' }}</th>
                @endif
                @if($fields->has('province_id'))
                    <th>{{ $fields['province_id']['translate'] ?? 'Label Tidak Tersedia' }}</th>
                @endif
                @if($fields->has('city_id'))
                    <th>{{ $fields['city_id']['translate'] ?? 'Label Tidak Tersedia' }}</th>
                @endif
                <th>Nilai</th>
            </tr>
        </thead>
        
        <tbody>
            @foreach($rankingExamChunks as $pageIndex => $chunkedRankingExams)
                @foreach($chunkedRankingExams as $index => $examUser)
                    <tr>
                        <td>{{ $index + 1 + ($pageIndex * 100) }}</td>
                        @if($fields->has('code'))
                            <td>{{ $examUser->user->code ?? '-' }}</td>
                        @endif
                        @if($fields->has('name'))
                            <td>{{ $examUser->user->name ?? '-' }}</td>
                        @endif
                        @if($fields->has('gender'))
                            <td>{{ $examUser->user->student && $examUser->user->student->gender ? ($examUser->user->student->gender == "F" ? "Perempuan" : "Laki-laki") : '-' }}</td>
                        @endif
                        @if($fields->has('province_id'))
                            <td>{{ $examUser->user->student ? ($examUser->user->student->province->name ?? '-') : '-' }}</td>
                        @endif
                        @if($fields->has('city_id'))
                            <td>{{ $examUser->user->student ? ($examUser->user->student->city->name ?? '-') : '-' }}</td>
                        @endif
                        <td>{{ gradeFormat($examUser->grade) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
