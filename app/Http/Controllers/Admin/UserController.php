<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function index() {

        return User::query()
            ->when(\request('query'), function ($query, $searchQuery){
                $query->where('name','like', "%{$searchQuery}%");
            })
            ->latest()
            ->paginate(10);
    }

    public function store() {

        \request()->validate([
            'name' => ['required'],
            'email' => ['required', 'unique:users,email'],
            'password' => ['required', 'min:8']
        ]);

        return User::create([
            'name' => \request('name'),
            'email' => \request('email'),
            'password' => bcrypt(\request('password'))
        ]);
    }

    public function update(User $user) {

        \request()->validate([
            'name' => ['required'],
            'email' => ['required', Rule::unique('users','email')->ignore($user->id)],
            'password' => ['sometimes', 'min:8']
        ]);

        return $user->update([
            'name' => \request('name'),
            'email' => \request('email'),
            'password' => \request('password') ? bcrypt(\request('password')) : $user->password
        ]);
    }

    public function destroy(User $user) {

        $user->delete();

        return response()->noContent();
    }

    public function changeRole(User $user) {

        $user->update([
            'role' => \request('role')
        ]);

        return response()->json(['success' => true ]);
    }
    
    public function bulkDelete() {

        User::whereIn('id',\request('ids'))->delete();

        return response()->json(['message' => "User deleted successfully!!!"]);
    }
}
