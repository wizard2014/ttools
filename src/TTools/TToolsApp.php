<?php

namespace TTools;

interface TToolsApp {


	private function storeRequestSecret($user_id, $request_secret);

    private function getRequestSecret($user_id);
}