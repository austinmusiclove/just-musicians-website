class Helper {
    constructor () {}
    convertStringToSlug(string) {
        let result = string.toLowerCase(); // convert to lower case
        result = result.replaceAll(/[^a-z0-9\-]/g, '-'); // convert non alphanumerica to -
        result = result.replaceAll(/-+/g, '-'); // remove consecutive hyphens
        result = result.replace(/^-+|-+$/g, ""); // trim hyphens from the start and end
        return result;
    }
    getRandomNumber(numDigits) {
        const min = Math.pow(10, numDigits - 1); // Minimum value (e.g., 100 for numDigits=3)
        const max = Math.pow(10, numDigits) - 1; // Maximum value (e.g., 999 for numDigits=3)
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch (err) {
            return false;
        }
    }
    show(element, display) {
        let displayValue = (display) ? display : 'block';
        element.style.display = displayValue;
    }
    hide(element) {
        element.style.display = "none";
    }
}

export default Helper;
