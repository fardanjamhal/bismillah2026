
<table border="1">
    <thead>
        <tr>
            <td align="center">REKAPITULASI NILAI {{ strtoupper($exam->title) }}</td>
        </tr>
        <tr>
            <td>No</td>
            @if($fields->has('code'))
                <td>{{ $fields['code']['translate'] ?? 'Nama Lengkap' }}</td>
            @endif
            @if($fields->has('name'))
                <td>{{ $fields['name']['translate'] ?? 'Nama Lengkap' }}</td>
            @endif
            @if($fields->has('gender'))
                <td>{{ $fields['gender']['translate'] ?? 'Jenis Kelamin' }}</td>
            @endif
            @if($fields->has('province_id'))
                <td>{{ $fields['province_id']['translate'] ?? 'Provinsi' }}</td>
            @endif
            @if($fields->has('city_id'))
                <td>{{ $fields['city_id']['translate'] ?? 'Kota/Kab' }}</td>
            @endif
            <td>Nilai</td>
        </tr>
    </thead>
    <tbody>
        @foreach($rankingExams as $index => $examUser)
            <tr>
                <td>{{ ++$index }}</td>
                @if($fields->has('code'))
                    <td>{{ $examUser->user->code ?? '-' }}</td>
                @endif
                @if($fields->has('name'))
                    <td>{{ $examUser->user->name ?? '-' }}</td>
                @endif
                @if($fields->has('gender'))
                    <td>{{ $examUser->user->student ? ($examUser->user->student->gender == "F" ? "P" : "L") : '-'}}</td>
                @endif
                @if($fields->has('province_id'))
                    <td>{{ $examUser->user->student ? ($examUser->user->student->province->name ?? '-') : '-' }}</td>
                @endif
                @if($fields->has('city_id'))
                    <td>{{ $examUser->user->student ? ($examUser->user->student->city->name ?? '-') : '-' }}</td>
                @endif
                <td>{{ number_format($examUser->grade, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
