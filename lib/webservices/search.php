<?php

	ws_pack_search_expose_functions();
	
	function ws_pack_search_expose_functions() {
		expose_function(
			"search.query", 
			"ws_pack_search",
			array(
				"query" => array(
					"type" => "string",
					"required" => true
				),
				"offset" => array(
					"type" => "integer",
					"required" => false
				),
				"limit" => array(
					"type" => "integer",
					"required" => false
				)
			),
			elgg_echo("ws_pack:api:system:api:register_push_notification_service"),
			"GET",
			true,
			false
		);
	}

	function ws_pack_search($query, $offset = 0, $limit = 20) {
		if (!elgg_is_logged_in()) {
			$ia = elgg_set_ignore_access(true);
		}

		$results = ESInterface::get()->search($query, "all", null, null, (int) $limit, (int) $offset);

		$json = array();
		$json["total"] = $results["count"];
		if ($results["count"] > 0) {
			$json["results"] = ws_pack_export_entities($results["hits"]);
		} else {
			$json["results"] = array();
		}

		if (!elgg_is_logged_in()) {
			elgg_set_ignore_access($ia);
		}

		return $json;
	}