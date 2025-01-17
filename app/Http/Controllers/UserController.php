<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash; // Import this for password hashing

class UserController extends Controller
{
    // Load all users
    public function loadAllUsers()
    {
        $all_users = User::all();
        return view('users', compact('all_users'));
    }

    // Load form to add a new user
    public function loadAddUserForm()
    {
        return view('add-user');
    }

    // Add a new user
    public function AddUser(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'is_admin' => 'required|in:user,admin',
            'password' => 'required|confirmed|min:4|max:8',
        ]);

        try {
            $new_user = new User;
            $new_user->name = $request->full_name;
            $new_user->email = $request->email;
            $new_user->is_admin = $request->is_admin; // Save user type
            $new_user->password = $request->password; // Hash the password

            $new_user->save();

            return redirect('/users')->with('success', 'User Added Successfully');
        } catch (\Exception $e) {
            return redirect('/add/user')->with('fail', $e->getMessage());
        }
    }

    // Edit an existing user
    public function EditUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'full_name' => 'required|string',
            'email' => 'required|email',
            // 'type' => 'required|in:user,admin',
            'password' => 'nullable|confirmed|min:4|max:8',
        ]);

        try {
            $update_data = [
                'name' => $request->full_name,
                'email' => $request->email,
                'is_admin' => $request->is_admin, // Update user type
            ];

            if ($request->filled('password')) {
                $update_data['password'] = $request->password;
            }

            User::where('id', $request->user_id)->update($update_data);

            return redirect('/users')->with('success', 'User Updated Successfully');
        } catch (\Exception $e) {
            return redirect('/edit/user')->with('fail', $e->getMessage());
        }
    }

    // Load form to edit a user
    public function loadEditForm($id)
    {
        $user = User::findOrFail($id); // Use findOrFail to handle invalid IDs

        return view('edit-user', compact('user'));
    }

    // Delete a user
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);

            // Ensure at least one admin remains
            if ($user->is_admin === 'admin' && User::where('is_admin', 'admin')->count() === 1) {
                return redirect('/users')->with('fail', 'The last admin cannot be deleted.');
            }

            $user->delete();

            return redirect('/users')->with('success', 'User Deleted Successfully');
        } catch (\Exception $e) {
            return redirect('/users')->with('fail', $e->getMessage());
        }
    }
}