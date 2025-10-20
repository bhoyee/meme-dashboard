<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Whale Dashboard</title>
  
  <!-- Load Tailwind CSS and Chart.js here -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- PrimeVue Theme via CDN -->
  <link rel="stylesheet" href="https://unpkg.com/primevue/resources/themes/lara-light-indigo/theme.css">
  <link rel="stylesheet" href="https://unpkg.com/primevue/resources/primevue.min.css">
  <link rel="stylesheet" href="https://unpkg.com/primeicons/primeicons.css">

  <style>
    * {
      font-family: 'Inter', sans-serif;
    }
    
    .sidebar {
      transition: all 0.3s ease;
    }
    
    .card-hover {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        position: fixed;
        z-index: 50;
        height: 100vh;
      }
      
      .sidebar.active {
        transform: translateX(0);
      }
      
      .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 40;
      }
      
      .overlay.active {
        display: block;
      }
    }
  </style>
  
  @vite('resources/js/app.js')
</head>
<body>
  <div id="app"></div>
</body>
</html>