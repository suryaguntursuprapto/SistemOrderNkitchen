<footer class="bg-gray-800 text-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <!-- Brand -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="N-Kitchen" class="w-10 h-10 rounded-xl shadow-lg object-cover">
                <div>
                    <h3 class="text-lg font-bold text-white">N-Kitchen</h3>
                    <p class="text-gray-400 text-sm">Cita Rasa Authentic</p>
                </div>
            </div>

            <!-- Copyright -->
            <div class="text-center md:text-right">
                <p class="text-gray-300 text-sm">
                    &copy; {{ date('Y') }} N-Kitchen. All rights reserved.
                </p>
                <p class="text-gray-400 text-xs mt-1">
                    Made with ❤️ 
                </p>
            </div>
        </div>
    </div>
</footer>