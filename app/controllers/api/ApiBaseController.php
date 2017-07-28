<?php

class ApiBaseController extends BaseController {

    public function __construct()
    {
        parent::__construct();

        /* This checks the client list for the CID. If a matching CID is found, all caching will be ignored
           for this request */

        if (Cache::has('clients'))
            $clients = Cache::get('clients');
        else {
            $clients = Client::all();
            Cache::put('clients', $clients, 1);
        }

        if (Cache::has('keys'))
            $keys = Cache::get('keys');
        else {
            $keys = Key::all();
            Cache::put('keys', $keys, 1);
        }

        $input_cid = Input::get('cid');
        if(!empty($input_cid)) {
            foreach ($clients as $client) {
                if ($client->uuid == $input_cid) {
                    $this->client = $client;
                }
            }
        }

        $input_key = Input::get('k');
        if(!empty($input_key)) {
            foreach ($keys as $key) {
                if ($key->api_key == $input_key) {
                    $this->key = $key;
                }
            }
        }

    }


}