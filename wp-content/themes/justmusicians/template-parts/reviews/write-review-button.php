<!-- Not Logged In Write Review Button -->
<button type="button" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2"
    x-show="!loggedIn" x-cloak
    x-on:click="showSignupModal = true; signupModalMessage = 'Sign up to write a review'"
>Write a Review</button>

<!-- Logged In Write Review Button -->
<button type="button" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2"
    x-show="loggedIn" x-cloak
>Write a Review</button>
