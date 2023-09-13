<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    @include('partials.__cdn')
</head>
<body class="w-full md:px-[10%] px-[5%] bg-indigo-400 min-h-screen flex justify-center items-center">

    @yield('body')
    
    {{-- datatables js --}}
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    {{-- toastify js --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    {{-- sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- custom js --}}
    @yield('js')
    <script>
        // response alert
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