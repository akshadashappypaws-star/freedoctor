@extends('admin.master')


@section('title', 'WhatsApp Bot Management')

@section('content')
<div class="container px-6 mx-auto grid">
    <!-- WhatsApp Bot Navigation -->
    <div class="mb-8 mt-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Bulk Messages -->
            <a href="{{ route('admin.whatsapp.bulk-messages') }}" 
               class="p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 mr-4">
                        <i class="fas fa-paper-plane text-green-500"></i>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700">Bulk Messages</p>
                        <p class="text-sm text-gray-500">Send messages to multiple users</p>
                    </div>
                </div>
            </a>

            <!-- Auto Replies -->
            <a href="{{ route('admin.whatsapp.auto-replies') }}" 
               class="p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 mr-4">
                        <i class="fas fa-reply-all text-blue-500"></i>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700">Auto Replies</p>
                        <p class="text-sm text-gray-500">Manage automated responses</p>
                    </div>
                </div>
            </a>

            <!-- Message Templates -->
            <a href="{{ route('admin.whatsapp.templates') }}" 
               class="p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 mr-4">
                        <i class="fas fa-file-alt text-purple-500"></i>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700">Templates</p>
                        <p class="text-sm text-gray-500">Manage message templates</p>
                    </div>
                </div>
            </a>

            <!-- ChatGPT Integration -->
            <a href="{{ route('admin.whatsapp.chatgpt') }}" 
               class="p-6 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 mr-4">
                        <i class="fas fa-robot text-yellow-500"></i>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700">ChatGPT</p>
                        <p class="text-sm text-gray-500">AI response configuration</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-700">Today's Messages</h3>
                <span class="text-2xl font-bold text-green-500">{{ $todayMessages ?? 0 }}</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-700">Active Auto-Replies</h3>
                <span class="text-2xl font-bold text-blue-500">{{ $activeAutoReplies ?? 0 }}</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-700">AI Responses</h3>
                <span class="text-2xl font-bold text-yellow-500">{{ $aiResponses ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Recent Images -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Recent Images</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4" id="recent-images">
                @foreach($recentImages ?? [] as $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $image->file_path) }}" 
                             alt="WhatsApp Image" 
                             class="w-full h-48 object-cover rounded-lg shadow-sm">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <div class="text-white text-center p-4">
                                <p class="font-semibold">{{ $image->phone }}</p>
                                <p class="text-sm">{{ $image->created_at->diffForHumans() }}</p>
                                @if($image->analysis_data)
                                    <p class="text-xs mt-2">{{ Str::limit($image->analysis_data['description'], 100) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Conversations -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Recent Conversations</h3>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-left font-semibold">
                            <th class="px-4 py-3">Phone</th>
                            <th class="px-4 py-3">Message</th>
                            <th class="px-4 py-3">Response Type</th>
                            <th class="px-4 py-3">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentConversations ?? [] as $conversation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $conversation->phone }}</td>
                                <td class="px-4 py-3">{{ Str::limit($conversation->message, 50) }}</td>
                                <td class="px-4 py-3">
                                    @if($conversation->auto_reply_id)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Auto Reply</span>
                                    @elseif($conversation->chatgpt_prompt_id)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">AI</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Manual</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $conversation->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-center text-gray-500">No recent conversations</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Echo for real-time updates
        window.Echo.private('whatsapp.images')
            .listen('.image.received', (e) => {
                const imageData = e.imageData;
                
                // Create new image element
                const imageHtml = `
                    <div class="relative group animate-fade-in">
                        <img src="${imageData.url}" 
                             alt="WhatsApp Image" 
                             class="w-full h-48 object-cover rounded-lg shadow-sm">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <div class="text-white text-center p-4">
                                <p class="font-semibold">${imageData.phone}</p>
                                <p class="text-sm">Just now</p>
                            </div>
                        </div>
                    </div>
                `;

                // Add to the grid
                const container = document.getElementById('recent-images');
                container.insertAdjacentHTML('afterbegin', imageHtml);

                // Remove oldest image if more than 8 images
                const images = container.children;
                if (images.length > 8) {
                    container.removeChild(images[images.length - 1]);
                }

                // Show notification
                const notification = new Notification('New WhatsApp Image', {
                    body: `Received from ${imageData.phone}`,
                    icon: imageData.url
                });
            });

        // Request notification permission if not granted
        if (Notification.permission !== 'granted') {
            Notification.requestPermission();
        }
    });
</script>
@endpush

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>
