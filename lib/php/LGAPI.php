<?php

/**
 * Description of index
 *
 * @author JakubDubrovsky
 */
class LGAPI {

    /**
     *
     * @var LGUser
     */
    var $user;
    var $request;

    public function __construct() {


//prihlasim uzivatele
    }

    function requestJSON($json) {


        $this->request = json_decode(str_replace('\"', '"', $json));

        if ($this->request->userHash || $this->request->request == "user.login") {
            $this->user = new LGUser();
            $this->user->hash($this->request->userHash);
        }
    }

    /**
     * Vsechny vrstvy ve skolzkach daneho planu
     * 
     * @param type $planId
     * @return string 
     */
    function layerList($planId) {



        $temp = array();
        $layerManager = new LGLayerManager($this->user);
        foreach ($layerManager->getLayerFoldersByPlan($planId) as $layerFolderId) {
            $folder = "";
            $layerFolder = new LGLayerFolder($layerFolderId, $this->user->isUser());

            $folder->data = $layerFolder->title;
            $folder->state = "open";

            foreach ($layerFolder->layers as $layerId) {
                $layer = new LGLayer($layerId);
                $item->url = $layer->url;
                $item->attr->id = $layer->namespace . ":" . $layer->name;
                $item->data = $layer->title;
                $item->opacity = $layer->opacity;
                $item->palete = $layer->palete;
                $item->type = $layer->type;
                $item->legendImage = $layer->legendImageUrl;

                $folder->children[] = $item;
            }




            $temp[] = $folder;
        }
        return $temp;
    }

    function layerForJSTree($planId) {
        $temp = array();
        $zIndexs = array();

        $layerManager = new LGLayerManager($this->user);
        foreach ($layerManager->getLayerFoldersByPlan($planId) as $layerFolderId) {
            $folder = "";
            $layerFolder = new LGLayerFolder($layerFolderId, ($this->user ? $this->user->systemUser->privilegeForPlan($planId) : FALSE));

            if (count($layerFolder->layers)) {
                $folder->data = $layerFolder->title;
                $folder->state = "open";

                foreach ($layerFolder->layers as $layerId) {
                    unset($item);


                    $layer = new LGLayer($layerId);

                    if ($layer->isActive) {
                        $layerStyle = new LGLayerStyle($layer->layerStyleId);

                        $item->url = $layer->url;
                        $item->attr->id = ($layer->namespace ? ($layer->namespace . ":") : "") . $layer->name;
                        $item->attr->class = ($layer->visibility ? "jstree-checked" : "jstree-unchecked");
                        $item->data = $layer->title;
                        $item->opacity = $layer->opacity / 10;
                        $item->format = $layer->format;
                        $item->palete = $layer->palete;
                        $item->type = $layer->type;
                        $item->legendImage = $layer->legendImageUrl;
                        $item->queryable = $layer->queryable;
                        $item->inLegend = $layer->isInLegend;
                        $item->printable = $layer->printable;
                        $item->transparent = (bool)$layer->transparent;
                        $item->visibility = $layer->visibility;
                        $item->isLockedForGeometry = $layer->isLockedForGeometry;
                        $item->layerId = $layer->layerId;
                        $item->layerStyleId = ($layerStyle->contentFormated ? $layerStyle->layerStyleId : 0);

                        switch ($layer->type) {
                            case 'RASTR':
                                $zIndexBasic = 1;
                                break;
                            case 'MULTIPOLYGON':
                                $zIndexBasic = 100;
                                break;
                            case 'LINESTRING':
                                $zIndexBasic = 200;
                                break;
                            case 'POINT':
                                $zIndexBasic = 300;
                                break;

                            default:
                                break;
                        }
                        $zIndex = $zIndexBasic + $layer->rank;
                        while (in_array($zIndex, $zIndexs)) {
                            $zIndex++;
                        }
                        $zIndexs[] = $zIndex;
                        $item->zIndex = $zIndex;

                        $folder->children[] = $item;
                    }
                }
                $temp[] = $folder;
            }
        }
        return $temp;
    }

