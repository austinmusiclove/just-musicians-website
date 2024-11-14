<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cube_blog
 */

get_header();
?>

<div id="content-wrap" class="container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="single-page-wrapper">
				<?php
                    function get_comp_structure_string($comp_structure) {
                        if (in_array('Versus', $comp_structure)) {
                            return join(" vs " , array(sanitize_text_field($_POST['versus_comp_1']), sanitize_text_field($_POST['versus_comp_2'])));
                        } else if (in_array('Tips', $comp_structure) && count($comp_structure) == 1) {
                            return 'Tips';
                        } else if (in_array('In Kind', $comp_structure) && count($comp_structure) == 1) {
                            return 'In Kind';
                        } else if (in_array('In Kind', $comp_structure) && in_array('Tips', $comp_structure) && count($comp_structure) == 2) {
                            return 'In Kind and Tips';
                        } else {
                            $comp_structures = array();
                            if (in_array('Guarantee', $comp_structure)) { array_push($comp_structures, 'Guarantee'); }
                            if (in_array('Door Deal', $comp_structure)) { array_push($comp_structures, 'Door Deal'); }
                            if (in_array('Bar Deal', $comp_structure)) { array_push($comp_structures, 'Bar Deal'); }
                            return join(" plus ", $comp_structures);
                        }
                        return 'Unknown';
                    }

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $venue_name = sanitize_text_field($_POST['venue_name']);
                        $performing_act_name = sanitize_text_field($_POST['performing_act_name']);
                        $performance_date = sanitize_text_field($_POST['performance_date']);
                        $comp_structure = (isset($_POST['comp_structure'])) ? array_map('sanitize_text_field', $_POST['comp_structure']) : array();
                        $comp_structure_string = get_comp_structure_string($comp_structure);
                        $backline = (isset($_POST['backline'])) ? array_filter(array_map('sanitize_text_field', $_POST['backline'])) : array();

                        $review_post = array(
                            'post_title'   => $performing_act_name . ' - ' . $venue_name . ' - ' . $performance_date,
                            'post_status'  => 'pending',
                            'post_type'    => 'venue_review',
                            'meta_input'   => array(
                                'performance' => sanitize_text_field($_POST['performance_post_id']),
                                'venue_name' => $venue_name,
                                'venue_post_id' => sanitize_text_field($_POST['venue_id']),
                                'venue' => sanitize_text_field($_POST['venue_id']),
                                'performing_act_name' => $performing_act_name,
                                'middle_man' => sanitize_text_field($_POST['middle_man']),
                                'gig_type' => sanitize_text_field($_POST['gig_type']),
                                'performance_date' => $performance_date,
                                'performance_start_time' => sanitize_text_field($_POST['performance_start_time']),
                                'minutes_performed' => sanitize_text_field($_POST['minutes_performed']), // add field
                                'hours_performed' => (float) sanitize_text_field($_POST['minutes_performed']) / 60,
                                'total_performers' => sanitize_text_field($_POST['total_performers']),
                                'total_acts' => sanitize_text_field($_POST['total_acts']),
                                'recommend_to_others' => sanitize_text_field($_POST['recommend_to_others']),
                                'overall_rating' => sanitize_text_field($_POST['overall_rating']),
                                'communication_rating' => sanitize_text_field($_POST['communication_rating']),
                                'sound_rating' => sanitize_text_field($_POST['sound_rating']),
                                'safety_rating' => sanitize_text_field($_POST['safety_rating']),
                                'comp_structure' => $comp_structure,
                                '_comp_structure_string' => $comp_structure_string,
                                'guarantee_promise' => sanitize_text_field($_POST['guarantee_promise']),
                                'guarantee_earnings' => sanitize_text_field($_POST['guarantee_earnings']),
                                'door_percentage' => sanitize_text_field($_POST['door_percentage']),
                                'door_earnings' => sanitize_text_field($_POST['door_earnings']),
                                'bar_percentage' => sanitize_text_field($_POST['bar_percentage']),
                                'bar_earnings' => sanitize_text_field($_POST['bar_earnings']),
                                'versus_comp_1' => sanitize_text_field($_POST['versus_comp_1']),
                                'versus_comp_2' => sanitize_text_field($_POST['versus_comp_2']),
                                'production_cost' => sanitize_text_field($_POST['production_cost']),
                                'production_cost_type' => sanitize_text_field($_POST['production_cost_type']),
                                'tips_earnings' => sanitize_text_field($_POST['tips_earnings']),
                                'other_comp_structure' => sanitize_text_field($_POST['other_comp_structure']),
                                'total_earnings' => sanitize_text_field($_POST['total_earnings']),
                                'payment_speed' => sanitize_text_field($_POST['payment_speed']),
                                'payment_method' => sanitize_text_field($_POST['payment_method']),
                                'provided_sound_engineer' => sanitize_text_field($_POST['provided_sound_engineer']),
                                'backline' => $backline,
                                'review' => sanitize_textarea_field($_POST['review']),
                                'author_contact' => sanitize_text_field($_POST['author_contact']),
                            ),
                        );
                        $post_id = wp_insert_post($review_post);

                        if (is_wp_error($post_id)) {
                            echo '<h2>There was an error saving your submission. Please try again.</h2>';
                        } else {
                            $submission_post = array(
                                'post_title'   => 'Venue Review: ' . $performing_act_name . ' - ' . $venue_name . ' - ' . $performance_date,
                                'post_status'  => 'publish',
                                'post_type'    => 'review_submission',
                                'meta_input'   => array(
                                    'type' => 'Venue',
                                    'review_post' => $post_id,
                                    'submission' => json_encode($review_post),
                                )
                            );
                            $post_id = wp_insert_post($submission_post);

                            echo '<h2>Thank you for your venue review submission!</h2>';
                            echo '<p>Your review has been submitted successfully.</p>';
                            echo '<a style="margin: 0 20px 20px 0;" href="' . get_permalink() . '"><button>Submit another Review</button></a>';
                            echo '<a href="' . get_site_url() . '/venues"><button>Browse Venues</button></a>';
                        }
                    } else {
                        // Display the form
                        ?>
                        <h1>Venue Review Form</h1>
                        <p>Each review is verified and anonymous. Each review coresponds to exactly one performance by one act. You are welcome to fill out as many reviews as you'd like. Check the <a href="<?php echo get_site_url() . "/venues" ?>">venues page</a> to see the published reviews and the compensation insights derived from them.</p>
                        <form id="venue-review-form" method="post" action="">
                            <!------------ Gig details ----------------->
                            <hr>
                            <h2>Performance Details</h2>
                            <div class="form-separator"></div>
                            <div class="form-group">
                                <input type="hidden" id="performance_id" name="performance_id" value="<?php echo $_GET['pid']; ?>">
                                <input type="hidden" id="performance_post_id" name="performance_post_id">
                                <div><label for="venue_name">Venue Name</label><br>
                                <input required type="text" id="venue_name" name="venue_name" autocomplete="off">
                                <input type="hidden" id="venue_id" name="venue_id">
                                <div style="display:none;" class="dropdown" id="venue_options"></div></div>

                                <!-- Performer Name -->
                                <div><label for="performing_act_name">Performing Act Name</label>
                                <span class="tooltip">
                                    i<span class="tooltip-text">This is the name printed on the show bill. Your review will be completely annonymous. Performing act name is only collected for verification purposes</span>
                                </span><br>
                                <input required type="text" id="performing_act_name" name="performing_act_name" ></div>

                                <!-- Promoter or agency involved -->
                                <div><label for="middle_man">Promoter or Booking Agency</label>
                                <span class="tooltip">
                                    i<span class="tooltip-text">If there was a booking agency on the buying side or a promoter involved that you know of please name them here.</span>
                                </span><br>
                                <input type="text" id="middle_man" name="middle_man" ></div>
                            </div>
                            <div class="form-separator"></div>

                            <div class="form-group">
                                <!-- Performance date -->
                                <div><label for="performance_date">Performance Date</label>
                                <span class="tooltip">
                                    i<span class="tooltip-text">Your review will be completely annonymous. Performance date is only collected for verification purposes</span>
                                </span><br>
                                <input required type="date" id="performance_date" name="performance_date" ></div>

                                <!-- Start Time -->
                                <div><label for="performance_start_time">Set Start Time</label><br>
                                <input type="time" id="performance_start_time" name="performance_start_time" ></div>

                                <!-- Hours Performed -->
                                <div><label for="minutes_performed">Set Duration in Minutes</label><br>
                                <input required type="number" id="minutes_performed" name="minutes_performed" min=0 ></div>
                            </div>
                            <div class="form-separator"></div>

                            <div class="form-group">
                                <!-- # Total Perormers -->
                                <div><label for="total_performers">Number of Performers</label>
                                <span class="tooltip">
                                    i<span class="tooltip-text">Total number of performers that performed as part of your group in this performance</span>
                                </span><br>
                                <input required type="number" id="total_performers" name="total_performers" min=1 ></div>
                                <!-- # Total Acts -->
                                <div><label for="total_acts">Number of Acts on the Bill</label>
                                <span class="tooltip">
                                    i<span class="tooltip-text">Total number of performing acts that played with you in this show</span>
                                </span><br>
                                <input type="number" id="total_acts" name="total_acts" min=1 ></div>
                            </div>
                            <div class="form-separator"></div>

                            <!-- Gig type -->
                            <label for="gig_type">Gig Type</label><br>
                            <input class="button-input" value="Free Show" type="radio" name="gig_type" id="free"/>
                            <label class="button-label" for="free">Free Show</label>
                            <input class="button-input" value="Ticketed/Cover-charge Show" type="radio" name="gig_type" id="ticketed"/>
                            <label class="button-label" for="ticketed">Ticketed/Cover-charge Show</label>
                            <input class="button-input" value="Background Music" type="radio" name="gig_type" id="background"/>
                            <label class="button-label" for="background">Background Music</label>
                            <input class="button-input" value="Private Event" type="radio" name="gig_type" id="private"/>
                            <label class="button-label" for="private">Private Event</label>
                            <input class="button-input" value="Corporate Event" type="radio" name="gig_type" id="corporate"/>
                            <label class="button-label" for="corporate">Corporate Event</label><br><br>
                            <div class="form-separator"></div>

                            <!------------ Rating ----------------->
                            <hr>
                            <h2>Rating</h2>
                            <!-- Recommend to others -->
                            <label for="recommend_to_others">Would you recommend this venue to others?</label><br>
                            <input class="button-input" value=1 type="radio" name="recommend_to_others" id="recommend_to_others_yes"/>
                            <label class="button-label" for="recommend_to_others_yes">Yes</label>
                            <input class="button-input" value=0 type="radio" name="recommend_to_others" id="recommend_to_others_no"/>
                            <label class="button-label" for="recommend_to_others_no">No</label>
                            <div class="form-separator"></div>
                            <!-- Overall Rating -->
                            <table class="rating-table" style="border: 1px solid white;">
                                <tr>
                                    <td><label for="overall_rating">Overall Rating: </label></td>
                                    <td>
                                        <div class="rating">
                                            <input type="radio" name="overall_rating" value="1" id="overall-1" hidden>
                                            <label class="star" group="overall_rating" value="1" for="overall-1">&#9733;</label>
                                            <input type="radio" name="overall_rating" value="2" id="overall-2" hidden>
                                            <label class="star" group="overall_rating" value="2" for="overall-2">&#9733;</label>
                                            <input type="radio" name="overall_rating" value="3" id="overall-3" hidden>
                                            <label class="star" group="overall_rating" value="3" for="overall-3">&#9733;</label>
                                            <input type="radio" name="overall_rating" value="4" id="overall-4" hidden>
                                            <label class="star" group="overall_rating" value="4" for="overall-4">&#9733;</label>
                                            <input type="radio" name="overall_rating" value="5" id="overall-5" hidden>
                                            <label class="star" group="overall_rating" value="5" for="overall-5">&#9733;</label>
                                        </div>
                                    </td>
                                </tr>
                            <!-- Communication Rating -->
                                <tr>
                                    <td> <label for="communication_rating">Communication Rating: </label> </td>
                                    <td>
                                        <div class="rating">
                                            <input type="radio" name="communication_rating" value="1" id="communication-1" hidden>
                                            <label class="star" group="communication_rating" value="1" for="communication-1">&#9733;</label>
                                            <input type="radio" name="communication_rating" value="2" id="communication-2" hidden>
                                            <label class="star" group="communication_rating" value="2" for="communication-2">&#9733;</label>
                                            <input type="radio" name="communication_rating" value="3" id="communication-3" hidden>
                                            <label class="star" group="communication_rating" value="3" for="communication-3">&#9733;</label>
                                            <input type="radio" name="communication_rating" value="4" id="communication-4" hidden>
                                            <label class="star" group="communication_rating" value="4" for="communication-4">&#9733;</label>
                                            <input type="radio" name="communication_rating" value="5" id="communication-5" hidden>
                                            <label class="star" group="communication_rating" value="5" for="communication-5">&#9733;</label>
                                        </div>
                                    </td>
                                </tr>
                            <!-- Sound quality -->
                                <tr>
                                    <td> <label for="sound_rating">Sound Rating</label> </td>
                                    <td>
                                        <div class="rating">
                                            <input type="radio" name="sound_rating" value="1" id="sound-1" hidden>
                                            <label class="star" group="sound_rating" value="1" for="sound-1">&#9733;</label>
                                            <input type="radio" name="sound_rating" value="2" id="sound-2" hidden>
                                            <label class="star" group="sound_rating" value="2" for="sound-2">&#9733;</label>
                                            <input type="radio" name="sound_rating" value="3" id="sound-3" hidden>
                                            <label class="star" group="sound_rating" value="3" for="sound-3">&#9733;</label>
                                            <input type="radio" name="sound_rating" value="4" id="sound-4" hidden>
                                            <label class="star" group="sound_rating" value="4" for="sound-4">&#9733;</label>
                                            <input type="radio" name="sound_rating" value="5" id="sound-5" hidden>
                                            <label class="star" group="sound_rating" value="5" for="sound-5">&#9733;</label>
                                        </div>
                                    </td>
                                </tr>
                            <!-- Safety Rating -->
                                <tr>
                                    <td> <label for="safety_rating">Safety Rating</label> </td>
                                    <td>
                                        <div class="rating">
                                            <input type="radio" name="safety_rating" value="1" id="safety-1" hidden>
                                            <label class="star" group="safety_rating" value="1" for="safety-1">&#9733;</label>
                                            <input type="radio" name="safety_rating" value="2" id="safety-2" hidden>
                                            <label class="star" group="safety_rating" value="2" for="safety-2">&#9733;</label>
                                            <input type="radio" name="safety_rating" value="3" id="safety-3" hidden>
                                            <label class="star" group="safety_rating" value="3" for="safety-3">&#9733;</label>
                                            <input type="radio" name="safety_rating" value="4" id="safety-4" hidden>
                                            <label class="star" group="safety_rating" value="4" for="safety-4">&#9733;</label>
                                            <input type="radio" name="safety_rating" value="5" id="safety-5" hidden>
                                            <label class="star" group="safety_rating" value="5" for="safety-5">&#9733;</label>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!------------ Pay ----------------->
                            <hr>
                            <h2>Compensation</h2>
                            <!-- Structure -->
                            <style>
                                #guarantee_questions,
                                #door_questions,
                                #bar_questions,
                                #versus_questions,
                                #cost_questions,
                                #tips_questions,
                                #other_questions {
                                    display: none;
                                }
                                #has_guarantee_comp:checked ~ #guarantee_questions,
                                #has_door_comp:checked ~ #door_questions,
                                #has_bar_comp:checked ~ #bar_questions,
                                #has_versus_comp:checked ~ #versus_questions,
                                #has_production_cost:checked ~ #cost_questions,
                                #has_tips_comp:checked ~ #tips_questions,
                                #has_other_comp:checked ~ #other_questions {
                                    display: block;
                                }
                            </style>
                            <div>
                                <label for="comp_structure">Compensation Structure. Check all that apply.</label><br>
                                <input class="button-input" value="Guarantee" type="checkbox" name="comp_structure[]" id="has_guarantee_comp"/>
                                <label class="button-label" for="has_guarantee_comp">Guarantee</label>
                                <input class="button-input" value="Door Deal" type="checkbox" name="comp_structure[]" id="has_door_comp"/>
                                <label class="button-label" for="has_door_comp">Door Deal</label>
                                <input class="button-input" value="Bar Deal" type="checkbox" name="comp_structure[]" id="has_bar_comp"/>
                                <label class="button-label" for="has_bar_comp">Bar Deal</label>
                                <input class="button-input" value="Versus" type="checkbox" name="comp_structure[]" id="has_versus_comp"/>
                                <label class="button-label" for="has_versus_comp">Versus Deal</label>
                                <input class="button-input" value="Production Cost" type="checkbox" name="comp_structure[]" id="has_production_cost"/>
                                <label class="button-label" for="has_production_cost">Production Costs</label>
                                <!--<input class="button-input" type="checkbox" name="comp_structure[]" id="split_point"/>
                                <label class="button-label" for="split_point">Split Point</label>-->
                                <input class="button-input" value="Tips" type="checkbox" name="comp_structure[]" id="has_tips_comp"/>
                                <label class="button-label" for="has_tips_comp">Tips</label>
                                <input class="button-input" value="In Kind" type="checkbox" name="comp_structure[]" id="comps"/>
                                <label class="button-label" for="comps">Free food/drink</label>
                                <input class="button-input" value="Other" type="checkbox" name="comp_structure[]" id="has_other_comp"/>
                                <label class="button-label" for="has_other_comp">Other or Unknown</label>
                                <div class="form-separator"></div>
                                <!-- Versus -->
                                <!-- dont allow same options, open questions associated with the selections by clicking button -->
                                <div id="versus_questions">
                                    <div class="form-group">
                                        <div>
                                            <select name="versus_comp_1" id="versus_comp_1">
                                                <option disabled selected > select an option </option>
                                                <option value="Guarantee">Guarantee</option>
                                                <option value="Door Deal">Door Deal</option>
                                                <option value="Bar Deal">Bar Deal</option>
                                            </select>
                                            <span style="margin: 0 10px 0 10px;">VS</span>
                                            <select name="versus_comp_2" id="versus_comp_2">
                                                <option disabled selected > select an option </option>
                                                <option value="Guarantee">Guarantee</option>
                                                <option value="Door Deal">Door Deal</option>
                                                <option value="Bar Deal">Bar Deal</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-separator"></div>
                                <!-- what versus what, open questions for guarantee and bar and door depending on what is selected -->
                                <!-- Guarantee -->
                                <div id="guarantee_questions">
                                    <div class="form-group">
                                        <div><label for="guarantee_promise">How much was the guarantee?</label><br>
                                        <input type="number" name="guarantee_promise" id="guarantee_promise"></div>
                                        <div><label for="guarantee_earnings">How much of the guarantee were you paid?</label><br>
                                        <input type="number" name="guarantee_earnings" id="guarantee_earnings"></div>
                                    </div>
                                </div>
                                <div class="form-separator"></div>
                                <!-- Door -->
                                <div id="door_questions">
                                    <div class="form-group">
                                        <div><label for="door_percentage">What percentage of the door/tickets were you/your group promised?</label><br>
                                        <input type="number" name="door_percentage" id="door_percentage"></div>
                                        <div><label for="door_earnings">How much were you actually paid out from the door?</label><br>
                                        <input type="number" name="door_earnings" id="door_earnings"></div>
                                    </div>
                                </div>
                                <div class="form-separator"></div>
                                <!-- Bar -->
                                <div id="bar_questions">
                                    <div class="form-group">
                                        <div><label for="bar_percentage">What percentage of the bar/kitchen were you/your group promised?</label><br>
                                        <input type="number" name="bar_percentage" id="bar_percentage"></div>
                                        <div><label for="bar_earnings">How much were you actually paid out from the bar/kitchen sales?</label><br>
                                        <input type="number" name="bar_earnings" id="bar_earnings"></div>
                                    </div>
                                </div>
                                <div class="form-separator"></div>
                                <!-- Production Costs -->
                                <div id="cost_questions">
                                    <div class="form-group">
                                        <div><label for="production_cost_type">How was the production cost paid?</label><br>
                                        <input class="button-input" value="Paid up front" type="radio" name="production_cost_type" id="up-front"/>
                                        <label class="button-label" for="up-front">Paid up front</label>
                                        <input class="button-input" value="Deducted from earnings" type="radio" name="production_cost_type" id="deducted"/>
                                        <label class="button-label" for="deducted">Deducted from earnings</label></div>
                                        <div><label for="production_cost">What was the production cost?</label><br>
                                        <input type="number" name="production_cost" id="production_cost"></div>
                                    </div>
                                </div>
                                <div class="form-separator"></div>
                                <!-- Split Point -->
                                <!-- split percentage after split point -->
                                <!-- make sure you check boxes for fix cost and other pay methods that were part of the deal -->
                                <!-- total take home pay after split point -->
                                <!-- add details -->
                                <!-- Tips -->
                                <div id="tips_questions">
                                    <label for="tips_earnings">How much did you take home in Tips?</label><br>
                                    <input type="number" name="tips_earnings" id="tips_earnings"><br>
                                </div>
                                <div class="form-separator"></div>
                                <!-- Other/Unknown -->
                                <div id="other_questions">
                                    <label for="other_comp_structure">Explain the pay structure, what you were promised and what was actually paid out to you?</label><br>
                                    <textarea type="textarea" name="other_comp_structure" id="other_comp_structure"></textarea><br>
                                </div>
                            </div>
                            <div class="form-separator"></div>
                            <!-- Total Earnings -->
                            <label for="total_earnings">Total Earnings</label>
                            <span class="tooltip">
                                i<span class="tooltip-text">How much money did you (if you performed solo) or your group (if you performed with a group) make in total?</span>
                            </span><br>
                            <input required type="number" name="total_earnings" id="total_earnings"><br>
                            <div class="form-separator"></div>
                            <!-- Pay speed -->
                            <label for="payment_speed">How quickly after the show did you get paid?</label>
                            <div>
                                <input class="button-input" value="Before the gig" type="radio" name="payment_speed" id="before"/>
                                <label class="button-label" for="before">Before the gig</label>
                                <input class="button-input" value="On the day of the gig" type="radio" name="payment_speed" id="day-of"/>
                                <label class="button-label" for="day-of">On the day of the gig</label>
                                <input class="button-input" value="Within 3 days after the gig" type="radio" name="payment_speed" id="3-days"/>
                                <label class="button-label" for="3-days">Within 3 days after the gig</label>
                                <input class="button-input" value="Within a week after the gig" type="radio" name="payment_speed" id="1-week"/>
                                <label class="button-label" for="1-week">Within a week after the gig</label>
                                <input class="button-input" value="Within 2 weeks after the gig" type="radio" name="payment_speed" id="2-weeks"/>
                                <label class="button-label" for="2-weeks">Within 2 weeks after the gig</label>
                                <input class="button-input" value="Within 30 days after the gig" type="radio" name="payment_speed" id="30-days"/>
                                <label class="button-label" for="30-days">Within 30 days after the gig</label>
                                <input class="button-input" value="Never got paid" type="radio" name="payment_speed" id="never"/>
                                <label class="button-label" for="never">Never got paid</label>
                            </div>
                            <div class="form-separator"></div>
                            <!-- Pay method -->
                            <label for="payment_method">What was the payment method used?</label>
                            <div>
                                <input class="button-input" value="Cash" type="radio" name="payment_method" id="cash"/>
                                <label class="button-label" for="cash">Cash</label>
                                <input class="button-input" value="Check" type="radio" name="payment_method" id="check"/>
                                <label class="button-label" for="check">Check</label>
                                <input class="button-input" value="Direct Deposit" type="radio" name="payment_method" id="direct-deposit"/>
                                <label class="button-label" for="direct-deposit">Direct Deposit</label>
                                <input class="button-input" value="Zelle" type="radio" name="payment_method" id="zelle"/>
                                <label class="button-label" for="zelle">Zelle</label>
                                <input class="button-input" value="Venmo/Cash App" type="radio" name="payment_method" id="app"/>
                                <label class="button-label" for="app">Venmo/Cash App</label>
                                <input class="button-input" value="PayPal" type="radio" name="payment_method" id="paypal"/>
                                <label class="button-label" for="paypal">PayPal</label>
                                <input class="button-input button-input-other" value="Other" type="radio" name="payment_method" id="payment_method_other"/>
                                <label class="button-label" for="payment_method_other">Other</label>
                                <input class="other-input" type="text" name="payment_method" id="payment_method_other_detail"/>
                            </div>
                            <div class="form-separator"></div>

                            <!------------ Sound ----------------->
                            <hr>
                            <h2>Sound</h2>
                            <!-- Provide Sound Engineer -->
                            <label for="provided_sound_engineer">Was a sound engineer provided?</label>
                            <div>
                                <input class="button-input" value=1 type="radio" name="provided_sound_engineer" id="provided_sound_engineer_yes"/>
                                <label class="button-label" for="provided_sound_engineer_yes">Yes</label>
                                <input class="button-input" value=0 type="radio" name="provided_sound_engineer" id="provided_sound_engineer_no"/>
                                <label class="button-label" for="provided_sound_engineer_no">No</label>
                            </div>
                            <div class="form-separator"></div>
                            <!-- Provide back line -->
                            <label for="backline">Was any backline equipment provided? Check all that apply</label>
                            <div>
                                <input class="button-input" value="PA System" type="checkbox" name="backline[]" id="backline_pa"/>
                                <label class="button-label" for="backline_pa">PA System</label>
                                <input class="button-input" value="Drum Kit" type="checkbox" name="backline[]" id="backline_drums"/>
                                <label class="button-label" for="backline_drums">Drum Kit</label>
                                <input class="button-input" value="Bass Amp" type="checkbox" name="backline[]" id="backline_bass_amp"/>
                                <label class="button-label" for="backline_bass_amp">Bass Amp</label>
                                <input class="button-input" value="Guitar Amp" type="checkbox" name="backline[]" id="backline_guitar_amp"/>
                                <label class="button-label" for="backline_guitar_amp">Guitar Amp</label>
                                <input class="button-input" value="Monitors" type="checkbox" name="backline[]" id="backline_monitors"/>
                                <label class="button-label" for="backline_monitors">Monitors</label>
                                <input class="button-input button-input-other" value="Other" type="checkbox" id="backline_other"/>
                                <label class="button-label" for="backline_other">Other</label>
                                <input name="backline[]" class="other-input" type="text" name="backline[]" id="backline_other_detail"/>
                            </div>
                            <div class="form-separator"></div>

                            <hr>
                            <h2>Review</h2>
                            <!-- Review -->
                            <label for="review">Please tell the community about your experience working with this venue.</label><br>
                            <textarea id="review" name="review"></textarea><br><br>
                            <div class="form-separator"></div>
                            <label for="author_contact">Please provide a way for us to contact you in case we have questions about the review.</label>
                            <span class="tooltip">
                                i<span class="tooltip-text">This will not be published in any way. This is only used to contact you in case we have questions about this review and we cannot find a way to contact you.</span>
                            </span><br>
                            <input type="text" name="author_contact" id="author_contact"><br>
                            <div class="form-separator"></div>

                            <!-- Talent buyer contact -->
                            <hr>
                            <!--
                            <h2>Verification</h2>
                            <label for="buyer_contact">Talent Buyer Contact</label><br>
                            <input type="text" id="buyer_contact" name="buyer_contact"></input><br><br>
                            <div class="form-separator"></div>
                            -->

                            <input type="submit" value="Submit">
                        </form>
                        <?php
                    }
				?>
			</div><!-- .single-page-wrapper  -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>

</div><!-- .container -->

<?php
get_footer();

