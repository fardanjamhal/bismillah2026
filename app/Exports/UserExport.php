<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\User;

class UserExport implements FromView
{
    public function view(): View
    {
        $users = User::orderBy('created_at', 'ASC')->get();
        
        return view('export.excel.user_export', [
            'users' => $users,
        ]);
    }
}