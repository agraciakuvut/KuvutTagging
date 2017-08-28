<?php

namespace agraciakuvut;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

/**
 * Class KuvutTagging
 *
 */
class KuvutTagging extends AbstractProvider
{

    /**
     * Production API URL.
     *
     * @const string
     */
    const BASE_KUVUT_TAGGING_URL = 'https://tagging.kuvut.com/api/1.0';

    protected $accessToken;

    public function getAccessToken($grant = 'client_credentials', array $params = [])
    {
        if (isset($params['refresh_token'])) {
            throw new \Exception('Kuvut Tagging does not support token refreshing.');
        }

        if (empty($this->accessToken)) {
            $this->accessToken = parent::getAccessToken($grant, $params);
        }

        return $this->accessToken;
    }

    public function getBaseAuthorizationUrl()
    {
        return static::BASE_KUVUT_TAGGING_URL . '/authorize/';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return static::BASE_KUVUT_TAGGING_URL . '/token/';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return '';
    }

    protected function getDefaultScopes()
    {
        return ['basic'];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (!empty($data['error'])) {
            $message = $data['error'];
            throw new \Exception($message, -1);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return null;
    }

    protected function getBaseOptions(){
        return ['headers' => ['Authorization' => 'Bearer ' . $this->getAccessToken()->getToken()]];
    }

    protected function parseUrl(string $endpoint, string $platform, array $parameters = []){
        $url = static::BASE_KUVUT_TAGGING_URL . $endpoint . '?platform=' . $platform;
        foreach ($parameters as $parameter => $value){
            $url .= '&' . $parameter . '=' . urlencode($value);
        }
        return $url;
    }

    protected function getResponseBaseOptions($method, $url){
        $options = $this->getBaseOptions();
        $request = $this->getRequest($method, $url, $options);
        $response = $this->getParsedResponse($request);
        return $response;
    }

    public function getAllActions(string $platform)
    {
        $method = 'GET';
        $url = $this->parseUrl('/action/list/', $platform);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function addAction(string $platform, array $options = [])
    {
        $method = 'PUT';

        if(empty($options['name'])){
           throw new \Exception('Name missing');
        }
        if(empty($options['description'])){
            throw new \Exception('Description missing');
        }

        $url = $this->parseUrl('/action/add/', $platform, $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function editAction(string $platform, array $options = [])
    {
        $method = 'POST';
        if(empty($options['action'])){
            throw new \Exception('Action missing');
        }
        $url = $this->parseUrl('/action/edit/', $platform, $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function deleteAction(string $platform, array $options = [])
    {
        $method = 'DELETE';
        if(empty($options['action'])){
            throw new \Exception('Action missing');
        }
        $url = $this->parseUrl('/action/delete/', $platform, $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function assignAction(string $platform, array $options = [])
    {
        $method = 'PUT';
        if(empty($options['action'])){
            throw new \Exception('Action missing');
        }
        if(empty($options['uid'])){
            throw new \Exception('uid missing');
        }
        $url = $this->parseUrl('/action/assign/', $platform, $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function getAllTags(string $platform)
    {
        $method = 'GET';
        $url = $this->parseUrl('/tag/list/', $platform);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function addTag(string $platform, array $options = [])
    {
        $method = 'PUT';

        if(empty($options['name'])){
            throw new \Exception('Name missing');
        }

        if(empty($options['description'])){
            throw new \Exception('Description missing');
        }

        $url = $this->parseUrl('/tag/add/', $platform, $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function editTag(string $platform, array $options = [])
    {
        $method = 'POST';
        if(empty($options['tag'])){
            throw new \Exception('Tag missing');
        }
        $url = $this->parseUrl('/tag/edit/', $platform, $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function assignTag(string $platform, array $options = [])
    {
        $method = 'PUT';
        if(empty($options['action'])){
            throw new \Exception('Action missing');
        }
        if(empty($options['tag'])){
            throw new \Exception('Tag missing');
        }
        $url = $this->parseUrl('/tag/assign/', $platform, $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function getTag(string $platform, array $options = [])
    {
        $method = 'GET';
        if(empty($options['tag'])){
            throw new \Exception('Tag missing');
        }
        $url = $this->parseUrl('/tag/get/', $platform, $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function getUserTags(string $platform, array $options = [])
    {
        $method = 'GET';
        if(empty($options['uid'])){
            throw new \Exception('uid missing');
        }
        $url = $this->parseUrl('/user/get-tags/', $platform, $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function getTagUsers(string $platform, array $options = [])
    {
        $method = 'GET';
        if(empty($options['tag'])){
            throw new \Exception('Tag missing');
        }
        $url = $this->parseUrl('/user/get-users/', $platform, $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function getAllCategories(string $platform)
    {
        $method = 'GET';
        $url = $this->parseUrl('/category/list/', $platform);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function addCategory(string $platform, array $options = [])
    {
        $method = 'PUT';

        if(empty($options['name'])){
            throw new \Exception('Name missing');
        }

        if(empty($options['description'])){
            throw new \Exception('Description missing');
        }

        $url = $this->parseUrl('/category/add/', $platform, $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function editCategory(string $platform, array $options = [])
    {
        $method = 'POST';
        if(empty($options['category'])){
            throw new \Exception('Category missing');
        }
        $url = $this->parseUrl('/category/edit/', $platform, $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function assignCaegory(string $platform, array $options = [])
    {
        $method = 'PUT';
        if(empty($options['category'])){
            throw new \Exception('Category missing');
        }
        if(empty($options['tag'])){
            throw new \Exception('Tag missing');
        }
        $url = $this->parseUrl('/category/assign/', $platform, $options);
        return $this->getResponseBaseOptions($method, $url);
    }


}