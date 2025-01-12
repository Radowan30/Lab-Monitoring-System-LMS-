<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/lab-room.css'])

    <title>Admin Panel</title>
</head>

<body class="bg-gray-100">
    <header class="bg-gradient-to-r from-cyan-400 to-indigo-500 p-4 text-white flex items-center justify-between">
        <a href="#" class="text-white text-2xl no-underline">←</a>
        <h1>Admin Panel</h1>
        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-indigo-500 font-bold">A</div>
    </header>

    <div class="max-w-7xl mx-auto my-8 px-4">
        <h2 class="text-center text-gray-600 text-2xl mb-8">Hi Admin, Welcome back!</h2>

        <div class="flex gap-4 mb-8">
            <button type="button"
                class="bg-blue-500 text-white px-4 py-2 rounded-md flex items-center gap-2 hover:bg-blue-600"
                onclick="toggleModal('addUserModal')">+ User</button>
            <input type="text"
                class="flex-1 px-4 py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Search user" id="searchInput" onkeyup="searchUsers()">
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b border-gray-200 grid grid-cols-6 font-medium text-gray-600">
                <div class="col-span-2">Username</div>
                <div>Type</div>
                <div class="col-span-3">Actions</div>
            </div>

            <div id="usersList">
                @if (!empty($all_users) && $all_users->count() > 0)
                    @foreach ($all_users as $item)
                        <div class="p-4 grid grid-cols-6 items-center border-b border-gray-200 user-row"
                            data-user-name="{{ strtolower($item->name) }}"
                            data-user-type="{{ strtolower($item->is_admin) }}">
                            <div class="col-span-2">{{ $item->name }}</div>
                            <div>{{ $item->is_admin }}</div>
                            <div class="col-span-3 flex gap-2">
                                <button type="button"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 text-sm"
                                    onclick="editUser('{{ $item->id }}', '{{ $item->name }}', '{{ $item->email }}', '{{ $item->is_admin }}'); toggleModal('editUserModal')">
                                    Update Profile
                                </button>
                                <a href="/delete/{{ $item->id }}"
                                    class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 inline-flex items-center text-sm"
                                    onclick="return confirm('Are you sure you want to delete this user? If this is the last admin, the action will not proceed.')">
                                    <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14 3H2V13C2 14.1 2.9 15 4 15H12C13.1 15 14 14.1 14 13V3Z"
                                            fill="currentColor" />
                                        <path d="M12 1H4L3 2H0V4H16V2H13L12 1Z" fill="currentColor" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-4 text-center no-results">
                        <div>No User Found!</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="min-h-screen px-4 text-center">
            <div class="fixed inset-0 transition-opacity" onclick="toggleModal('addUserModal')"></div>
            <div
                class="inline-block align-middle max-w-md w-full bg-white rounded-lg text-left shadow-xl transform transition-all my-8">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <h5 class="text-xl font-medium">Add New User</h5>
                    <button type="button" class="text-gray-400 hover:text-gray-500 text-2xl"
                        onclick="toggleModal('addUserModal')">×</button>
                </div>
                <div class="p-4">
                    <form action="{{ route('AddUser') }}" method="post">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter Full Name">
                            @error('full_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">User Type</label>
                            <select name="is_admin"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="0">User</option>
                                <option value="1">Admin</option>
                            </select>
                            @error('is_admin')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter Email">
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter Password">
                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Confirm Password">
                            @error('password_confirmation')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="min-h-screen px-4 text-center">
            <div class="fixed inset-0 transition-opacity" onclick="toggleModal('editUserModal')"></div>
            <div
                class="inline-block align-middle max-w-md w-full bg-white rounded-lg text-left shadow-xl transform transition-all my-8">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <h5 class="text-xl font-medium">Edit User</h5>
                    <button type="button" class="text-gray-400 hover:text-gray-500 text-2xl"
                        onclick="toggleModal('editUserModal')">×</button>
                </div>
                <div class="p-4">
                    <form action="{{ route('EditUser') }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="full_name" id="edit_full_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter Full Name">
                            @error('full_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">User Type</label>
                            <select name="is_admin" id="edit_user_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                            @error('is_admin')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="edit_email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter Email">
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password (optional)</label>
                            <input type="password" name="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter New Password">
                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Confirm New Password">
                            @error('password_confirmation')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save
                            Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }

        function editUser(id, name, email, userType) {
            document.getElementById('edit_user_id').value = id;
            document.getElementById('edit_full_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_user_type').value = userType;
        }

        function searchUsers() {
            const searchInput = document.getElementById('searchInput');
            const searchTerm = searchInput.value.toLowerCase();
            const userRows = document.querySelectorAll('.user-row:not(.no-results)');
            let hasVisibleRows = false;

            userRows.forEach(row => {
                const userName = row.getAttribute('data-user-name');
                const userType = row.getAttribute('data-user-type');

                if (userName.includes(searchTerm) || userType.includes(searchTerm)) {
                    row.classList.remove('hidden');
                    hasVisibleRows = true;
                } else {
                    row.classList.add('hidden');
                }
            });

            const noResults = document.querySelector('.no-results');
            if (noResults) {
                if (hasVisibleRows) {
                    noResults.classList.add('hidden');
                } else {
                    noResults.classList.remove('hidden');
                }
            }
        }
    </script>
</body>

</html>
