<head>
    <title>Rafael INC incorporated asocieshon company</title>
</head>
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div>
       <center><h4 class="mt-6 text-xl text-gray-900 dark:text-white">SU CODIGO DE VERIFICACIÓN ES</h4></center>
    </div>
    <div>
       <center><b><h2 class="underline mt-12 text-xl font-bold text-gray-900 dark:text-white"> {{ $codigo }} </h2></b></center>
    </div>
</x-guest-layout>