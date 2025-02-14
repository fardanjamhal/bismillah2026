<table border="1">
    <thead style="background: red">
        <tr>
            <th align="center" colspan="{{ ($examGroup->exam->count() + 7) - ($fields->has('name') ? 0 : 1) - ($fields->has('gender') ? 0 : 1) - ($fields->has('province_id') ? 0 : 1) - ($fields->has('city_id') ? 0 : 1) }}">REKAPITULASI NILAI {{ strtoupper($examGroup->title) }}</th>
        </tr>
        <tr>
            <th valign="middle" align="center" width="5" rowspan="2">No</th>
            @if($fields->has('code'))
                <th valign="middle" align="center" width="30" rowspan="2">{{ $fields['code']['translate'] ?? 'Kode' }}</th>
            @endif
            @if($fields->has('name'))
                <th valign="middle" align="center" width="30" rowspan="2">{{ $fields['name']['translate'] ?? 'Nama Lengkap' }}</th>
            @endif
            @if($fields->has('gender'))
                <th valign="middle" align="center" width="30" rowspan="2">{{ $fields['gender']['translate'] ?? 'Jenis Kelamin' }}</th>
            @endif
            @if($fields->has('province_id'))
                <th valign="middle" align="center" width="30" rowspan="2">{{ $fields['province_id']['translate'] ?? 'Provinsi' }}</th>
            @endif
            @if($fields->has('city_id'))
                <th valign="middle" align="center" width="30" rowspan="2">{{ $fields['city_id']['translate'] ?? 'Kota/Kab' }}</th>
            @endif
            <th valign="middle" align="center" colspan="{{ $examGroup->exam->count() }}">Detail Nilai</th>
            <th valign="middle" align="center" width="15" rowspan="2">Nilai Akhir</th>
            <th valign="middle" align="center" width="20" rowspan="2">{{ $examGroup->minimal_grade_type == 0 ? 'Peringkat' : 'Keterangan' }}</th>
        </tr>
        <tr>
            @foreach($examGroup->exam as $exam)
                <th align="center">{{ $exam->lesson->short_name ? $exam->lesson->short_name : $exam->lesson->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($examGroupUsers as $index => $examGroupUser)
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                @if($fields->has('code'))
                    <td>{{ $examGroupUser->user->code ?? '-' }}</td>
                @endif
                @if($fields->has('name'))
                    <td>{{ $examGroupUser->user->name ?? '-' }}</td>
                @endif
                @if($fields->has('gender'))
                    <td>{{ $examGroupUser->user->student ? ($examGroupUser->user->student->gender == "F" ? "Perempuan" : "Laki-laki") : '-'}}</td>
                @endif
                @if($fields->has('province_id'))
                    <td>{{ $examGroupUser->user->student ? ($examGroupUser->user->student->province->name ?? '-') : '-' }}</td>
                @endif
                @if($fields->has('city_id'))
                    <td>{{ $examGroupUser->user->student ? ($examGroupUser->user->student->city->name ?? '-') : '-' }}</td>
                @endif
                @foreach($examGroup->exam as $exam)
                    @php
                        $gradeCollection = $grades->get($examGroupUser->user_id . '-' . $exam->id);
                        $gradeDetail = $gradeCollection ? $gradeCollection->first() : null;
                    @endphp
                    <td align="center" width="20">
                        {{ $gradeDetail ? number_format($gradeDetail->grade, 2) : '-' }}
                    </td>
                @endforeach
                <td align="center">{{ $examGroupUser->grade }}</td>
                <td align="center">
                    {{ $examGroup->minimal_grade_type == 0 
                        ? ($examGroupUser->is_finished ? $index + 1 : '-') 
                        : ($examGroupUser->description ?? '-') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
