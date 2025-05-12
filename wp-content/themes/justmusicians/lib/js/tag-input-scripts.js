function addTag(alpineComponent, event, errorEventName)    {
    var value = event.target.value.trim();

    // Handle empty
    if (value == '' || value == '/' || value == "'") { return; }

    // Leading or trailing '
    if (value.startsWith("'") || value.endsWith("'")) {
        alpineComponent.$dispatch(errorEventName, {'message': 'Cannot start or end with \''});
        return;
    }

    // Handle disallowed characters
    var disallowedChars = ['[', ']', '{', '}', '|', '\\', ';', ':', '"', ',', '?', '<', '>'];
    for (let char of disallowedChars) {
        if (value.includes(char)) {
            alpineComponent.$dispatch(errorEventName, {'message': `Remove disallowed characters: ${disallowedChars.join(' ')}`});
            return;
        }
    }

    // Handle duplicate
    if (alpineComponent.tags.includes(value)) {
        alpineComponent.$dispatch(errorEventName, {'message': 'No duplicates allowed'});
        return;
    }

    // Add tag
    alpineComponent.tags.push(value);
    event.target.value = '';

}

function removeTag(alpineComponent, index) {
    alpineComponent.tags.splice(index, 1);
}
