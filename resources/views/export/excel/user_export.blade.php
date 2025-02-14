<table>
    <thead>
        <tr>
            <th>No</th>
            <th width="25">Kode</th>
            <th width="40">Nama</th>
            <th width="40">Email</th>
            <th width="25">Username</th>
            <th width="15">Level</th>
            <th width="20">Tipe Member</th>
            <th width="25">Saldo Akun</th>
            <th width="20">Provinsi</th>
            <th width="20">Kota/Kab</th>
            <th width="20">Kecamatan</th>
            <th width="20">Desa/Kelurahan</th>
            <th width="60">Alamat</th>
            <th width="25">Jenis Kelamin</th>
            <th width="20">Nomor Whatsapp</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $index => $user)
            <tr>
                <td>{{ ++$index }}</td>
                <td>{{ $user->code ?? '-' }}</td>
                <td>{{ $user->name ?? '-' }}</td>
                <td>{{ $user->email ?? '-' }}</td>
                <td>{{ $user->username ?? '-' }}</td>
                <td>{{ $user->level == 1 ? 'Admin' : ($user->level == 2 ? 'Peserta' : 'Guru') }}</td>
                <td>{{ $user->member_type == 1 ? 'Member Gratis' : 'Member Berbayar' }}</td>
                <td>{{ $user->account_balance }}</td>
                <td>{{ $user->student && $user->student->province ? $user->student->province->name : '-' }}</td>
                <td>{{ $user->student && $user->student->city ? $user->student->city->name : '-' }}</td>
                <td>{{ $user->student && $user->student->district ? $user->student->district->name : '-' }}</td>
                <td>{{ $user->student && $user->student->village ? $user->student->village->name : '-' }}</td>
                <td>{{ $user->student && $user->student->address ? $user->student->address : '-' }}</td>
                <td>
                    {{ 
                        $user->student && $user->student->gender 
                        ? ($user->student->gender == 'M' 
                            ? 'Laki-laki' 
                            : ($user->student->gender == 'F' 
                                ? 'Perempuan' 
                                : '-')) 
                        : '-' 
                    }}
                </td>
                <td>{{ $user->student && $user->student->phone_number ? $user->student->phone_number : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>