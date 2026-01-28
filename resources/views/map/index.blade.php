<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>GIS Pendidikan</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #map-container {
            position: relative;
            height: 100%;
            width: 100%;
        }

        #dock {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(51, 51, 51, 0.8);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
            /* Ensures the dock stays on top */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            /* Adds a shadow for a docked effect */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #dock input[type="text"] {
            width: 200px;
            padding: 5px;
            margin-right: 10px;
            border: none;
            border-radius: 3px;
            background-color: rgba(255, 255, 255, 0.8);
            color: black;
        }

        #dock a {
            text-decoration: none;
            color: white;
            margin-left: 10px;
        }

        #map {
            height: 100%;
            width: 100%;
        }

        datalist {
            background-color: white;
            color: black;
            border: 1px solid #ccc;
            border-radius: 3px;
            max-height: 150px;
            overflow-y: auto;
        }

        datalist option {
            padding: 5px;
        }
    </style>
</head>

<body>
    <div id="map-container">
        <div id="dock">
            <input type="text" id="search-input" list="school-suggestions" placeholder="Search..." />
            <datalist id="school-suggestions">
                <!-- Options will be populated by JavaScript -->
            </datalist>
            <a href="#" class="mx-2">School</a>
            <a href="#" class="mx-2">Information</a>
            <a href="{{ route('admin.dashboard') }}" class="mx-2">Login</a>
        </div>
        <div id="map"></div>
    </div>
</body>

</html>

@vite('resources/js/gis/initial-map.js')
@vite('resources/js/map/map.js')

<script></script>
