<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
|  
|--------------------------------------------------------------------------
|  
|  
|  
*/
/*
    Use the static method getInstance to get the object.
*/

class gf_sessions
{
    const SESSION_STARTED = TRUE;
    const SESSION_NOT_STARTED = FALSE;

    // The state of the session
    private $sessionState = self::SESSION_NOT_STARTED;

    // THE only instance of the class
    private static $instance;


    public function __construct()
    {
    }


    /**
     *    Returns THE instance of 'Session'.
     *    The session is automatically initialized if it wasn't.
     *   
     *    @return    object
     **/

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        self::$instance->startSession();

        return self::$instance;
    }


    /**
     *    (Re)starts the session.
     *   
     *    @return    bool    TRUE if the session has been initialized, else FALSE.
     **/

    public function startSession()
    {
        if ($this->sessionState == self::SESSION_NOT_STARTED) {
            $this->sessionState = session_start();
        }

        return $this->sessionState;
    }


    /**
     *    Stores datas in the session.
     *    Example: $instance->foo = 'bar';
     *   
     *    @param    name    Name of the datas.
     *    @param    value    Your datas.
     *    @return    void
     **/

    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }


    /**
     *    Gets datas from the session.
     *    Example: echo $instance->foo;
     *   
     *    @param    name    Name of the datas to get.
     *    @return    mixed    Datas stored in session.
     **/

    public function __get($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
    }


    public function __isset($name)
    {
        return isset($_SESSION[$name]);
    }


    public function __unset($name)
    {
        unset($_SESSION[$name]);
    }


    /**
     *    Destroys the current session.
     *   
     *    @return    bool    TRUE is session has been deleted, else FALSE.
     **/

    public function destroy()
    {
        if ($this->sessionState == self::SESSION_STARTED) {
            $this->sessionState = !session_destroy();
            unset($_SESSION);

            return !$this->sessionState;
        }

        return FALSE;
    }
}

if (!function_exists("session_get")) {
    function session_get($tag = '')
    {
        // // We get the instance
        // $data = gf_sessions::getInstance();

        // // Let's store datas in the session
        // $data->nickname = 'Someone';
        // $data->age = 18;

        // printf( '<pre>%s</pre>' , print_r( $_SESSION , TRUE ));

        // // TRUE
        // var_dump( isset( $data->nickname ));

        // // We destroy the session
        // $data->destroy();
        if (empty($tag)) {
            return $_SESSION;
        } else {
            if (isset($_SESSION[$tag])) {
                return $_SESSION[$tag];
            } else {
                return NULL;
            }
        }
    }
}

if (!function_exists("session_save")) {
    function session_save($tag, $value)
    {
        return $_SESSION[$tag] = $value;
    }
}

if (!function_exists("session_delete")) {
    function session_delete($tag)
    {
        if (isset($_SESSION[$tag])) {
            unset($_SESSION[$tag]);
            return TRUE;
        } else return FALSE;
                
    }
}
