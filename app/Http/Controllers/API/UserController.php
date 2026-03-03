<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\BuilderDataTableService;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class UserController extends Controller
{
    public function __construct(private BuilderDataTableService $dataTableService)
    { }

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $query = User::query();

        // Use the DataTableService or any other logic as needed
        $response = $this->dataTableService->getJsonResponse($request, $query);

        return response()->json($response, 200);
    }

    // public function list(Request $request)
    // {
    //     // Parameter paginasi dari Vue DataTable
    //     $perPage = $request->input('per_page', 10); // Default 10 item per halaman
    //     $search = $request->input('search');
    //     $sortBy = $request->input('sort_by', 'id'); // Default sort by id
    //     $sortDirection = $request->input('sort_direction', 'asc'); // Default sort asc

    //     $query = User::query();

    //     // Logika pencarian
    //     if ($search) {
    //         $query->where(function($q) use ($search) {
    //             $q->where('name', 'like', '%' . $search . '%')
    //                 ->orWhere('email', 'like', '%' . $search . '%');
    //             // Tambahkan kolom lain yang ingin dicari di sini
    //         });
    //     }

    //     // Logika pengurutan
    //     $query->orderBy($sortBy, $sortDirection);

    //     // Lakukan paginasi
    //     $users = $query->paginate($perPage);

    //     // Format respons sesuai dengan apiResponseMeta di Vue DataTable
    //     return response()->json([
    //         'data' => $users->items(), // Data produk untuk halaman saat ini
    //         'total' => $users->total(), // Total semua produk (untuk paginasi)
    //         'per_page' => $users->perPage(),
    //         'current_page' => $users->currentPage(),
    //         'last_page' => $users->lastPage(),
    //     ]);
    //     // // Build the query excluding the current user
    //     // $query = User::query();

    //     // // Use the DataTableService or any other logic as needed
    //     // $response = $this->dataTableService->getJsonResponse($request, $query);

    //     // return response()->json(User::get(), 200);
    // }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name', // Pastikan role yang diberikan ada di tabel roles
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Add role 'user' secara default
        $createdUser->roles()->attach($request['role']);

        return response()->json([
            'success' => true,
            'message' => "User created successfully!",
            'data' => $user,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // Build the query excluding the current user
        $user = User::find($id);

        // Cek jika user ditemukan
        if ($user) {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => "Success deleted user!"
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "User not found!"
            ], 404);
        }
    }
}
