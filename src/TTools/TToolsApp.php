<?php
namespace TTools;

interface TToolsApp {

	function storeRequestSecret($request_token, $request_secret);

    function getRequestSecret();
}