<?php

/*
 * style muze byt public = kazdy ho smi pouzivat, asi by se uz moc nemel editovat
 * 
 * pokud chci pouzivat public style, tak ho musím zkopírovat k sobě
 * 
 * styly budou mit tagy, pomoci kterych bude mozno filtrovat
 * 
 * jinak style vlastni user,
 * style se da kopirovat, 
 * 
 * v budoucnu kontrolovat, aby kazda vrstva mela prirazeny nejaky style
 */

/**
 * Description of LGStyleManager
 *
 * @author jakubdubrovsky
 */
class LGLayerStyleManager {

    /**
     *
     * @var LGUser
     */
    var $user;

    /**
     *
     * @param LGUser $user 
     */
    public function __construct($user) {
        $this->user = $user;
    }

    function getStylesPublic() {
        $temp = array();
        $v = mydb_query('select layerStyleId from layerStyle where public=1 order by title;');
        while ($z = $v->fetch_array()) {
            $temp[] = $z['layerStyleId'];
        }
        return $temp;
    }

    function getStylesByUser() {
        $temp = array();
        $v = mydb_query('select layerStyleId from layerStyle where userCreatorId=' . (int) $this->user->userId . ' order by title;');
        while ($z = $v->fetch_array()) {
            $temp[] = $z['layerStyleId'];
        }
        return $temp;
    }

    function addStyle($array) {
        $array['userCreatorId'] = $this->user->userId;
        if ($array['content'])
            $array['contentFormated'] = $this->getContentFormated($array['content']);

        $layerStyleId = mydb_insertValuesInTab($array, 'layerStyle');
        if ($_FILES["fileIco"]["tmp_name"])
            $this->uploadLayerIco($_FILES["fileIco"], $layerStyleId);
    }

    function removeStyle($layerStyleId) {
        mydb_query('delete from layerStyle where layerStyleId=' . (int) $layerStyleId . ';');
    }

    function updateStyle($array) {
        $array['userCreatorId'] = $this->user->userId;
        if ($array['content'])
            $array['contentFormated'] = $this->getContentFormated($array['content']);
        if ($_FILES["fileIco"]["tmp_name"])
            $this->uploadLayerIco($_FILES["fileIco"], $array["layerStyleId"]);
        mydb_updateValuesInTab($array, 'layerStyle');
    }

    private function getContentFormated($content) {

        $content = preg_replace('/<StyledLayerDescriptor[^>]*>/', '<StyledLayerDescriptor>', str_replace('sld:', '', str_replace('ogc:', '', $content)));

        $content = preg_replace('/(<Name>).*(<\/Name>)/', '$1%s$2', $content, 1);
        $content = preg_replace('/<Title>.*<\/Title>/', '', $content);
        $content = preg_replace('/<Title[ ]*\/>/', '', $content);

        $content = preg_replace("/\n/", "", $content);
        $content = preg_replace("/\n/", "", $content);
        $content = preg_replace("/\r/", "", $content);
        $content = preg_replace("/\t/", "", $content);
        return $content;
    }

    /*
      function copyStyle($layerStyleId) {
      $v = mydb_query('select * from layerStyle where layerStyleId=' . (int) $layerStyleId . ';');
      $z = $v->fetch_array();
      unset($z['layerStyleId']);
      $newLayerStyleId = mydb_insertValuesInTab($z, 'layerStyle');
      return $newLayerStyleId;
      }
     * */

    private function uploadLayerIco($file, $id) {
        if ((($file["type"] == "image/gif")
                || ($file["type"] == "image/jpeg")
                || ($file["type"] == "image/png")
                || ($file["type"] == "image/jpg"))
                && ($file["size"] < 30000)) {
            if ($file["error"] > 0) {
                echo "Error: " . $file["error"] . "<br />";
            } else {
                /*
                  $fileName = '../../img/style/'.(int)$id.'.png';
                  echo "Upload: " . $file["name"] . "<br />";
                  echo "<a href='".$fileName."'>: " . $fileName . "</a><br />";
                  echo "Type: " . $file["type"] . "<br />";
                  echo "Size: " . ($file["size"] / 1024) . " Kb<br />";
                  echo "Stored in: " . $file["tmp_name"];
                 */
                file_put_contents('../../img/style/' . $id . '.png', file_get_contents($file["tmp_name"]));
            }
        } else {
            echo "Invalid file of img";
        }
    }

}

?>