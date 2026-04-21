<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Pegawai;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KelolaUserController extends Controller
{
    public function index()
    {
        $isSuperAdmin = Auth::user()->isSuperadmin();
        $nip = Auth::user()->employee_id;
        if ($isSuperAdmin) {
            $users = User::with('role')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        } else {
            $users = User::where('employee_id', $nip)->paginate(10);
        }
        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }
    public function store(Request $request)
    {
        $this->saveuser($request);
        return redirect()->route('user.index')
        ->with('success', 'User dibuat. Password: '.$request->password);
    }
    public function update(Request $request, $id)
    {
        $password = $request->password ?? 'Password tidak berubah!';
        $this->saveuser($request, $id);
        return redirect()->route('user.edit', $id)
            ->with('success', 'Data berhasil diupdate. Password: ' .$password);
    }
    private function saveuser(Request $request,$Userid = null)
    {
        $user = $Userid ? User::findOrFail($Userid) : null;
        $request->validate([
           'username' => ['required', Rule::unique('users','username')->ignore($user)],
        ]);
        // CEK PEGAWAI
        if ($request->pegawai_id) {
        $pegawai = Pegawai::findOrFail($request->pegawai_id);

        $employee_id = $pegawai->nip;
        $name        = $pegawai->nama;
        $email       = $pegawai->email;
        $phone       = $pegawai->no_hp;
         } else {
        // pakai data lama dari user
        $employee_id = $user->employee_id;
        $name        = $user->name;
        $email       = $user->email;
        $phone       = $user->phone;
    }
    // DATA USER
    $data = [
        'employee_id' => $employee_id,
        'name'        => $name,
        'username'    => $request->username,
        'email'       => $email,
        'phone'       => $phone,
        'role_id'     => $request->role,
        'is_active'      => $request->has('status') ? 1 : 0,
    ];
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

        if ($user) {
        $user->update($data);
        activity()
            ->useLog('User')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Update user');
        return $user;
        }
        $user = User::create($data);
        activity()
            ->useLog('User')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Tambah user');
        return $user;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }
    public function detail($id)
    {
        $user = User::findOrFail($id);
        return view('user.detail', compact('user'));
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        activity()
            ->useLog('User')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ])
            ->log('Hapus user');
        $user->delete();
        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus');
    }

}
