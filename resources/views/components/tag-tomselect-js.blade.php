
<style type="text/css">
    .ts-control:not(.rtl) {
/*    .ts-wrapper.multi.has-istems .ts-control {*/
        border: 1px solid #aaa;
        border-radius: 7px;
    }
</style>


<script type="text/javascript"> 

    new TomSelect('#select-tag', {
        valueField: 'tag_id',  
        labelField: 'tag_title',  
        searchField: 'tag_title', 
        highlight: false, 
        plugins: ['remove_button'],    
        mode: 'multi', 
        maxItems: null, 
        load: function(query, callback) {
            search_data = query;
            var url = '{{url('html_tag_list')}}'; 

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ search: query })
            })
            .then(response => response.json())
            .then(json => callback(json))
            .catch(() => callback());
        }, 
        onItemAdd: function() {
            this.setTextboxValue('');  // Clear the search input after selecting an item
            this.refreshOptions(false); // Refresh options to show the full list
        }
    });  
</script>