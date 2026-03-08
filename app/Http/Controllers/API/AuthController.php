<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Role;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AuthController extends Controller
{
    // User registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed!',
                'errors'  => $validator->errors()
            ], 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        // Default role = customer
        $roleName = $request->input('role', 'reader');

        $role = Role::where('name', $roleName)->first();
        if (! $role) {
            return response()->json(['message' => "Role $roleName not found"], 422);
        }

        $user->roles()->attach($role->id);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'access_token' => JWTAuth::fromUser($user)
            ]
        ], 201);
    }

    // User login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $keepLoggedIn = $request->boolean('keepLoggedIn');

        try {
            // Validate the credentials.
            $validator = Validator::make($credentials, [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed!',
                    'errors'  => $validator->errors()
                ], 422);
            }

            // set TTL lebih panjang kalau keepLoggedIn
            if ($keepLoggedIn) {
                auth()->factory()->setTTL(60 * 24 * 7); // 7 hari
            }

            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Get the authenticated user.
            $user = auth()->user();

            $defaultPictureUrl = asset('images/defaults/no-avatar.png'); 

            // 1. Dapatkan URL gambar pengguna
            $picturePath = $user->picture;
        
            // Cek apakah ada path gambar di database DAN apakah file tersebut benar-benar ada di storage
            if ($picturePath && Storage::disk('public')->exists($picturePath)) {
                // Jika ada, gunakan URL dari storage
                $pictureUrl = Storage::url($picturePath);
            } else {
                // Jika tidak ada atau file tidak ditemukan, gunakan gambar default
                $pictureUrl = $defaultPictureUrl;
            }

            // load semua relasi yang dibutuhkan
            $user->load([
                'role:id,name,code',
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id'                => $user->id,
                        'name'              => $user->name,
                        'email'             => $user->email,
                        'picture_url'       => $pictureUrl, 

                        'role'       => $user->role ? [
                            'id'   => $user->role->id,
                            'name' => $user->role->name,
                            'code' => $user->role->code,
                        ] : null,
                    ],
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60
                ]
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Opps, something wrong!',
                'error'   => $e->messages
            ], 500);
        }
    }

    // Get current authenticated user
    public function me()
    {
        try {
            // Ambil user dari token JWT
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'message' => 'User tidak ditemukan atau token tidak valid.'
                ], 404);
            }

            $defaultPictureUrl = asset('images/defaults/no-avatar.png');

            // 1. Dapatkan URL gambar pengguna
            $picturePath = $user->picture;
        
            // Cek apakah ada path gambar di database DAN apakah file tersebut benar-benar ada di storage
            if ($picturePath && Storage::disk('public')->exists($picturePath)) {
                // Jika ada, gunakan URL dari storage
                $pictureUrl = Storage::url($picturePath);
            } else {
                // Jika tidak ada atau file tidak ditemukan, gunakan gambar default
                $pictureUrl = $defaultPictureUrl;
            }

            // load semua relasi yang dibutuhkan
            $user->load([
                'role:id,name,code',
            ]);

            // return dengan struktur yang rapi
            return response()->json([
                'id'                => $user->id,
                'name'              => $user->name,
                'employee_number'   => $user->employee_number,
                'email'             => $user->email,
                'picture_url'       => $pictureUrl, 

                'role'       => $user->role ? [
                    'id'   => $user->role->id,
                    'name' => $user->role->name,
                    'code' => $user->role->code,
                ] : null,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Could not get user',
                'error'   => $e->getMessage()
            ], 401);
        }
    }

    /**
     * Memperbarui foto profil (picture) untuk user yang sedang terautentikasi.
     * API: POST /api/user/update-picture
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePicture(Request $request)
    {
        // 1. Validasi permintaan
        $validator = Validator::make($request->all(), [
            // Gunakan 'picture' sebagai nama field untuk file yang diunggah
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Maksimal 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 2. Ambil user dari token JWT SESUAI permintaan
            // User diambil langsung dari token yang di-parse dan diautentikasi
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                // Walaupun token valid, jika user tidak ditemukan (misal sudah dihapus)
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan atau token tidak valid.'
                ], 404);
            }

            // 3. Hapus gambar lama jika ada untuk menghindari penumpukan file
            // Asumsi kolom foto profil di model User adalah 'picture'
            if ($user->picture) {
                Storage::disk('public')->delete($user->picture);
            }

            // 4. Upload dan simpan gambar baru
            // File akan disimpan di storage/app/public/profiles
            $path = $request->file('picture')->store('profiles', 'public');

            // 5. Update kolom 'picture' di database
            $user->picture = $path;
            $user->save();

            // 6. Berikan respon sukses
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui.',
                'picture_url' => Storage::url($path)
            ], 200);

        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException $e) {
            // Menangkap error jika token bermasalah (kadaluarsa, tidak ada, atau tidak valid)
            return response()->json([
                'success' => false,
                'message' => 'Autentikasi gagal: ' . $e->getMessage()
            ], 401);
        } catch (Exception $e) {
            // Menangkap error lainnya (misalnya masalah I/O saat upload)
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan server saat memperbarui foto: ' . $e->getMessage()
            ], 500);
        }
    }

    // User logout
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            // return response()->json(['message' => 'Successfully logged out']);
            // $token = JWTAuth::getToken();
            // if (!$token) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Token not provided.'
            //     ], 400);
            // }
            // JWTAuth::invalidate($token);

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out!'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Logout failed', 'error' => $e->getMessage()], 500);
        }
    }
}
