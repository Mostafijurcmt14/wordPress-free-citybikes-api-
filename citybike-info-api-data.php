<?php

/**
* Important Note...........
* Please add below code to WordPress function.php 
*/


/**
 * Admin menu create for api data view
 * CityBike is a free api from - https://rapidapi.com/eskerda/api/citybikes/
 */ 

function api_admin_menu(){
	add_menu_page(
		__('CityBike', 'hello-elementor'),
		__('CityBike', 'hello-elementor'),
		'manage_option',
		'citybike',
		'',
		'dashicons-pressthis',
		8
	);
	add_submenu_page(
		'citybike',
		__('All Lists', 'hello-elementor'),
		__('All Lists', 'hello-elementor'),
		'manage_options', 
		'all-citybike-lists',
		'all_citybike_lists_callback'
	);
}
add_action('admin_menu', 'api_admin_menu');


/**
 * Get data from the api using curl() library 
 */ 

function get_api_function(){
	global $get_data;
	
	$curl_init = curl_init();

	curl_setopt_array($curl_init, [
		CURLOPT_URL => "https://community-citybikes.p.rapidapi.com/valenbisi.json",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"X-RapidAPI-Host: community-citybikes.p.rapidapi.com",
			"X-RapidAPI-Key: 0f678380c4mshda2e811f467e56cp1542c2jsna09e9beb1a10"
		],
	]);

	$response = curl_exec($curl_init);
	$api_error = curl_error($curl_init);

	curl_close($curl_init);

	if ($api_error) {
		echo "Api error... #:" . $api_error;
	} else {
		
		$data_decode = json_decode($response);
		$get_data = $data_decode;
		//print_r($get_data);
	}
}

add_shortcode('get_api_function_shortcode','get_api_function');



/**
 * Admin menu callback function for display data to the table.
 * The table data is displayed from the following shortcode - [api_frontend_data_table_shortcode]
 */ 

function all_citybike_lists_callback(){
	echo do_shortcode('[api_frontend_data_table_shortcode]');
}


/**
 * Admin table frontend design using html baisc table,
 */ 

function api_frontend_data_table(){
	ob_start();

	global $get_data;
	echo do_shortcode('[get_api_function_shortcode]');
	//print_r($get_data);

	?>
<h2>All city bike lists from citybikes api: </h2>
<table style="border-collapse: collapse; margin-top: 20px; margin-right: 20px;">
	  <tr>
	    <th style=" border: 1px solid #dddddd; text-align: left; padding: 8px;">id</th>
	    <th style=" border: 1px solid #dddddd; text-align: left; padding: 8px;">bikes</th>
	    <th style=" border: 1px solid #dddddd; text-align: left; padding: 8px;">name</th>
	    <th style=" border: 1px solid #dddddd; text-align: left; padding: 8px;">lat</th>
	    <th style=" border: 1px solid #dddddd; text-align: left; padding: 8px;">timestamp</th>
	    <th style=" border: 1px solid #dddddd; text-align: left; padding: 8px;">lng</th>
	    <th style=" border: 1px solid #dddddd; text-align: left; padding: 8px;">free</th>
	    <th style=" border: 1px solid #dddddd; text-align: left; padding: 8px;">number</th>
	  </tr>

	<?php
		foreach ($get_data as $data) {
			//print_r($data);
	?>
	  <tr>
	    <td style=" border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo $data->id; ?></td>
	    <td style=" border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo $data->bikes; ?></td>
	    <td style=" border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo $data->name; ?></td>
	    <td style=" border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo $data->lat; ?></td>
	    <td style=" border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo $data->timestamp; ?></td>
	    <td style=" border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo $data->lng; ?></td>
	    <td style=" border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo $data->free; ?></td>
	    <td style=" border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo $data->number; ?></td>
	  </tr>
    <?php } ?>

</table>

	<?php
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode('api_frontend_data_table_shortcode','api_frontend_data_table');

?>