@extends('components.layout-login')

@section('title', content: 'Login')

@section('content')
    <section class="bg-[#050A30]">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="w-full bg-white rounded-lg shadow border md:mt-0 sm:max-w-md xl:p-0">
                <div class="p-6">
                    <h1 class="text-3xl font-bold leading-tight tracking-tight text-black">
                        SEKAR SATRIA GROUP
                    </h1>
                    <p class="leading-tight tracking-tight text-[#000C66]">
                        Masukan username dan password untuk login!
                    </p>
                    <form class="mt-8 space-y-3" action="{{ route('login.process') }}" method="POST">
                        @csrf
                        <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-black">Username</label>
                            <input type="text" name="username" id="username"
                                class="bg-white border border-gray-500 text-black rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5"
                                placeholder="Masukan username" required="">
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-black">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                    class="bg-white border border-gray-500 text-black rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5"
                                    placeholder="Masukan password" required="">
                                <button type="button" id="togglePassword"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-600">
                                    <x-tabler-eye-off id="eyeIconOff" class="w-5 h-5 hidden" />
                                    <x-tabler-eye id="eyeIconOn" class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full mt-10 text-white bg-[#050A30] border border-gray-700 transition-colors duration-300 ease-in-out hover:bg-white hover:text-black focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIconOff = document.getElementById('eyeIconOff');
        const eyeIconOn = document.getElementById('eyeIconOn');

        togglePassword.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeIconOff.classList.toggle('hidden');
            eyeIconOn.classList.toggle('hidden');
        });
    </script>
@endsection
