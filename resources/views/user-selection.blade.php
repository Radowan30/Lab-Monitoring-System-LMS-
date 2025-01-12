<x-guest-layout>
    <style>
        .container {
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 30px;
            color: #333;
        }

        .button {
            display: inline-block;
            width: 80%;
            /* Adjusted width for better alignment */
            margin: 10px auto;
            padding: 15px;
            border: none;
            border-radius: 5px;
            background: linear-gradient(to right, #60a5fa, #2563eb);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .button:hover {
            opacity: 0.9;
        }
    </style>


    <div class="container">
        <h1>Continue as...</h1>
        <a href="{{ route('dashboard') }}" class="button">Normal User</a>
        <a href="{{route('admin.view')}}" class="button">Admin</a>
    </div>

</x-guest-layout>
