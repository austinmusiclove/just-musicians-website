function addTag(alpineComponent, event, errorEventName)    {
    var value = event.target.value.trim();

    // Handle empty
    if (value == '') { return; }

    // Handle disallowed characters
    if (value.includes(',') || value.includes('"')) {
        alpineComponent.$dispatch(errorEventName, {'message': 'No commas or quotes allowed'});

    // Handle duplicate
    } else if (alpineComponent.tags.includes(value)) {
        alpineComponent.$dispatch(errorEventName, {'message': 'No duplicates allowed'});

    // Add tag
    } else {
        alpineComponent.tags.push(value);
        event.target.value = '';
    }
}
function removeTag(index) {
    alpineComponent.tags.splice(index, 1);
}
