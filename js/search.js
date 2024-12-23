function toggleResults()
        {
            const searchResults = document.getElementById('searchResults');
            if(searchResults.style.display != 'block')
            {
                searchResults.style.display = 'block';
            }
            else
            {
                searchResults.style.display = 'none';
            }
        }
        // Display all options initially
        function displayOptions(list) {
            const searchResults = document.getElementById('searchResults');
            searchResults.innerHTML = ''; // Clear previous results

            if (list.length > 0) {
                list.forEach(option => {
                    const div = document.createElement('div');
                    div.textContent = option.title;
                    div.classList.add('search-item');
                    div.addEventListener('click', () => {
                        map.setZoom(2);
                        setTimeout(() =>{
                            map.panTo([option.coordinateh, option.coordinatew], {animate:true});
                        }, 300);
                        document.getElementById('searchInput').value = "";
                        searchResults.style.display = 'none'; // Hide after selection
                    });
                    searchResults.appendChild(div);
                });
                searchResults.style.display = 'block'; // Show the filtered list
            } else {
                searchResults.style.display = 'none'; // Hide if no results
            }
        }

        // Filter the list based on user input
        function filterOptions() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            let filteredOptions = iconArray.filter(animal => 
            animal.title == searchTerm || animal.name == searchTerm
            )
            if(filteredOptions.length == 0) {
                filteredOptions = iconArray.filter(animal =>
                animal.title.toLowerCase().includes(searchTerm) || animal.name.toLowerCase().includes(searchTerm)
                );
            }
            

            displayOptions(filteredOptions);
        }