<?php

class APIController extends BaseController
{

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
        if (!empty($input_cid)) {
            foreach ($clients as $client) {
                if ($client->uuid == $input_cid) {
                    $this->client = $client;
                }
            }
        }

        $input_key = Input::get('k');
        if (!empty($input_key)) {
            foreach ($keys as $key) {
                if ($key->api_key == $input_key) {
                    $this->key = $key;
                }
            }
        }

    }

    /**
     *
     * /api/
     *
     * Returns information about the technic pack.
     * Only added is_plus to keep backwards compatibility
     *
     * NOTE: Unsure if anything else is required for backward compatibility
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndex()
    {
        return Response::json(array(
            'api' => 'TechnicSolder',
            'is_plus' => true,
            'version' => SOLDER_VERSION,
            'stream' => SOLDER_STREAM
        ));
    }

    /**
     * /api/modpack/{$modpack}/{$build}
     *
     * /api/modpack
     *  RETURNS  modpacks: {slug: name}, mirror_url: Config::solder.mirror_url
     *
     * /api/modpack?include=full
     *  RETURNS:
     * modpacks: {
     *      name: slug,
     *      display_name: pretty_name,
     *      url: null,
     *      icon,
     *      icon_md5,
     *      logo,
     *      logo_md5,
     *      background,
     *      background_md5,
     *      recommended: name,
     *      latest: name,
     *      builds: {id: name}
     *  }, mirror_url: Config::solder.mirror_url
     *
     * /api/modpack/
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModpack($modpack_id = null, $build_id = null)
    {
        if (empty($modpack_id)) {
            if (Input::has('include')) {
                $include = Input::get('include');
                switch ($include) {// api/modpack?include=
                    default:
                    case "full":
                        $modpacks = $this->fetchModpacks();
                        $m_array = array();
                        foreach ($modpacks['modpacks'] as $slug => $name) {
                            $modpack_id = $this->fetchModpack($slug);
                            $m_array[$slug] = $modpack_id;
                        }
                        $response = array();
                        $response['modpacks'] = $m_array;
                        $response['mirror_url'] = $modpacks['mirror_url'];
                        return Response::json($response);
                        break;
                }
            } else {// /api/modpack/
                return Response::json($this->fetchModpacks());
            }
        } else {
            if (empty($build_id)) // api/modpack/{modpack_id}
                return Response::json($this->fetchModpack($modpack_id));
            else {
	            return Response::json($this->fetchBuild($modpack_id, $build_id,
		            Input::get('is_server', "false") == "false"));
            }
        }
    }

	/**
	 * /api/mod/{$mod}/{$version}
	 *
	 * /api/mod
	 *  RETURNS  mods: {slug: name}
	 *
	 *
	 * /api/mod/{$mod}
	 *  RETURNS:
	 * {
	 *      name: slug,
	 *      display_name: pretty_name,
	 *      author,
	 *      description,
	 *      link,
	 *      donate,
	 *      mod_type,
	 *      versions: {id: name},
	 *  }
	 *
	 * /api/mod/{$mod}/{$version}
	 *  RETURNS:
	 * {
	 *      md5: md5,
	 *      filesize: filesize,
	 *      url: url
	 *  }
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function getMod($mod = null, $version = null)
    {
        $response = array();

        if (empty($mod)) {
            if (Cache::has('modlist') && empty($this->client) && empty($this->key)) {
                $response['mods'] = Cache::get('modlist');
            } else {
                foreach (Mod::all() as $mod) {
                    $response['mods'][$mod->name] = $mod->pretty_name;
                }
                //usort($response['mod'], function($a, $b){return strcasecmp($a['name'], $b['name']);});
                if (isset($response['mods']))
                    Cache::put('modlist', $response['mods'], 5);
            }
            return Response::json($response);
        } else {
            if (Cache::has('mod.' . $mod)) {
                $mod = Cache::get('mod.' . $mod);
            } else {
                $modname = $mod;
                $mod = Mod::where('name', '=', $mod)->first();
                Cache::put('mod.' . $modname, $mod, 5);
            }

            if (empty($mod))
                return $this->error('Mod is missing');
            	//return Response::json(array('error' => 'Mod does not exist'));

            if (empty($version))
                return Response::json($this->fetchMod($mod));

            return Response::json($this->fetchModversion($mod, $version));
        }
    }

	/**
	 * /api/verify/{key}
	 * Verifies the key - TechnicPlatform
	 *
	 * @param null $key
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function getVerify($key = null)
    {
        if (empty($key))
            return Response::json(array("error" => "No API key provided."));

        $key = Key::where('api_key', '=', $key)->first();

        if (empty($key))
        	return $this->error('Key is invalid');
            //return Response::json(array("error" => "Invalid key provided."));
        else
            return Response::json(array("valid" => "Key validated.", "name" => $key->name, "created_at" => $key->created_at));
    }


    /* Private Functions */

    private function fetchMod($mod)
    {
        $response = array();

        $response['name'] = $mod->name;
        $response['pretty_name'] = $mod->pretty_name;
        $response['author'] = $mod->author;
        $response['description'] = $mod->description;
        $response['link'] = $mod->link;
        $response['donate'] = $mod->donatelink;
        $response['mod_type'] = $mod->mod_type;
        $response['versions'] = array();

        foreach ($mod->versions as $version) {
            array_push($response['versions'], $version->version);
        }

        return $response;
    }

    private function fetchModversion($mod, $version)
    {
        $response = array();

        $version = Modversion::where("mod_id", "=", $mod->id)
            ->where("version", "=", $version)->first();

        if (empty($version))
            return array("error" => "Mod version does not exist");

        $response['md5'] = $version->md5;
        $response['filesize'] = $version->filesize;
        $response['url'] = Modfile::getModFolderURL() . $version->mod->name . '/' . $version->mod->name . '-' . $version->version . '.zip';

        return $response;
    }

    private function fetchModpacks()
    {
        if (Cache::has('modpacks') && empty($this->client) && empty($this->key)) {
            $modpacks = Cache::get('modpacks');
        } else {
            $modpacks = Modpack::all();
            if (empty($this->client) && empty($this->key)) {
                Cache::put('modpacks', $modpacks, 5);
            }

        }

        $response = array();
        $response['modpacks'] = array();
        foreach ($modpacks as $modpack) {
            if ($modpack->private == 1 || $modpack->hidden == 1) {
                if (isset($this->client)) {
                    foreach ($this->client->modpacks as $pmodpack) {
                        if ($pmodpack->id == $modpack->id) {
                            $response['modpacks'][$modpack->slug] = $modpack->name;
                        }
                    }
                } else if (isset($this->key)) {
                    $response['modpacks'][$modpack->slug] = $modpack->name;
                }
            } else {
                $response['modpacks'][$modpack->slug] = $modpack->name;
            }
        }

        $response['mirror_url'] = Modfile::getModFolderURL();

        return $response;
    }

    private function fetchModpack($slug)
    {
        $response = array();

        if (Cache::has('modpack.' . $slug) && empty($this->client) && empty($this->key)) {
            $modpack = Cache::get('modpack.' . $slug);
        } else {
            $modpack = Modpack::with('Builds')
                ->where("slug", "=", $slug)->first();
            if (empty($this->client) && empty($this->key))
                Cache::put('modpack.' . $slug, $modpack, 5);
        }

        if (empty($modpack))
            return array("error" => "Modpack does not exist");

        $response['name'] = $modpack->slug;
        $response['display_name'] = $modpack->name;
        $response['url'] = $modpack->url;
        $response['icon'] = $modpack->icon_url;
        $response['icon_md5'] = $modpack->icon_md5;
        $response['logo'] = $modpack->logo_url;
        $response['logo_md5'] = $modpack->logo_md5;
        $response['background'] = $modpack->background_url;
        $response['background_md5'] = $modpack->background_md5;
        $response['recommended'] = $modpack->recommended;
        $response['latest'] = $modpack->latest;
        $response['builds'] = array();

        foreach ($modpack->builds as $build) {
            if ($build->is_published) {
                if (!$build->private || isset($this->key)) {
                    array_push($response['builds'], $build->version);
                } else if (isset($this->client)) {
                    foreach ($this->client->modpacks as $pmodpack) {
                        if ($modpack->id == $pmodpack->id) {
                            array_push($response['builds'], $build->version);
                        }
                    }
                }
            }
        }

        return $response;
    }

    private function fetchBuild($slug, $build, $client_only = true)
    {
        $response = array();

        if (Cache::has('modpack.' . $slug) && empty($this->client) && empty($this->key)) {
            $modpack = Cache::Get('modpack.' . $slug);
        } else {
            $modpack = Modpack::where("slug", "=", $slug)->first();
            if (empty($this->client) && empty($this->key))
                Cache::put('modpack.' . $slug, $modpack, 5);
        }

        if (empty($modpack))
            return array("error" => "Modpack does not exist");

        $buildpass = $build;
        if (Cache::has('modpack.' . $slug . '.build.' . $build) && empty($this->client) && empty($this->key)) {
            $build = Cache::get('modpack.' . $slug . '.build.' . $build);
        } else {
            $build = Build::with('Modversions')
                ->where("modpack_id", "=", $modpack->id)
                ->where("version", "=", $build)->first();
            if (empty($this->client) && empty($this->key))
                Cache::put('modpack.' . $slug . '.build.' . $buildpass, $build, 5);
        }

        if (empty($build))
            return array("error" => "Build does not exist");

        $response['minecraft'] = $build->minecraft;
        $response['java'] = $build->min_java;
        $response['memory'] = $build->min_memory;
        $response['forge'] = $build->forge; // TODO is this needed?
        $response['mods'] = array();

        if (!Input::has('include')) {
            if (false && Cache::has('modpack.' . $slug . '.build.' . $buildpass . 'modversion') && empty($this->client) && empty($this->key)) {
                $response['mods'] = Cache::get('modpack.' . $slug . '.build.' . $buildpass . 'modversion');
            } else {
                foreach ($build->modversions as $modversion) {
                    if (!$client_only or
                        ($modversion->mod->mod_type == Mod::MOD_TYPE_UNIVERSAL or
                            $modversion->mod->mod_type == Mod::MOD_TYPE_CLIENT)) {

                        $response['mods'][] = array(
                            "name" => $modversion->mod->name,
                            "version" => $modversion->version,
                            "md5" => $modversion->md5,
                            "filesize" => $modversion->filesize,
                            'mod_type' => $modversion->mod->mod_type,
                            "url" => Modfile::getModFolderURL() . $modversion->mod->name . '/' . $modversion->mod->name . '-' . $modversion->version . '.zip'
                        );
                    }
                }
                usort($response['mods'], function ($a, $b) {
                    return strcasecmp($a['name'], $b['name']);
                });
                Cache::put('modpack.' . $slug . '.build.' . $buildpass . 'modversion', $response['mods'], 5);
            }
        } else if (Input::get('include') == "mods") {
            if (Cache::has('modpack.' . $slug . '.build.' . $buildpass . 'modversion.include.mods') && empty($this->client) && empty($this->key)) {
                $response['mods'] = Cache::get('modpack.' . $slug . '.build.' . $buildpass . 'modversion.include.mods');
            } else {
                foreach ($build->modversions as $modversion) {

                    $response['mods'][] = array( /// TODO there is probably a better way
                        "name" => $modversion->mod->name,
                        "version" => $modversion->version,
                        "md5" => $modversion->md5,
                        "filesize" => $modversion->filesize,
                        "pretty_name" => $modversion->mod->pretty_name,
                        "author" => $modversion->mod->author,
                        "description" => $modversion->mod->description,
                        "link" => $modversion->mod->link,
                        "donate" => $modversion->mod->donatelink,
                        "mod_type" => $modversion->mod_type,
                        "url" => Modfile::getModFolderURL() . $modversion->mod->name . '/' . $modversion->mod->name . '-' . $modversion->version . '.zip'
                    );

                }
                usort($response['mods'], function ($a, $b) {
                    return strcasecmp($a['name'], $b['name']);
                });
                Cache::put('modpack.' . $slug . '.build.' . $buildpass . 'modversion.include.mods', $response['mods'], 5);
            }
        } else {
            $request = explode(",", Input::get('include'));
            if (Cache::has('modpack.' . $slug . '.build.' . $buildpass . 'modversion.include.' . $request) && empty($this->client) && empty($this->key)) {
                $response['mods'] = Cache::get('modpack.' . $slug . '.build.' . $buildpass . 'modversion.include.' . $request);
            } else {
                foreach ($build->modversions as $modversion) {
                    $data = array(
                        "name" => $modversion->mod->name,
                        "version" => $modversion->version,
                        "md5" => $modversion->md5,
                        "filesize" => $modversion->filesize,
                    );
                    $mod = (array)$modversion->mod;
                    $mod = $mod['attributes'];
                    foreach ($request as $type) {
                        if (isset($mod[$type]))
                            $data[$type] = $mod[$type];
                    }

                    $response['mods'][] = $data;
                }
                usort($response['mods'], function ($a, $b) {
                    return strcasecmp($a['name'], $b['name']);
                });
                Cache::put('modpack.' . $slug . '.build.' . $buildpass . 'modversion.include.' . $request, $response['mods'], 5);
            }
        }

        return $response;
    }

}
