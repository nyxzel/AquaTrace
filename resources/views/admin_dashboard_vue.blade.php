<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard | AquaTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/adminDashboard_style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <admin-dashboard
            :initial-vessel-count="{{ $vesselCount }}"
            :initial-port-count="{{ $portCount }}"
            :initial-user-count="{{ $userCount }}"
            :initial-report-count="{{ $reportCount }}"
            :initial-vessels='@json($vessels)'
            :initial-ports='@json($ports)'
            :initial-pending-owners='@json($pendingOwners)'></admin-dashboard>
    </div>
</body>

</html>