<?php

class TwitchAPI {

    var $redirect_url = '';
    var $client_id = '';
    var $client_secret = '';
    var $access_token = NULL;

    public function setRedirectURL($url) {
        $this->redirect_url = $url;
    }

    public function setClientID($clientid) {
        $this->client_id = $clientid;
    }

    public function setClientSecret($clientsecret) {
        $this->client_secret = $clientsecret;
    }

    public function getLoginURL() {
        return 'https://api.twitch.tv/kraken/oauth2/authorize?response_type=code&client_id=' . $this->client_id . '&redirect_uri=' . urlencode($this->redirect_url);
    }

    public function handleCallback() {
        if (!isset($_GET['code']))
            return false;
        $request = $this->request('/oauth2/token?grant_type=authorization_code&client_id=' . $this->client_id . '&client_secret=' . $this->client_secret . '&code=' . $_GET['code'] . '&redirect_uri=' . urlencode($this->redirect_url), 'POST');
        if (!isset($request->access_token))
            return false;
        $this->access_token = $request->access_token;
        return $request->access_token;
    }

    public function getUserData() {
        return $this->request('');
    }

    private function request($path, $method = 'GET', $postData = array()) {
        $ch = curl_init('https://api.twitch.tv/kraken' . $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        $header = array('Client-ID: ' . $this->client_id);
        if ($this->access_token !== NULL)
            $header[] = 'Authorization: OAuth ' . $this->access_token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output);
    }

}
