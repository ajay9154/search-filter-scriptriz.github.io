<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <section>
        <div class="results-container">

            <?php
function performSearch($query, $rootDirectory)
{
    $results = [];

    // Get all PHP files recursively from the specified root directory
    $files = getAllFiles($rootDirectory);

    // Split the search query into individual words using a regular expression
    preg_match_all('/\b\w+\b/', $query, $matches);
    $queryWords = $matches[0];

    // Loop through each file and check if it contains all the query words
    foreach ($files as $file) {
        $fileContent = file_get_contents($file);
        $fileContentLower = strtolower($fileContent);

        // Check if all query words are present in the content
        $matchesAllWords = true;
        foreach ($queryWords as $word) {
            if (strpos($fileContentLower, strtolower($word)) === false) {
                $matchesAllWords = false;
                break;
            }
        }

        if ($matchesAllWords) {
            // Add the file to the results
            $results[] = [
                'file' => $file,
                'title' => getTitleFromPHPFile($file),
                'description' => getDescriptionFromPHPFile($file),
            ];
        }
    }

    return $results;
}


function calculateRelevance($title, $description, $queryWords)
{
    // Calculate relevance based on the number of query words present in the title and description
    $titleMatchCount = 0;
    $descriptionMatchCount = 0;

    foreach ($queryWords as $word) {
        if (strpos($title, strtolower($word)) !== false) {
            $titleMatchCount++;
        }

        if (strpos($description, strtolower($word)) !== false) {
            $descriptionMatchCount++;
        }
    }

    // You can customize the relevance calculation based on your requirements
    return $titleMatchCount + $descriptionMatchCount;
}

            // Function to extract the description from a PHP file
            function getDescriptionFromPHPFile($file)
            {
                // Read the contents of the PHP file
                $contents = file_get_contents($file);

                // Use a regular expression to extract the description from a meta tag
                preg_match('/<meta name="description" content="(.*?)"\s*\/?>/i', $contents, $matches);

                // Return the extracted description or an empty string if not found
                return isset($matches[1]) ? $matches[1] : '';
            }
            // Function to get all files recursively from a directory
            function getAllFiles($dir)
            {
                $files = [];

                // Get all files and directories in the specified directory
                $items = scandir($dir);

                // Loop through each item
                foreach ($items as $item) {
                    if ($item !== '.' && $item !== '..') {
                        $path = $dir . '/' . $item;

                        // Check if the item is a directory
                        if (is_dir($path)) {
                            // Recursively get files from the subdirectory
                            $files = array_merge($files, getAllFiles($path));
                        } elseif (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
                            // Add only PHP files to the list
                            $files[] = $path;
                        }
                    }
                }

                return $files;
            }


            $rootDirectory = './'; // You may need to adjust this based on your directory structure
            
          
            // Get the search query from the URL
            $query = isset($_GET['query']) ? trim($_GET['query']) : '';

            // Perform the search using the updated performSearch function
            $searchResults = performSearch($query, $rootDirectory);

            // Display the entered query
            echo '<h1>Search Results for "' . htmlspecialchars($query) . '"</h1>';


            // Display the entered query and result count
            $resultCount = count($searchResults);
            echo '<div class="result-count">Found ' . $resultCount . ' results</div>';


            // Display the results with a clear title, URL, and description
            foreach ($searchResults as $result) {
                $resultUrl = substr($result['file'], strlen($rootDirectory) + 1); // Remove the root directory from the URL
                $pageTitle = htmlspecialchars($result['title']);
                $pageUrl = htmlspecialchars($resultUrl);
                $pageDescription = htmlspecialchars($result['description']);

                echo '<div class="result">';
                echo '<a href="' . $pageUrl . '"><div class="result-title">' . $pageTitle . '</div></a>';
                echo '<div class="result-url">' . $pageUrl . '</div>';
                echo '<div class="result-description">' . $pageDescription . '</div>';
                echo '</div>';
            }

         
            // Function to extract the title from a PHP file
            function getTitleFromPHPFile($file)
            {
                // Read the contents of the PHP file
                $contents = file_get_contents($file);

                // Use a regular expression to extract the title from the <title> tag
                preg_match('/<title>(.*?)<\/title>/i', $contents, $matches);

                // Return the extracted title or use the file name if not found
                return isset($matches[1]) ? $matches[1] : pathinfo($file, PATHINFO_FILENAME);
            }

            ?>
            <div class="result-page-pagination">
                 <ul class="pagination" id="pagination"></ul>
            </div>



        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
    const itemsPerPage = 10; // Number of items per page
    const items = document.querySelectorAll('.result'); // Correct selector
    const pagination = document.getElementById('pagination');

    let currentPage = 1;

    function showPage(page) {
    const startIndex = (page - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;

    items.forEach((item, index) => {
        if (index >= startIndex && index < endIndex) {
            item.style.opacity = 1;
            item.style.visibility = 'visible'; // Show the item
            item.style.transition = 'opacity 0.3s ease-in-out';
        } else {
            item.style.opacity = 0;
            item.style.visibility = 'hidden'; // Hide the item
            item.style.transition = 'opacity 0.3s ease-in-out';
        }
    });

    // Set a timeout to change display property after the transition
    setTimeout(() => {
        items.forEach((item, index) => {
            if (index >= startIndex && index < endIndex) {
                item.style.display = 'block'; // Show the item
            } else {
                item.style.display = 'none'; // Hide the item
            }
        });
    }, 500); // Adjust the timeout based on your transition duration
}


    function updatePagination() {
        const totalPages = Math.ceil(items.length / itemsPerPage);

        // Clear existing pagination
        pagination.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.innerText = i;

            // Highlight the current page
            if (i === currentPage) {
                li.classList.add('active');
            }

            li.addEventListener('click', function () {
                currentPage = i;
                showPage(currentPage);
                updatePagination();
            });

            pagination.appendChild(li);
        }
    }

    // Initial setup
    showPage(currentPage);
    updatePagination();
});

  </script>



</body>

</html>