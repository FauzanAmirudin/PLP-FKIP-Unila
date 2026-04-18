<?php
defined('GF_BASE_PATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Contorler
|--------------------------------------------------------------------------
|
| Controler aplications
|
*/
class captcha extends gf_controller
{
    function __construct()
    {
        parent::__construct();
    }
    public function index($req = 'home')
    {
        // OPTIONAL Change configuration...
        $this->captcha->session_var = 'secretword';
        $this->captcha->imageFormat = 'png';
        $this->captcha->width = 250;
        $this->captcha->height = 100;
        $this->captcha->minWordLength = 4;
        $this->captcha->maxWordLength = 6;
        $this->captcha->lineWidth = 3;
        $this->captcha->scale = 4;
        $this->captcha->blur = true;
        // $captcha->resourcesPath = "";

        /** 
         * Dictionary word file (empty for random text, words/en.php, or words/es.php)
         * $this->captcha->wordsFile = '';
         * OPTIONAL Simple autodetect language example
         * if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
         *     $langs = array('en', 'es');
         *     $lang  = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
         *     if (in_array($lang, $langs)) {
         *         $this->captcha->wordsFile = "words/$lang.php";
         *     }
         * }
         */
        // Image generation
        $this->captcha->CreateImage();
    }
}
