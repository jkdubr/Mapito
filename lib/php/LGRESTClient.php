<?php

class LGRestClient {

    private $root_url = "";
    private $curr_url = "";
    private $user_name = "";
    private $password = "";
    private $response = "";
    private $method = "";
    private $request_data;
    private $httpHeader;
    private $argumentType;

    public function __construct($root_url = "", $user_name = "", $password = "") {
        $this->root_url = $this->curr_url = $root_url;
        $this->user_name = $user_name;
        $this->password = $password;


        return true;
    }

    public function createRequest($url, $method, $argument = null, $argumentType="array", $httpHeader=array()) {
        $this->curr_url = $url;

        $this->method = $method;
        $this->argumentType = $argumentType;
        $this->setHttpHeader($httpHeader);

        if ($argument) {
            if ($this->argumentType == "array") {
                $this->addPostData($argument);
            } elseif ($this->argumentType == "xml") {
                $this->request_data = $argument;
                $this->httpHeader[] = "Content-type: application/xml";
            } elseif ($this->argumentType == "json") {
                $this->request_data = $argument;
                $this->httpHeader[] = "Content-type: application/json";
            }
        }
    }

    private function addPostData($arr) {

        $this->request_data = "";
        foreach ($data as $key => $val) {
            if (!empty($this->request_data))
                $s.= '&';
            $this->request_data.= $key . '=' . urlencode($val);
        }
    }

    private function setHttpHeader($array) {
        $this->httpHeader = $array;
    }

    public function sendRequest() {
        $ch = curl_init($this->root_url . "/" . $this->curr_url);
        // set options
        //  curl_setopt($ch, CURLOPT_HTTP_VERSION, 1.0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //   curl_setopt($ch, CURLOPT_VERBOSE, true);

        curl_setopt($ch, CURLOPT_USERPWD, $this->user_name . ':' . $this->password);

        //  curl_setopt($ch, CURLOPT_HEADER, 1);
        //      curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        //      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        if ($this->httpHeader)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->httpHeader);

        if ($this->method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, True);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request_data);
        } elseif ($this->method == 'PUT') {
            $tmpFile = tmpfile();
            fwrite($tmpFile, $this->request_data);
            fseek($tmpFile, 0);
            curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_INFILE, $tmpFile);
            curl_setopt($ch, CURLOPT_INFILESIZE, strlen($this->request_data));
            //    echo fread($tmpFile, 10);
        } elseif ($this->method == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }


        $this->response = curl_exec($ch);

/*
        $info = curl_getinfo($ch);

        print_r($info);
*/
        curl_close($ch);
        if ($tmpFile)
            fclose($tmpFile);


        return $this->response;
    }

}

?>