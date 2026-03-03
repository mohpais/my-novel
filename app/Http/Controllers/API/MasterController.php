<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;

class MasterController extends Controller
{
    public function GetRoles() 
    {
        $roles = Role::
            select('id', 'name') // Pilih kolom yang diperlukan
            ->orderBy('name', 'asc') // Urutkan berdasarkan nama
            ->get();

        return response()->json([
            'roles' => $roles,
        ]);
    }
}
