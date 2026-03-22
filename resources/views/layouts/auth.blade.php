<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Login') — BillifyStay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: { sidebar:'#0f172a', gold:'#d4a84b', cream:'#f8f6f1' },
                fontFamily: { sans:['DM Sans','sans-serif'], display:['Playfair Display','serif'] }
            }}
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family:'DM Sans',sans-serif; }
        input:focus { outline:none; box-shadow:0 0 0 3px rgba(212,168,75,0.2); border-color:#d4a84b !important; }
    </style>
</head>
<body class="bg-sidebar min-h-screen flex items-center justify-center p-4">
    @yield('content')
</body>
</html>
