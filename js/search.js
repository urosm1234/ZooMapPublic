var drawnPath = null, returnMarker = null, filteredOptions = null;
        
        function toggleResults()
        {
            const searchResults = document.getElementById('searchResults');
            if(searchResults.style.display != 'flex' && filteredOptions != null && filteredOptions.length > 0)
            {
                searchResults.style.display = 'flex';
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
                    div.addEventListener('click', () => optionSelected(option));
                    searchResults.appendChild(div);
                });
                searchResults.style.display = 'flex'; // Show the filtered list
            } else {
                searchResults.style.display = 'none'; // Hide if no results
            }
        }

        // Filter the list based on user input
        function filterOptions() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            if(searchTerm == "")
                    {
                        filteredOptions = null;
                        return;
                    }
            filteredOptions = iconArray.filter(animal => 
            animal.title == searchTerm || animal.name == searchTerm
            )
            if(filteredOptions.length == 0) {
                filteredOptions = iconArray.filter(animal =>
                animal.title && (animal.title.toLowerCase().includes(searchTerm) || animal.name.toLowerCase().includes(searchTerm))
                );
            }
            

            displayOptions(filteredOptions);
        }

        function optionSelected(option)
        {
            const searchResults = document.getElementById('searchResults');
            map.setZoom(2);
            setTimeout(() =>{
                map.panTo([option.coordinateh, option.coordinatew], {animate:true});
            }, 300);
            document.getElementById('searchInput').value = "";
            if(navigator.geolocation)
            {
                if(drawnPath)
                map.removeLayer(drawnPath);

                if(returnMarker)
                map.removeLayer(returnMarker);

                let pathAtrr = drawPath(pointCurr, [option.coordinateh, option.coordinatew]);
                drawnPath = pathAtrr[0];
                returnMarker = pathAtrr[1];
            }
            else
            console.log('navigator not set')
            searchResults.style.display = 'none'; // Hide after selection
        }

        function closeSearchList()
        {
            const searchResults = document.getElementById('searchResults');
            searchResults.style.display = 'none';
        }

        function searchSelected()
        {
            const searchResults = document.getElementById('searchResults');
            if(searchResults.style.display == 'flex')
            {
                searchResults.style.display = 'none';
                document.activeElement.blur();
                return;
            }
            if(filteredOptions != null && filteredOptions.length > 0)
            {
                searchResults.style.display = 'flex';
            }
        }