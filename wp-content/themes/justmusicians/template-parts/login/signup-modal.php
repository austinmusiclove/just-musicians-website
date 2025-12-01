<div data-popup="quote" class="popup-wrapper w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center p-4" x-show="showSignupModal" x-cloak>
    <div data-trigger="quote" class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"
        x-on:click="showSignupModal = false; signupModalMessage = 'Sign up for an account'; loginModalMessage = 'Sign in to your account';"
    ></div>

    <div class="bg-white relative w-full h-auto md:w-auto flex items-center justify-center border-2 shadow-black-offset border-black" style="max-width: 780px;">

    <img data-trigger="quote" class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"
        x-on:click="showSignupModal = false; signupModalMessage = 'Sign up for an account'; loginModalMessage = 'Sign in to your account';" />

    <div class="slide w-[32rem] grow">


        <div class="flex flex-col justify-center lg:px-8 min-h-full py-12 sm:px-6">
            <div class="flex flex-col items-center sm:mx-auto sm:w-full sm:max-w-md text-center">
                <!--<img class="mx-auto h-20 w-20 mb-4" src="<?php echo get_site_icon_url(); ?>" alt="Site Icon">-->
                <h2 x-text="signupModalMessage" class="mt-6 text-25 font-bold leading-9 tracking-tight mb-12 leading-tight">Sign up for an account</h2>
            </div>
            <div class="sm:mx-auto sm:w-full sm:max-w-[480px]">
                <div class="bg-white px-6 pb-4 sm:px-12">
                    <form class="space-y-2" action="" method="POST"
                        hx-post="<?php echo site_url('/wp-html/v1/register-user'); ?>"
                        hx-target="#sign-up-result">
                        <div>
                            <label for="email" class="block text-sm font-medium leading-6">Email Address</label>
                            <div class="mt-2">
                                <input id="email" name="r_user_email" type="email" autocomplete="email" required class="block w-full rounded-md border border-yellow px-3 py-2 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium leading-6">Password</label>
                            <div class="mt-2">
                                <input id="password" name="r_user_pass" x-bind:type="showPassword ? 'text' : 'password'" autocomplete="current-password" autocapitalize="none" required class="block w-full rounded-md border border-yellow px-3 py-2 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6">
                                <span class="float-right right-[12px] mt-[-29px] relative">
                                    <img class="h-5 w-5 cursor-pointer opacity-100 hover:opacity-50" src="<?php echo get_template_directory_uri() . '/lib/images/icons/eye-password-show.svg'; ?>" x-cloak x-show="showPassword" x-on:click="showPassword = false;"/>
                                    <img class="h-5 w-5 cursor-pointer opacity-50 hover:opacity-100" src="<?php echo get_template_directory_uri() . '/lib/images/icons/eye-password-hide.svg'; ?>" x-cloak x-show="!showPassword" x-on:click="showPassword = true;"/>
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pb-6">
                            <div class="flex items-center">
                                <input id="r_rememberme" name="r_rememberme" type="checkbox" class="h-4 w-4 rounded">
                                <label for="r_rememberme" class="ml-2 block text-sm leading-6">Remember Me</label>
                            </div>
                        </div>
                        <?php if (isset($_GET['lic'])) { ?><input type="hidden" name="lic" value="<?php echo $_GET['lic']; ?>"><?php } ?>
                        <input type="hidden" name="r_csrf" value="<?php echo wp_create_nonce('r-csrf'); ?>">
                        <div>
                            <button type="submit" class="flex w-full justify-center rounded-md bg-yellow px-3 py-1.5 text-sm font-semibold leading-6 text-navy shadow-sm hover:bg-navy hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow mt-4">Sign up</button>
                        </div>
                        <div id="sign-up-result" class="flex items-center text-14 justify-between"></div>

                    </form>
                    <div>
                        <div class="relative mt-4">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t"></div>
                            </div>
                            <div class="relative flex justify-center text-sm font-medium leading-6"><span class="bg-white px-6">Or</span></div>
                        </div>
                        <div class="mt-6 grid gap-4 mt-4"><a href="<?php echo login_with_google(); ?>" class="border border-black flex focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#1D9BF0] focus-visible:outline-offset-2 gap-3 items-center justify-center px-3 py-1.5 rounded-md w-full"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" style="enable-background:new 0 0 150 150;" version="1.1" viewBox="0 0 150 150" width="1.5em" height="1.5em" xml:space="preserve"><style type="text/css">.st0 { fill: #1A73E8; } .st1 { fill: #EA4335; } .st2 { fill: #4285F4; } .st3 { fill: #FBBC04; } .st4 { fill: #34A853; } .st5 { fill: #4CAF50; } .st6 { fill: #1E88E5; } .st7 { fill: #E53935; } .st8 { fill: #C62828; } .st9 { fill: #FBC02D; } .st10 { fill: #1565C0; } .st11 { fill: #2E7D32; } .st12 { fill: #F6B704; } .st13 { fill: #E54335; } .st14 { fill: #4280EF; } .st15 { fill: #34A353; } .st16 { clip-path: url(#SVGID_2_); } .st17 { fill: #188038; } .st18 { opacity: 0.2; fill: #FFFFFF; enable-background: new    ; } .st19 { opacity: 0.3; fill: #0D652D; enable-background: new    ; } .st20 { clip-path: url(#SVGID_4_); } .st21 { opacity: 0.3; fill: url(#_45_shadow_1_); enable-background: new    ; } .st22 { clip-path: url(#SVGID_6_); } .st23 { fill: #FA7B17; } .st24 { opacity: 0.3; fill: #174EA6; enable-background: new    ; } .st25 { opacity: 0.3; fill: #A50E0E; enable-background: new    ; } .st26 { opacity: 0.3; fill: #E37400; enable-background: new    ; } .st27 { fill: url(#Finish_mask_1_); } .st28 { fill: #FFFFFF; } .st29 { fill: #0C9D58; } .st30 { opacity: 0.2; fill: #004D40; enable-background: new    ; } .st31 { opacity: 0.2; fill: #3E2723; enable-background: new    ; } .st32 { fill: #FFC107; } .st33 { opacity: 0.2; fill: #1A237E; enable-background: new    ; } .st34 { opacity: 0.2; } .st35 { fill: #1A237E; } .st36 { fill: url(#SVGID_7_); } .st37 { fill: #FBBC05; } .st38 { clip-path: url(#SVGID_9_); fill: #E53935; } .st39 { clip-path: url(#SVGID_11_); fill: #FBC02D; } .st40 { clip-path: url(#SVGID_13_); fill: #E53935; } .st41 { clip-path: url(#SVGID_15_); fill: #FBC02D; }</style><g><path class="st14" d="M120,76.1c0-3.1-0.3-6.3-0.8-9.3H75.9v17.7h24.8c-1,5.7-4.3,10.7-9.2,13.9l14.8,11.5   C115,101.8,120,90,120,76.1L120,76.1z"/><path class="st15" d="M75.9,120.9c12.4,0,22.8-4.1,30.4-11.1L91.5,98.4c-4.1,2.8-9.4,4.4-15.6,4.4c-12,0-22.1-8.1-25.8-18.9   L34.9,95.6C42.7,111.1,58.5,120.9,75.9,120.9z"/><path class="st12" d="M50.1,83.8c-1.9-5.7-1.9-11.9,0-17.6L34.9,54.4c-6.5,13-6.5,28.3,0,41.2L50.1,83.8z"/><path class="st13" d="M75.9,47.3c6.5-0.1,12.9,2.4,17.6,6.9L106.6,41C98.3,33.2,87.3,29,75.9,29.1c-17.4,0-33.2,9.8-41,25.3   l15.2,11.8C53.8,55.3,63.9,47.3,75.9,47.3z"/></g></svg><span class="text-sm font-semibold leading-6">Sign up with Google</span> </a>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-center text-sm mt-4">Already have an account? <span class="hover:underline underline cursor-pointer" x-on:click="showSignupModal = false; showLoginModal = true;">Sign in here</span></p>
        </div>


    </div>
    </div>
</div>

