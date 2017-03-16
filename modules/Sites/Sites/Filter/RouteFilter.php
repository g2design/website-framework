<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sites\Filter;

/**
 * Description of RouteFilter
 *
 * @author User
 */
class RouteFilter {

	static function afterJson($response) {
		//Send content type json header
		header('Content-Type: application/json');

		// JSON encode response
//		if (is_object($response) || is_array($response))
			return json_encode($response);
//		else
//			return json_encode(['response' => $response]);
	}

}
