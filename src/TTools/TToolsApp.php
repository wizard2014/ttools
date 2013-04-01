<?php

namespace TTools;

interface TToolsApp {

	function storeRequestSecret($user_id, $request_secret);

    function getRequestSecret();
}