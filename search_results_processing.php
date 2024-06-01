<?php
session_start();
if ($_SESSION["logged_in"] != true) {
    header("Location: ./index.php");
}
// maybe this will stop this script from crashing?
//ini_set('max_execution_time', 10000);
/*
ini_set('memory_limit', '1024M');
set_time_limit(0); 
*/
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//ini_set('memory_limit', '6G');
*/
//

//retrive the search parameters -- from get
$search_values = $_GET['search_bar'];
$is_only_one_match = 0; // if this 1, there is only one relevent search result
$are_multple_matches = 0; // if this is 1, there is more than 1 relevent search result
$single_result_query = ""; //the sql query if there is only one result
$multi_result_array_fetch = array(); // temporarily holds the records from a multi-result search
//search each video name in the database and check if the seach parameter exists -- a simple sub-substring search
    //corelation score from 0.00 to 1
        //check if the video title contains that entire search string -- if it exists, score == 1
        //if the search string is multiple words seperated by comma, space, or both, seperate each word and check if each word exists in a video title 
            //score = word_matches/total_number_of_words * 0.99
// =====================================================================
// get all records from the database
include "mysql_access_functions.php";

$sql_query = "SELECT upload_Id,video_Title,file_Description FROM video_info";

$all_database_records = array();
$all_database_records = run_sql_query_return_all_results($sql_query,$sql_server,$database_user,$database_user_password,$database_name);

// look for any seperators in the search terms -> spaces, commas
$find_this_1 = " ";
$find_this_2 = ",";
$find_this_3 = "-";
$first_alphanumerica_index = -5;
    // find the index of the first character that is not a seperator
for ($x = 0; $x < strlen($search_values); $x = $x + 1) {
   if ($first_alphanumerica_index < 0) {
        if (ctype_alnum($search_values[$x])) {
            $first_alphanumerica_index = $x;
        }
   }
}
if ($first_alphanumerica_index < 0) {
    $first_alphanumerica_index = 0;
}
function find_indexes_of_char ($char_to_find,$start_index,$string_to_search) {
    $max_index = strlen($string_to_search) - 1;
    $return_array = array();
    for ($x = $start_index; $x <= $max_index; $x = $x + 1) {
    // for the following indexes
        // do not proceed if the the current index is the second to last index
        // and if the next character is not a letter or number
        if ($x < $max_index && ctype_alnum($string_to_search[$x + 1])) {
            if ($string_to_search[$x] == $char_to_find) {
                array_push($return_array,$x);
            }
        }
    }
    return $return_array;
}

$seperator_list = array(); // holds the index of all seperators
    // check for any spaces
$space_indexes = array();
$space_indexes = find_indexes_of_char($find_this_1,$first_alphanumerica_index,$search_values);
    // check for commas
$comma_indexes = find_indexes_of_char($find_this_2,$first_alphanumerica_index,$search_values);
    // find any -
$dash_indexes = array();
$dash_indexes = find_indexes_of_char($find_this_3,$first_alphanumerica_index,$search_values);

$seperator_list = array_merge($space_indexes,$comma_indexes,$dash_indexes);
$seperated_search_terms = array();
$only_search_term = ""; // if there is only one search term -- no multi search
sort($seperator_list);
// get each search term, they exist --- start from $first_alphanumerica_index
$run_multi_search = 0; //if this is 1, search for multiple search terms
    // check if the list of seperators is not empty
