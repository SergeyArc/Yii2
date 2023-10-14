<?php

namespace app\components;

use Yii;
use yii\base\Component;

class Bitrix24 extends Component
{
    const ADMIN_CREDENTIALS_CONFIG_KEY = 'bitrix24_admin_credentials';
    const IS_BITRIX24_ADMIN_CACHE_KEY = 'bitrix24_is_admin_';
    const IS_BITRIX24_ADMIN_CACHE_TIME = 900;
    const USER_SCOPE = ['department', 'crm', 'user', 'entity', 'task', 'tasks_extended', 'im', 'imbot', 'log', 'sonet_group', 'placement', 'bizproc'];
    const ADMIN_SCOPE = ['department', 'crm', 'user', 'entity', 'task', 'tasks_extended', 'im', 'imbot', 'log', 'sonet_group', 'placement'];

    protected static $b24User;
    protected static $b24Admin;
    protected static $user;

    public function init()
    {
        if (!Yii::$app instanceof Yii\console\Application) {

            if (!isset($_REQUEST['AUTH_ID']) && isset($_REQUEST['auth'])) {
                $_REQUEST['DOMAIN'] = $_REQUEST['auth']['domain'];
                $_REQUEST['member_id'] = $_REQUEST['auth']['member_id'];
                $_REQUEST['AUTH_ID'] = $_REQUEST['auth']['access_token'];
                $_REQUEST['REFRESH_ID'] = Null;
                Yii::$app->session->set('b24_auth_data', $_REQUEST);
            }

            if (isset($_REQUEST['AUTH_ID'])) {
                Yii::$app->session->set('b24_auth_data', $_REQUEST);
            }

            if (Yii::$app->session->has('b24_auth_data')){
                self::$user = new \Bitrix24\User\User($this->user());
            }
        }
    }

    public function user()
    {
        if (!self::$b24User) {

            self::$b24User = self::initB24User('user');

            self::$b24User->setOnExpiredToken(function ($bitrix24) {

                $data = $bitrix24->getNewAccessToken();

                if (empty($data['access_token']))
                    return false;

                $b24AuthData = Yii::$app->session->get('b24_auth_data');

                $bitrix24->setAccessToken($data['access_token']);
                $bitrix24->setRefreshToken($data['refresh_token']);

                $b24AuthData['AUTH_ID'] = $data['access_token'];
                $b24AuthData['REFRESH_ID'] = $data['refresh_token'];

                Yii::$app->session->set('b24_auth_data', $b24AuthData);

                return true;
            });
        }

        return self::$b24User;
    }

    public function admin()
    {
        if (!self::$b24Admin) {

            self::$b24Admin = self::initB24User('admin');

            self::$b24Admin->setOnExpiredToken(function ($bitrix24) {
                $data = $bitrix24->getNewAccessToken();

                if (empty($data['access_token'])) {
                    return false;
                }

                $bitrix24->setAccessToken($data['access_token']);
                $bitrix24->setRefreshToken($data['refresh_token']);

                $credentials = self::getAdminCredentials();

                $credentials['AUTH_ID'] = $data['access_token'];
                $credentials['REFRESH_ID'] = $data['refresh_token'];

                self::setAdminCredentials($credentials);

                return true;
            });

        }

        return self::$b24Admin;
    }

    private function initB24User(string $type)
    {
        $config = \Yii::$app->params['app'];

        if ($type == 'user') {
            $scope = self::USER_SCOPE;
            $credentials = Yii::$app->session->get('b24_auth_data');
        } elseif ($type == 'admin') {
            $scope = self::ADMIN_SCOPE;
            $credentials = self::getAdminCredentials();
        }

        $b24User = new \Bitrix24\Bitrix24();
        $b24User->setApplicationScope($scope);
        $b24User->setApplicationId($config['app_id']);
        $b24User->setApplicationSecret($config['secret']);
        $b24User->setRedirectUri($config['uri']);
        $b24User->setDomain($credentials['DOMAIN']);
        $b24User->setMemberId($credentials['member_id']);
        $b24User->setAccessToken($credentials['AUTH_ID']);
        $b24User->setRefreshToken($credentials['REFRESH_ID']);

        return $b24User;
    }

    /**
     * Функция проверяет являться ли пользователь администратором
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return Yii::$app->cache->getOrSet(self::IS_BITRIX24_ADMIN_CACHE_KEY . self::userID(), function () {
            return self::$b24User->call('user.admin', [])['result'];
        }, self::IS_BITRIX24_ADMIN_CACHE_TIME);
    }

    /**
     * Функция возвращает ID пользователя
     *
     * @return mixed
     */

    public function userID()
    {
        return self::userInfo()['ID'];
    }

    /**
     * Функция возвращает информацию о пользователе
     *
     * @return mixed
     */
    public function userInfo()
    {
        return self::$user->current()['result'];
    }

    /**
     * Функция сохраняет в БД доступы администратора
     *
     * @param array $adminCredentials
     */
    public function setAdminCredentials($adminCredentials = [])
    {
        Yii::$app->config->set(self::ADMIN_CREDENTIALS_CONFIG_KEY, serialize($adminCredentials));
    }

    /**
     * Функция возвращает доступы администратора из БД
     *
     * @return array
     */
    public function getAdminCredentials()
    {
        $credentials = Yii::$app->config->get(self::ADMIN_CREDENTIALS_CONFIG_KEY);

        if (!$credentials) {
            return [];
        }

        return unserialize($credentials);
    }

    /**
     * Функция обновляет админ токен в БД
     */
    public function refreshAdminToken()
    {
        $credentials = self::getAdminCredentials();
        $newCredentials = self::admin()->getNewAccessToken();

        $credentials['AUTH_ID'] = $newCredentials['access_token'];
        $credentials['REFRESH_ID'] = $newCredentials['refresh_token'];

        self::setAdminCredentials($credentials);
    }
}
