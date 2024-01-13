<?php

// Sample page titles (replace this with your actual page titles logic)
$pageTitles = [
    'Introduction to PHP',
    'Html Tutorial',
    'JavaScript Basics',
    'HTML5 and CSS3 Tutorial',
    'Python Programming Guide',
    'Java for Beginners',
    'C++ Fundamentals',
    'Ruby on Rails Tutorial',
    'Swift Programming Language',
    'React.js Crash Course',
    'Angular Framework Overview',
    'Vue.js Introduction',
    'Node.js Essentials',
];

// Get the search query from the URL
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Filter page titles based on the query (case-insensitive)
$filteredTitles = array_filter($pageTitles, function ($title) use ($query) {
    return stripos($title, $query) !== false;
});

// Convert the filtered titles to a numeric array
$filteredTitlesArray = array_values($filteredTitles);

// Return suggestions as JSON
header('Content-Type: application/json');
echo json_encode($filteredTitlesArray);
?>