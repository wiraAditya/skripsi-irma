<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Users Card -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm mb-2">Total Users</h3>
                    <p class="text-3xl font-bold">1,234</p>
                </div>

                <!-- Active Sessions Card -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm mb-2">Active Sessions</h3>
                    <p class="text-3xl font-bold">42</p>
                </div>
            </div>

            <!-- Recent Activities Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-gray-600 border-b">
                                    <th class="pb-3">#</th>
                                    <th class="pb-3">User</th>
                                    <th class="pb-3">Activity</th>
                                    <th class="pb-3">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Isi tabel dari database -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="mt-8 text-center text-sm text-gray-600">
                © 2025 Admin Panel. All rights reserved.
            </footer>
        </div>
    </div>
</x-app-layout>
