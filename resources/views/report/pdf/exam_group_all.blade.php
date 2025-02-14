<html>
<head>
    <title>{{ $examGroup->lessonCategory->name }} - {{ $examGroup->title }}</title>
    <style>
        table {
            border-collapse: collapse;
            border:1px solid #333;
            font-size:11px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    
        table th {
            padding:5px;
            border:1px solid #333;
            text-align: center;
        }
    
        table td {
            padding:5px;
            border:1px solid #333;
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
        <img src="{{ $setting->app_url }}/storage/upload_files/settings/{{ $setting->logo }}" alt="" height="50px" style="position: absolute; margin-top:-40px; margin-bottom:20px;">
    @endif
    <center>
        <div class="title">
            <h3>REKAPITULASI NILAI {{ strtoupper($examGroup->title) }}</h3>
        </div>
    </center>

    @php
        $fields = collect($setting['authentication_field'])->where('is_active', '1')->keyBy('name');
    @endphp

    <table width="100%" border="1" style="font-size:8;">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                @if($fields->has('code'))
                    <th rowspan="2">{{ $fields['code']['translate'] ?? 'NIS' }}</th>
                @endif
                @if($fields->has('name'))
                    <th rowspan="2">{{ $fields['name']['translate'] ?? 'Nama Lengkap' }}</th>
                @endif
                @if($fields->has('gender'))
                    <th rowspan="2">{{ $fields['gender']['translate'] ?? 'Jenis Kelamin' }}</th>
                @endif
                @if($fields->has('province_id'))
                    <th rowspan="2">{{ $fields['province_id']['translate'] ?? 'Provinsi' }}</th>
                @endif
                @if($fields->has('city_id'))
                    <th rowspan="2">{{ $fields['city_id']['translate'] ?? 'Kota/Kab' }}</th>
                @endif
                <th colspan="{{ $examGroup->exam->count() }}">Detail Nilai</th>
                <th rowspan="2">Nilai Akhir</th>
                <th rowspan="2">{{ $examGroup->minimal_grade_type == 0 ? 'Peringkat' : 'Keterangan' }}</th>
            </tr>
            <tr>
                @foreach($examGroup->exam as $exam)
                    <th>{{ $exam->lesson->short_name ? $exam->lesson->short_name : $exam->lesson->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($gradesCollection as $index => $data)
                @php
                    $user = $data['user'];
                    $userGrades = $data['grades'];
                @endphp
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    @if($fields->has('code'))
                        <td>{{ $user->user->code ?? '-' }}</td>
                    @endif
                    @if($fields->has('name'))
                        <td>{{ $user->user->name ?? '-' }}</td>
                    @endif
                    @if($fields->has('gender'))
                        <td>{{ $user->user->student && $user->user->student->gender ? ($user->user->student->gender == "F" ? "Perempuan" : "Laki-laki") : '-' }}</td>
                    @endif
                    @if($fields->has('province_id'))
                        <td>{{ $user->user->student ? ($user->user->student->province->name ?? '-') : '-' }}</td>
                    @endif
                    @if($fields->has('city_id'))
                        <td>{{ $user->user->student ? ($user->user->student->city->name ?? '-') : '-' }}</td>
                    @endif
                    @foreach($examGroup->exam as $exam)
                    @php
                        $gradeCollection = $userGrades->get($user->user_id . '-' . $exam->id);
                        $gradeDetail = $gradeCollection ? $gradeCollection->first() : null;
                    @endphp
                    <td align="center">{{ $gradeDetail ? gradeFormat($gradeDetail->grade) : '-' }}</td>                
                    @endforeach
                    <td align="center">{{ gradeFormat($user->grade) }}</td>
                    <td align="center">{{ $examGroup->minimal_grade_type == 0 
                        ? ($user->is_finished ? $index + 1 : '-') 
                        : ($user->description ?? '-') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>