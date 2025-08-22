<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - FreedDoctor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-green-50 via-white to-blue-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-full p-3">
                        <i class="fas fa-heart text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">FreedDoctor</h1>
                        <p class="text-sm text-gray-600">Healthcare for Everyone</p>
                    </div>
                </div>
                <nav class="hidden md:flex space-x-6">
                    <a href="{{ route('user.dashboard') }}" class="text-gray-600 hover:text-blue-600 transition">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-6 py-12">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Success Animation -->
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-r from-green-400 to-green-600 rounded-full mb-6 animate-pulse">
                    <i class="fas fa-check text-white text-5xl"></i>
                </div>
                <h1 class="text-5xl font-bold text-gray-800 mb-4">
                    Thank You, {{ $sponsor->name }}!
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    Your generous sponsorship has been successfully processed.
                </p>
            </div>

            <!-- Payment Details -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-receipt text-green-600 mr-3"></i>
                    Payment Confirmation
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="font-semibold text-green-800 mb-4">Sponsorship Details</h3>
                        <div class="space-y-2 text-left">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount:</span>
                                <span class="font-bold text-green-600">₹{{ number_format($sponsor->amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Status:</span>
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                    {{ $sponsor->payment_status_label }}
                                </span>
                            </div>
                            @if($sponsor->payment_id)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment ID:</span>
                                <span class="font-mono text-sm">{{ $sponsor->payment_id }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date:</span>
                                <span>{{ $sponsor->payment_date ? $sponsor->payment_date->format('M j, Y g:i A') : 'Now' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-4">Campaign Information</h3>
                        <div class="space-y-2 text-left">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Campaign:</span>
                                <span class="font-medium">{{ $sponsor->campaign->title }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Location:</span>
                                <span>{{ $sponsor->campaign->location }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Doctor:</span>
                                <span>Dr. {{ $sponsor->campaign->doctor->doctor_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration:</span>
                                <span class="text-sm">
                                    {{ \Carbon\Carbon::parse($sponsor->campaign->start_date)->format('M j') }} - 
                                    {{ \Carbon\Carbon::parse($sponsor->campaign->end_date)->format('M j, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Impact Message -->
            <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-2xl p-8 mb-8">
                <h2 class="text-3xl font-bold mb-4">
                    <i class="fas fa-hands-helping mr-3"></i>
                    Your Impact
                </h2>
                <p class="text-xl mb-6">
                    With your sponsorship of ₹{{ number_format($sponsor->amount, 2) }}, you're helping provide 
                    free healthcare services to those who need it most.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-2">
                            {{ floor($sponsor->amount / 500) }}+
                        </div>
                        <div class="text-sm opacity-90">Patients can be treated</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-2">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="text-sm opacity-90">Lives touched</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-2">
                            <i class="fas fa-smile"></i>
                        </div>
                        <div class="text-sm opacity-90">Families helped</div>
                    </div>
                </div>
            </div>

            <!-- What's Next -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-arrow-right text-blue-600 mr-3"></i>
                    What Happens Next?
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-6 bg-blue-50 rounded-lg">
                        <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-envelope text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">Email Confirmation</h3>
                        <p class="text-sm text-gray-600">You'll receive a detailed receipt and thank you email shortly.</p>
                    </div>

                    <div class="text-center p-6 bg-green-50 rounded-lg">
                        <div class="w-16 h-16 bg-green-600 text-white rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">Campaign Updates</h3>
                        <p class="text-sm text-gray-600">We'll keep you updated on the campaign's progress and impact.</p>
                    </div>

                    <div class="text-center p-6 bg-purple-50 rounded-lg">
                        <div class="w-16 h-16 bg-purple-600 text-white rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-trophy text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">Recognition</h3>
                        <p class="text-sm text-gray-600">Your contribution will be acknowledged in our campaign reports.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('user.dashboard') }}" 
                   class="bg-gradient-to-r from-blue-600 to-green-600 text-white px-8 py-4 rounded-lg font-semibold hover:from-blue-700 hover:to-green-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    <i class="fas fa-home mr-2"></i>Back to Home
                </a>
                <a href="{{ route('sponsor.form') }}" 
                   class="bg-white text-gray-800 border-2 border-gray-300 px-8 py-4 rounded-lg font-semibold hover:bg-gray-50 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Sponsor Another Campaign
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="container mx-auto px-6 text-center">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-full p-3 mr-4">
                    <i class="fas fa-heart text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold">FreedDoctor</h3>
                    <p class="text-sm text-gray-400">Healthcare for Everyone</p>
                </div>
            </div>
            <p class="text-gray-400">
                Thank you for being a champion of accessible healthcare!
            </p>
        </div>
    </footer>

    <!-- Confetti Animation -->
    <script>
        // Simple confetti effect
        function createConfetti() {
            const colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7'];
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.style.position = 'fixed';
                confetti.style.width = '10px';
                confetti.style.height = '10px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.animationDuration = Math.random() * 3 + 2 + 's';
                confetti.style.animationName = 'fall';
                confetti.style.animationTimingFunction = 'linear';
                confetti.style.animationFillMode = 'forwards';
                confetti.style.pointerEvents = 'none';
                document.body.appendChild(confetti);
                
                setTimeout(() => {
                    confetti.remove();
                }, 5000);
            }
        }

        // Add CSS for falling animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fall {
                0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
                100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
            }
        `;
        document.head.appendChild(style);

        // Start confetti
        createConfetti();
        setTimeout(createConfetti, 1000);
    </script>
</body>
</html>
