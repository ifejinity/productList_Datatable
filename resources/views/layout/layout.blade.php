<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    {{-- cdn --}}
    @include('partials.__cdn')
</head>
<body class="w-full md:px-[10%] px-[5%] bg-indigo-400 min-h-screen flex justify-center items-center">

    {{-- body --}}
    @yield('body')

    {{-- custom js --}}
    @yield('js')
    {{-- response alert --}}
    <script>
        function success(message) {
            Toastify({
                text: message,
                className: "info",
                style: {
                    background: "#22c55e",
                }
            }).showToast();
        }
        function error(message) {
            Toastify({
                text: message,
                className: "info",
                style: {
                    background: "#ef4444",
                }
            }).showToast();
        }
    </script>
</body>
</html>