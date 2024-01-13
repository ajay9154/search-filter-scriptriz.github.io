
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const suggestionsList = document.getElementById('suggestions');

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.trim();

        if (query === '') {
            suggestionsList.style.display = 'none';
            return;
        }

        // Fetch suggestions from the server using AJAX (you may need to adjust the URL)
        fetch(`suggestions.php?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(suggestions => {
                displaySuggestions(suggestions);
            })
            .catch(error => {
                console.error('Error fetching suggestions:', error);
            });
    });

    function displaySuggestions(suggestions) {
        suggestionsList.innerHTML = '';

        if (suggestions.length === 0) {
            suggestionsList.style.display = 'none';
            return;
        }

        suggestions.forEach(suggestion => {
            const li = document.createElement('li');
            li.textContent = suggestion;
            li.addEventListener('click', function () {
                searchInput.value = suggestion;
                suggestionsList.style.display = 'none';
            });
            suggestionsList.appendChild(li);
        });

        suggestionsList.style.display = 'block';
    }

    // Close suggestions when clicking outside the input and suggestions list
    document.addEventListener('click', function (event) {
        if (!event.target.matches('#searchInput') && !event.target.matches('#suggestions li')) {
            suggestionsList.style.display = 'none';
        }
    });
});
