<x-guest-layout>

    <div class="backdrop-blur-md p-6 md:p-8">

        <div class="flex justify-center mb-6">
            <div class="p-4 rounded-full text-white text-2xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-center mb-6 text-white">Login</h2>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" name="email" placeholder="Email"
                        class="w-full pl-10 pr-4 py-2 rounded-lg bg-white/90 border border-white/30 text-gray-800 placeholder:text-gray-500 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        required autofocus>
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" placeholder="Password"
                        class="w-full pl-10 pr-4 py-2 rounded-lg bg-white/90 border border-white/30 text-gray-800 placeholder:text-gray-500 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        required>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-4 text-sm">
                <label class="flex items-center text-white">
                    <input type="checkbox" name="remember" class="mr-2">
                    Remember me
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-white hover:underline">
                        Forgot password
                    </a>
                @endif
            </div>

            <button type="submit"
                class="w-full mt-6 bg-gradient-to-r from-blue-500 to-blue-700 hover:opacity-90 hover:scale-[1.02] active:scale-[0.98] text-white py-2 rounded-lg font-semibold transition">
                LOGIN
            </button>

        </form>
    </div>

</x-guest-layout>