    /**
     *
     * @param type $planId
     * @param type $layerName
     * @param type $layerFolder
     * @param type $layerType 
     */
    function layerAdd($planId, $layerName, $layerFolderId, $layerType) {
        $temp["layerFolderId"] = $layerFolderId;
        $temp["type"] = $layerType;
        $temp["planId"] = $planId;
        $temp["name"] = $layerName;
        $temp["title"] = $layerName;
        $temp["url"] = $GLOBALS["LGSettings"]->admin_url . "/module/proxy/api/proxyWMS.php?";
        $temp["isLockedGeometry"] = 0;

        $layerManager = new LGLayerManager($this->user);
        $layerManager->createLayerOnDB($temp);


        $geoserverApi = new GeoserverAPILigeo();
        $geoserverApi->api->reloadServer();
    }

    function formDetail($formId) {
        $form = new LGForm($formId);
        return $form->toDictionaryWithFormElements();
    }

    function formsByPlan($planId) {
        $temp = array();

        $formManager = new LGFormManager($this->user);
        foreach ($formManager->getFormsByPlan($planId) as $formId) {
            $form = new LGForm($formId);
            $temp[] = $form->toDictionary();
        }
        return $temp;
    }

    function formList() {
        $temp = array();

        $formManager = new LGFormManager($this->user);
        foreach ($formManager->getForms() as $formId) {
            $form = new LGForm($formId);
            $temp[] = $form->toDictionary();
        }
        return $temp;
    }

    function formCollectPOST($formId) {
        $layerManager = new LGLayerManager($this->user);
        $temp->save = $layerManager->collectDataFromForm($formId, $_POST);
        return $temp->save;
    }

    function formUpload($formId) {
        $layerManager = new LGLayerManager($this->user);
        return $layerManager->uploadFile($formId);
    }

    function getCapabilities() {
        /**
         * ted potrebuji:
         * 1/ plány, na které mám právo edit/view
         * 2/ vrstvy daných plánů (dle přihlášení dám private/public vrstvy)
         * 3/ formuláře dané vrstvy (dám jen pro právo edit)
         * 
         * 4/ uložit data z form
         * 5/ dát form data
         * 
         * ?json={"request":"form","param":{"formId":8}}
         * 
         * ?json={"request":"getCapabilities"}
         * 
         * /?json={%22request%22:%22layersForJSTree%22,%22param%22:{%22planId%22:22}}
         * 
         * 
         *  */
        $temp->about = "About service...";

        $req->name = "form";

        $param->name = "formId";
        $param->type = "int";

        $req->param[] = $param;

        $temp->request[] = $req;

        return $temp;
    }

    function userLogin($mail, $password) {
//  $this->user = new LGUser();
        $temp = array();
        $temp["userHash"] = $this->user->login($mail, $password);
        if (!$temp["userHash"])
            $temp["err"] = "login";
        return $temp;
    }

    function userHash() {
        $temp = array();
        $temp["userHash"] = $this->user->userHash;
        $temp["name"] = $this->user->systemUser->title;

        return $temp;
    }

    function userLogout() {
        $this->user->logout();
    }

    function planDetail($planName) {
        $v = mydb_query('select planId from plan where name="' . secure($planName) . '";');
        $z = $v->fetch_array();
        $plan = new LGPlan($z['planId']);

        $temp->title = $plan->title;
        $temp->planId = $plan->planId;
        $temp->txt = $plan->txt;
        $temp->mapCenterLat = $plan->mapCenterLat;
        $temp->mapCenterLon = $plan->mapCenterLon;
        $temp->mapZoom = $plan->mapZoom;
        $temp->privilege = ($this->user ? $this->user->getPrivilegeForPlan($plan->planId) : 0);

        return $temp;
    }

    function styleSld($layerId) {

        //  file_put_contents("out.txt",gmdate("M d Y H:i:s", time()), FILE_APPEND);

        $v = mydb_query('select s.contentFormated as content,if(l.namespace!="",concat(l.namespace,":",l.name),l.name) as name from layer as l, layerStyle as s where l.layerStyleId=s.layerStyleId and l.layerId= ' . (int) $layerId . ';');
        if ($z = $v->fetch_array()) {
            return htmlspecialchars_decode(sprintf($z['content'], $z['name']), ENT_QUOTES);
        }
    }

