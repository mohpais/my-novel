<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\Tag;
use App\Models\Genre;

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
    
    public function GetGenres() 
    {
        $genres = Genre::
            select('id', 'name', 'slug') // Pilih kolom yang diperlukan
            ->orderBy('name', 'asc') // Urutkan berdasarkan nama
            ->get();

        return response()->json([
            'genres' => $genres,
        ]);
    }

    public function GetTags() 
    {
        $tags = Tag::
            select('id', 'name', 'slug') // Pilih kolom yang diperlukan
            ->orderBy('name', 'asc') // Urutkan berdasarkan nama
            ->get();

        return response()->json([
            'tags' => $tags,
        ]);
    }
}