$number_of_seperators =  count($seperator_list);
if ($number_of_seperators > 0) {
    $run_multi_search = 1;
}
// search each video description and title for the presence of all search terms
function copy_text_from_one_index_to_another ($start_index, $ending_index_inclusive, $string_to_copy_from) {
    $return_string = "";
    for ($x = $start_index; $x <= $ending_index_inclusive; $x = $x + 1) {
        $return_string = $return_string.$string_to_copy_from[$x];
    }
    return $return_string;
}
    // if there are no seperators
    if ($run_multi_search == 0) {
        // get each seperated search term
            // get the first one one first
        $only_search_term = copy_text_from_one_index_to_another($first_alphanumerica_index,strlen($search_values) - 1,$search_values);
    }
    if ($run_multi_search == 1) {
        // get each seperated search term
            // get the first one one first
        $last_index_single = -5; // the index where the last alphanumeric character is, if it exists
        $first_search_term = copy_text_from_one_index_to_another($first_alphanumerica_index,$seperator_list[0] - 1,$search_values);
            // if there are more than one seperators
        if ($number_of_seperators == 1) { // if there is only 1 seperator
                // get the second search term
                    // check if there are any seperators at the end of the search terms

            // ---------------------------------------------------------
            for ($x = $seperator_list[0] + 1; $x <= strlen($search_values) - 1; $x = $x + 1) {
                if ($last_index_single < 0) { // if $last_index_single is empty
                    if ($search_values[$x] == $find_this_1 || $search_values[$x] == $find_this_2 || $search_values[$x] == $find_this_3) {
                        $last_index_single = $x - 1;
                    }
                }
            }
            // ---------------------------------------------------------
  
                // set the last index to length - 1 if no trailing seperators are found
            if ($last_index_single < 0) {
                $last_index_single = strlen($search_values) - 1;
            }
            $second_search_term = copy_text_from_one_index_to_another($seperator_list[0] + 1,$last_index_single,$search_values);
                // put the two search terms into the final list of search terms
                array_push($seperated_search_terms,$first_search_term,$second_search_term);
        }
        if ($number_of_seperators > 1) { // if there is more 1 seperator
            $index_start = 0;
            // check if there are any trailing seperators
            for ($x = $seperator_list[$number_of_seperators - 1] + 1; $x <= strlen($search_values) - 1; $x = $x + 1) {
                if ($last_index_single < 0) { // if $last_index_single is empty
                    if ($search_values[$x] == $find_this_1 || $search_values[$x] == $find_this_2 || $search_values[$x] == $find_this_3) {
                        $last_index_single = $x - 1;
                    }
                }
            }
            // ---------------------------------------------------------
  
                // set the last index to length - 1 if no trailing seperators are found
            if ($last_index_single < 0) {
                $last_index_single = strlen($search_values) - 1;
            }
            // get each search term after the first one
            array_push($seperated_search_terms,$first_search_term);
            for ($x = $index_start; $x <= $number_of_seperators - 1; $x = $x + 1) {
                if ($x == 0) {
                    $after_first_search_term = copy_text_from_one_index_to_another($seperator_list[$x] + 1,$seperator_list[$x + 1] - 1,$search_values);
                    array_push($seperated_search_terms,$after_first_search_term);
                }
                if ($x != $number_of_seperators - 1 && $x != 0) {
                    $extra_search_term = copy_text_from_one_index_to_another($seperator_list[$x] + 1,$seperator_list[$x + 1] - 1,$search_values);
                    array_push($seperated_search_terms,$extra_search_term);
                }
                if ($x == $number_of_seperators - 1) {
                    $final_search_term = copy_text_from_one_index_to_another($seperator_list[$x] + 1,$last_index_single,$search_values);
                    array_push($seperated_search_terms,$final_search_term);
                }
            }
        }
    }

// get the final list of search results
    // if using multi select, search all seperated search terms
    // if not using multi select, search the entire search value