    function planList() {
        $temp = array();
        $planManager = new LGPlanManager($user);
        foreach ($planManager->getPlans() as $planId) {
            $plan = new LGPlan($planId);

            $item->name = $plan->name;
            $item->title = $plan->title;
            $item->planId = $plan->planId;
            $item->mapCenterLat = $plan->mapCenterLat;
            $item->mapCenterLon = $plan->mapCenterLon;
            $item->mapZoom = $plan->mapZoom;

            $temp[] = $item;
        }
        return $temp;
    }

    function planPublicList() {
        $temp = array();
        $planManager = new LGPlanManager($user);
        foreach ($planManager->getPlansPublic() as $planId) {
            $plan = new LGPlan($planId);

            $item->name = $plan->name;
            $item->title = $plan->title;
            $item->planId = $plan->planId;
            $item->mapCenterLat = $plan->mapCenterLat;
            $item->mapCenterLon = $plan->mapCenterLon;
            $item->mapZoom = $plan->mapZoom;

            $temp[] = $item;
        }
        return $temp;
    }

    function planPublicKML() {
        $temp = array();
        $planManager = new LGPlanManager($user);
        foreach ($planManager->getPlansPublic() as $planId) {
            $plan = new LGPlan($planId);

            $item->name = $plan->name;
            $item->title = $plan->title;
            $item->planId = $plan->planId;
            $item->mapCenterLat = $plan->mapCenterLat;
            $item->mapCenterLon = $plan->mapCenterLon;
            $item->mapZoom = $plan->mapZoom;

            $temp[] = $item;
        }
        return $temp;
    }

    function layerBasic($layerIds) {
        $temp = array();
        foreach ($layerIds as $layerId) {

            $layer = new LGLayer($layerId);
            $item->id = ($layer->namespace ? ($layer->namespace . ":") : "") . $layer->name;
            $item->opacity = $layer->opacity;
            $item->url = $layer->url;
            $item->layerId = $layerId;

            $temp[] = $item;
            unset($item);
        }

        return $temp;
    }

    function result() {
        $res = array();

        switch ($this->request->request) {
            case "layer.forJSTree":
                $res = $this->layerForJSTree($this->request->param->planId);
                break;
            case "layer.list":
                $res = $this->layerList($this->request->param->planId);
                break;
            case "layer.basic":
                $res = $this->layerBasic($this->request->param->layerIds);
                break;
            case "layer.add":
                $res = $this->layerAdd($this->request->param->planId, $this->request->param->layerName, $this->request->param->folderId, $this->request->param->layerType);
                break;


            case "form.detail":
                $res = $this->formDetail($this->request->param->formId);
                break;
            case "form.list":
                $res = $this->formList();
                break;
            case "form.byPlan.list":
                $res = $this->formsByPlan($this->request->param->planId);
                break;
            case "form.collectPOST":
                $res = $this->formCollectPOST($this->request->param->formId);
                break;
            case "form.upload":
                $res = $this->formUpload($this->request->param->formId);
                break;


            case "user.login":
                $res = $this->userLogin($this->request->param->mail, $this->request->param->password);
                break;
            case "user.logout":
                $res = $this->userLogout();
                break;
            case "user.hash":
                $res = $this->userHash();
                break;


            case "style.sld":
                $res = $this->styleSld($this->request->param->layerId);
                break;


            case "plan.list":
                $res = $this->planList();
                break;
            case "plan.public.list":
                $res = $this->planPublicList();
                break;
            case "plan.public.kml":
                $res = $this->planPublicKML();
                break;
            case "plan.detail":
                $res = $this->planDetail($this->request->param->name);
                break;


            default:
                $res = $this->getCapabilities();
                break;
        }
        $this->resultJSON($res);

        if (is_string($res)) {
            return $res;
        } else {
            return $this->resultJSON($res);
        }
    }

    function resultJSON($res) {

        return str_replace('\/', '/', json_encode($res));
    }

}

?>