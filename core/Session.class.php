<?php


namespace core;

class Session
{
    protected const FLASH_KEY = 'flash_messages';
    private const SESSION_REGENERATE = 30;

    public function __construct()
    {
        session_start();

        $_SESSION['last_active'] = time();

        //Check if session needs to be regenerated
        self::regenerate();
    }

    public function setFlash($key, $value)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $value
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function __destruct()
    {
        $this->removeFlashMessages();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function destroy()
    {
        session_destroy();
    }

    private function removeFlashMessages()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => $flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    private function regenerate(){
        if(!isset($_SESSION['time'])){
            $_SESSION['time'] = time() + self::SESSION_REGENERATE;
        }

        $time = $_SESSION['time'];
        if($time < time()){
            session_regenerate_id(true);
            $_SESSION['time'] = time() + self::SESSION_REGENERATE;
        }
    }
}