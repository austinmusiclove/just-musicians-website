function addTag(alco, input, errorEventName)    {
    var value = input.value.trim();

    // Handle empty
    if (value == '' || value == '/' || value == "'") { return; }

    // Leading or trailing '
    if (value.startsWith("'") || value.endsWith("'")) {
        alco.$dispatch(errorEventName, {'message': 'Cannot start or end with \''});
        return;
    }

    // Handle disallowed characters
    var disallowedChars = ['[', ']', '{', '}', '|', '\\', ';', ':', '"', ',', '?', '<', '>'];
    for (let char of disallowedChars) {
        if (value.includes(char)) {
            alco.$dispatch(errorEventName, {'message': `Remove disallowed characters: ${disallowedChars.join(' ')}`});
            return;
        }
    }

    // Handle duplicate
    if (alco.tags.includes(value)) {
        alco.$dispatch(errorEventName, {'message': 'No duplicates allowed'});
        return;
    }

    // Add tag
    alco.tags.push(value);
    input.value = '';

}

function removeTag(alco, index) {
    alco.tags.splice(index, 1);
}
