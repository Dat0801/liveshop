<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">User Management</h2>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..." class="input w-64">
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">Name</th>
                        <th class="text-left py-3 px-4">Email</th>
                        <th class="text-center py-3 px-4">Phone</th>
                        <th class="text-center py-3 px-4">Joined</th>
                        <th class="text-center py-3 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <p class="font-semibold">{{ $user->name }}</p>
                            </td>
                            <td class="py-3 px-4">{{ $user->email }}</td>
                            <td class="py-3 px-4 text-center">{{ $user->phone ?? '—' }}</td>
                            <td class="py-3 px-4 text-center text-sm text-gray-600">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button wire:click="viewUser({{ $user->id }})" class="text-blue-600 hover:text-blue-800 mr-3">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            {{ $users->links() }}
        </div>
    </div>

    <!-- User Details Modal -->
    @if($showDetailsModal && $selectedUser)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showDetailsModal') }">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="show = false"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold">User Details</h3>
                            <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-6">
                            <!-- Basic Info -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold mb-3">Basic Information</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Name</p>
                                        <p class="font-semibold">{{ $selectedUser->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Email</p>
                                        <p class="font-semibold">{{ $selectedUser->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Phone</p>
                                        <p class="font-semibold">{{ $selectedUser->phone ?? '—' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Joined</p>
                                        <p class="font-semibold">{{ $selectedUser->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Orders -->
                            @if($selectedUser->orders && $selectedUser->orders->count() > 0)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-semibold mb-3">Recent Orders ({{ $selectedUser->orders->count() }})</h4>
                                    <div class="space-y-2">
                                        @foreach($selectedUser->orders->take(5) as $order)
                                            <div class="flex justify-between items-center py-2 border-b">
                                                <div>
                                                    <p class="font-semibold">{{ $order->order_number }}</p>
                                                    <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-semibold">${{ number_format($order->total, 2) }}</p>
                                                    <span class="text-xs px-2 py-1 rounded-full {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Addresses -->
                            @if($selectedUser->addresses && $selectedUser->addresses->count() > 0)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-semibold mb-3">Addresses</h4>
                                    <div class="space-y-3">
                                        @foreach($selectedUser->addresses as $address)
                                            <div class="border-l-4 border-primary-500 pl-3">
                                                <p class="font-semibold">{{ $address->type ?? 'Address' }}</p>
                                                <p class="text-sm">{{ $address->address }}, {{ $address->city }}, {{ $address->state }} {{ $address->zip }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex gap-3">
                                <button type="button" wire:click="resetUserPassword({{ $selectedUser->id }})" 
                                        class="btn btn-secondary"
                                        onclick="return confirm('Reset password? User will need to set new password on login.')">
                                    Reset Password
                                </button>
                                <button type="button" wire:click="closeModal" class="btn ml-auto">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
