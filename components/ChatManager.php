<?php
namespace jones\wschat\components;

/**
 * Class ChatManager
 * @package \jones\wschat\components
 */
class ChatManager
{
    /** @var \jones\wschat\components\User[] */
    private $users = [];
    /** @var string a name of class to get user instance */
    public $userClassName = null;

    /**
     * Add new user to manager
     *
     * @access public
     * @param $rid
     * @param $id
     * @return void
     */
    public function addUser($rid, $id)
    {
        $user = new User($id, $this->userClassName);
        $user->setRid($rid);
        $this->users[$rid] = $user;
    }

    /**
     * Return if exists user chat room
     *
     * @access public
     * @param $rid
     * @return \jones\wschat\components\ChatRoom|null
     */
    public function getUserChat($rid)
    {
        $user = $this->getUserByRid($rid);
        return $user ? $user->getChat() : null;
    }

    /**
     * Find chat room by id, if not exists create new chat room
     * and assign to user by resource id
     *
     * @access public
     * @param $chatId
     * @param $rid
     * @return \jones\wschat\components\ChatRoom|null
     */
    public function findChat($chatId, $rid)
    {
        $chat = null;
        $storedUser = $this->getUserByRid($rid);
        foreach ($this->users as $user) {
            $userChat = $user->getChat();
            if (!$userChat) {
                continue;
            }
            if ($userChat->getUid() == $chatId) {
                $chat = $userChat;
                echo 'User('.$rid.') will be joined to '.$chatId.PHP_EOL;
                break;
            }
        }
        if (!$chat) {
            echo 'Create new chat room: '.$chatId.' for user('.$rid.')'.PHP_EOL;
            $chat = new ChatRoom();
            $chat->setUid($chatId);
        }
        $storedUser->setChat($chat);
        return $chat;
    }

    /**
     * Get user by resource id
     *
     * @access public
     * @param $rid
     * @return User
     */
    public function getUserByRid($rid)
    {
        return $this->users[$rid];
    }

    /**
     * Find user by resource id and remove it from chat
     *
     * @access public
     * @param $rid
     * @return void
     */
    public function removeUserFromChat($rid)
    {
        $user = $this->getUserByRid($rid);
        $chat = $user->getChat();
        if ($chat) {
            $chat->removeUser($user);
        }
        unset($this->users[$rid]);
    }
}
 