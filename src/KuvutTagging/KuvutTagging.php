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

    protected $platform;
    protected $accessToken;

    public function __construct(array $options = [], array $collaborators = [])
    {
        parent::__construct($options, $collaborators);
        if (!empty($options['platform'])) {
            $this->platform = $options['platform'];
        }
    }

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

    protected function getBaseOptions()
    {
        return ['headers' => ['Authorization' => 'Bearer ' . $this->getAccessToken()->getToken()]];
    }

    protected function parseUrl(string $endpoint, array $parameters = [])
    {
        $platform = empty($parameters['platform']) ? $this->platform : $parameters['platform'];

        $url = static::BASE_KUVUT_TAGGING_URL . $endpoint . '?platform=' . $platform;
        foreach ($parameters as $parameter => $value) {
            $url .= '&' . $parameter . '=' . urlencode($value);
        }
        return $url;
    }

    protected function getResponseBaseOptions($method, $url)
    {
        $options = $this->getBaseOptions();
        $request = $this->getRequest($method, $url, $options);
        $response = $this->getParsedResponse($request);
        return $response;
    }

    public function getAllActions(array $options = [])
    {
        $method = 'GET';
        $url = $this->parseUrl('/action/list/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function addAction(array $options = [])
    {
        $method = 'PUT';

        if (empty($options['name'])) {
            throw new \Exception('Name missing');
        }
        if (empty($options['description'])) {
            throw new \Exception('Description missing');
        }

        $url = $this->parseUrl('/action/add/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function editAction(array $options = [])
    {
        $method = 'POST';
        if (empty($options['action'])) {
            throw new \Exception('Action missing');
        }
        $url = $this->parseUrl('/action/edit/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function deleteAction(array $options = [])
    {
        $method = 'DELETE';
        if (empty($options['action'])) {
            throw new \Exception('Action missing');
        }
        $url = $this->parseUrl('/action/delete/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function assignAction(array $options = [])
    {
        $method = 'PUT';
        if (empty($options['action'])) {
            throw new \Exception('Action missing');
        }
        if (empty($options['uid'])) {
            throw new \Exception('uid missing');
        }
        $url = $this->parseUrl('/action/assign/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function getAllTags(array $options = [])
    {
        $method = 'GET';
        $url = $this->parseUrl('/tag/list/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function addTag(array $options = [])
    {
        $method = 'PUT';

        if (empty($options['name'])) {
            throw new \Exception('Name missing');
        }

        if (empty($options['description'])) {
            throw new \Exception('Description missing');
        }

        $url = $this->parseUrl('/tag/add/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function editTag(array $options = [])
    {
        $method = 'POST';
        if (empty($options['tag'])) {
            throw new \Exception('Tag missing');
        }
        $url = $this->parseUrl('/tag/edit/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function assignTag(array $options = [])
    {
        $method = 'PUT';
        if (empty($options['action'])) {
            throw new \Exception('Action missing');
        }
        if (empty($options['tag'])) {
            throw new \Exception('Tag missing');
        }
        $url = $this->parseUrl('/tag/assign/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function unassignTag(array $options = [])
    {
        $method = 'PUT';
        if (empty($options['action'])) {
            throw new \Exception('Action missing');
        }
        if (empty($options['tag'])) {
            throw new \Exception('Tag missing');
        }
        $url = $this->parseUrl('/tag/unassign/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function getTag(array $options = [])
    {
        $method = 'GET';
        if (empty($options['tag'])) {
            throw new \Exception('Tag missing');
        }
        $url = $this->parseUrl('/tag/get/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function getUserTags(array $options = [])
    {
        $method = 'GET';
        if (empty($options['uid'])) {
            throw new \Exception('uid missing');
        }
        $url = $this->parseUrl('/user/get-tags/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function getTagUsers(array $options = [])
    {
        $method = 'GET';
        if (empty($options['tag'])) {
            throw new \Exception('Tag missing');
        }
        $url = $this->parseUrl('/user/get-users/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function getAllCategories(array $options = [])
    {
        $method = 'GET';
        $url = $this->parseUrl('/category/list/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function addCategory(array $options = [])
    {
        $method = 'PUT';

        if (empty($options['name'])) {
            throw new \Exception('Name missing');
        }

        if (empty($options['description'])) {
            throw new \Exception('Description missing');
        }

        $url = $this->parseUrl('/category/add/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function editCategory(array $options = [])
    {
        $method = 'POST';
        if (empty($options['category'])) {
            throw new \Exception('Category missing');
        }
        $url = $this->parseUrl('/category/edit/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }

    public function assignCaegory(array $options = [])
    {
        $method = 'PUT';
        if (empty($options['category'])) {
            throw new \Exception('Category missing');
        }
        if (empty($options['tag'])) {
            throw new \Exception('Tag missing');
        }
        $url = $this->parseUrl('/category/assign/', $options);
        return $this->getResponseBaseOptions($method, $url);
    }


}