$results_returned_array = array();
if ($run_multi_search == 0) { // if not multi-select
    // prepared statement
    $search_for_this = "%$search_values%";
    $database_connection = new mysqli($sql_server_name, $database_user, $database_user_password, $database_name);
        // prepare the query
    $sql_query_single_search = $sql_query = "SELECT * FROM video_info WHERE video_Title like ? ";
    $prepared_sql_query = $database_connection->prepare($sql_query_single_search);
        // bind the input variables
    $prepared_sql_query->bind_param("s", $search_for_this);
        // execute the query
    $prepared_sql_query->execute();
    //
    //$sql_query = "SELECT * FROM video_info WHERE video_Title like '%$search_values%'";

    //$results_returned_array = run_sql_query_return_all_results($sql_query,$sql_server,$database_user,$database_user_password,$database_name);
    $results_returned_array = $prepared_sql_query->get_result()->fetch_all(MYSQLI_ASSOC);
    $prepared_sql_query->close();
}
// function block
function copy_text_from_one_index_to_another_internal ($start_index, $ending_index_inclusive, $string_to_copy_from) {
    $return_string = "";
    for ($x = $start_index; $x <= $ending_index_inclusive; $x = $x + 1) {
        $return_string = $return_string.$string_to_copy_from[$x];
    }
    return $return_string;
}
function search_string_for_keyword ($string_to_find,$string_to_search_in) {
    $found_match = 0; // if this is 1, a match is found
    $search_index_length = strlen($string_to_find);
    for ($x = 0; $x < strlen($string_to_search_in); $x = $x + 1) {
        if ($x <= strlen($string_to_search_in) - 1 - $search_index_length + 1) {
            $test_string = copy_text_from_one_index_to_another_internal($x,$x + $search_index_length - 1,$string_to_search_in);
            if ($test_string == $string_to_find) {
                $found_match = 1;
            }
        }
    }
    if ($found_match == 1) {
        return true;
    }
    if ($found_match != 1) {
        return false;
    }
}
// function block
if ($run_multi_search == 1) { // if multi-select
    // search all search terms
        // ### fetch all database records -- upload_id and title
    $sql_query = "SELECT upload_Id,video_Title FROM video_info";
    $all_database_records = run_sql_query_return_all_results($sql_query,$sql_server,$database_user,$database_user_password,$database_name);
    // arrays for relevance matching purposes
    $upload_id_list = array(); // a matching array of upload_Id records
    $video_title_list = array(); // a matching array of video titles
    $all_records_relevence_scores = array(); // a mataching array of relevence scores
    // arrays for display purposes
    $upload_ids_to_retrieve = array(); // a matching list of upload ids from which to retrieve database records
    $relevence_score_list = array(); // a matching list of relevence scores
    $display_order_list = array(); // the order in which each record will be displayed -- reverse iterate to list from most relevent to least relevent
    //
            // copy the records from the sql query into the seperate arrays
    foreach ($all_database_records as $row) {
        array_push($upload_id_list,$row["upload_Id"]);
        array_push($video_title_list,$row["video_Title"]);
    }
            // search each video title for the presence of each seperate search term
    for ($x = 0; $x < count($video_title_list); $x = $x + 1) {
        $relevence_score = 0;
        $total_relevence_points = count($seperated_search_terms);
        $earned_relevence_points = 0;
        $video_title_to_search_in = $video_title_list[$x];
        // search each term within the title
        foreach ($seperated_search_terms as $search_term) {
            if (search_string_for_keyword($search_term,$video_title_to_search_in)) {
                $earned_relevence_points = $earned_relevence_points + 1;
            }
        }
        $relevence_score = $earned_relevence_points/$total_relevence_points;
        // store the relevence score in the list of relevence scores for all records
        array_push($all_records_relevence_scores,$relevence_score);
        //echo "for |$video_title_to_search_in|, the relevence score is |$relevence_score|<br>";
    }
    // put all records that have relevence ratio above 0 into a seperate array for display processing
    for ($x = 0; $x < count($upload_id_list); $x = $x + 1) {
        $r_id = $upload_id_list[$x];
        $r_desc = $video_title_list[$x];
        $r_score = $all_records_relevence_scores[$x];
        // if the relevence score is above 0
        if ($r_score > 0) {
            array_push($upload_ids_to_retrieve,$r_id);
            array_push($relevence_score_list,$r_score);
        }
    }
    
    $total_number_of_records = count($upload_ids_to_retrieve);
    function copy_array ($array_to_copy) {
        $input_array = $array_to_copy;
        $return_array = array();
        for ($x = 0; $x < count($input_array); $x = $x + 1) {
            array_push($return_array,$input_array[$x]);
        }
        return $return_array;
    }
    $copy_of_score_array = copy_array($relevence_score_list);
        // check of there are any matches
    $number_of_matches = count($copy_of_score_array);
        // if there is exactly one match, retrive that one record
    if ($number_of_matches == 1) {
        $the_only_match = $upload_ids_to_retrieve[0];
        $single_result_query = "SELECT * FROM video_info WHERE upload_Id = '$the_only_match'";
        $is_only_one_match = 1;
    }
        // if there are at least two matches, sort the results
    if ($number_of_matches > 1) {
        $are_multple_matches = 1;
        sort ($copy_of_score_array);
        // sort the results by relevence score -- smallest to biggest
        $postion_count = 1; // start from 1 -- the smallest number
        $max_postiion_count = count($copy_of_score_array);
        $lowest_score = $copy_of_score_array[0];
        $highest_score = $copy_of_score_array[count($copy_of_score_array) - 1];
        $final_id_to_retrieve = array(); // the id of the records to retreive ordered by score -- matching array
        // order the results by relevence score - lowest to highest
        for ($x = 0; $x < count($copy_of_score_array); $x = $x + 1) {
            $check_score = $copy_of_score_array[$x];
            // check for the score values in the orignal list of matched ids
            for ($y = 0; $y < count($relevence_score_list); $y = $y + 1) {
                if ($relevence_score_list[$y] == $check_score) {
                    array_push($final_id_to_retrieve,$upload_ids_to_retrieve[$y]);
                }
            }
            
        }
        // remove duplicate ids
        $final_id_to_retrieve = array_unique($final_id_to_retrieve);
        // reverse the order of the final id array to put from highest score to lowest
        $final_id_to_retrieve = array_reverse($final_id_to_retrieve);
        //print_r($final_id_to_retrieve);
        //$multi_result_array_fetch
        // get each record specified in the final list
        for ($x = 0; $x < count($final_id_to_retrieve); $x = $x + 1) {
            $find_this_id = $final_id_to_retrieve[$x];
            $sql_query = "SELECT * FROM video_info WHERE upload_Id = '$find_this_id'";
            $temporary_storage = run_sql_query_return_all_results($sql_query,$sql_server,$database_user,$database_user_password,$database_name);
            foreach ($temporary_storage as $row) {
                array_push($multi_result_array_fetch,$row);
            }
        }
    }
        
    $sql_query = "";

    if ($is_only_one_match == 1) {
        $results_returned_array = run_sql_query_return_all_results($single_result_query,$sql_server,$database_user,$database_user_password,$database_name);
    }
    if ($are_multple_matches == 1) {
        $results_returned_array = $multi_result_array_fetch;
    }
}
// ###################### display the search results #######################
$results_to_display = $results_returned_array;
// show the following: video title, thumbnail
//
$number_of_results = count($results_to_display);
if ($number_of_results <= 0) {
    echo "<p>no results for found for '$search_values'</p>";
}
//
foreach($results_to_display as $record) {
    $title_display = $record['video_Title'];
    $display_image = $record['path_To_Video_Thumbnail'];
    $display_link = $record['path_To_Video_Page'];
    $display_test_link = $record['upload_Id'];
    $display_unit = "
    <div style='background-color: orange; padding: 10px; font-weight: bold; text-align: center;'>
    <p>$title_display</p>
    </div>
    <form action='./vpage_template.php' method='get'>
    <input name='video_id' id='video_id' type='hidden' value='$display_test_link'>
    <input type='image' src='$display_image' alt='submit' width='100%'>
    </form>
    ";
    echo $display_unit;
    echo "<br>";
    echo "<br>";
}
// ============================================================================

?>


