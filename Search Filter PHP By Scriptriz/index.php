<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<section>
        <div class="container">
            <div id="navbar" class="main-page">
                <h1>Learn To Code</h1>

                <div class="search-filter">
                     <form id="search-form" action="results.php" method="GET">
                    <input type="text" name="query" id="searchInput" placeholder="Search..." value="<?php echo htmlspecialchars($_GET['query'] ?? ''); ?>" autocomplete="off">
                    <ul id="suggestions"></ul>
                    <button type="submit">Search</button>
                </form>
                </div>
               
            </div>
        </div>
    </section>
    <script src="script.js"></script>
</body>
</html>