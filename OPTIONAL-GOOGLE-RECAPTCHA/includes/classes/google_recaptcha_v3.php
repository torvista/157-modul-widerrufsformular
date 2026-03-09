<?php
/**
 * @package Google reCaptcha V3 for Zen Cart German
 * @copyright Copyright 2021 Numinix (www.numinix.com)
 * @copyright Copyright 2003-2026 Zen Cart Development Team
 * Zen Cart German Version - www.zen-cart-pro.at 
 * @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
 * @version $Id: google_recaptcha_v3.php for Widerrufsformular 2026-03-09 20:42:29Z webchills $
 */
class GoogleRecaptchaV3
{
    private $enable;
    private $siteKey;
    private $siteSecret;
    private $actionsScore;
    private $formActions;
    private $notifiersToCheck;
    
    function __construct(){
        
        $this->enable = false;
        if(defined('GOOGLE_RECAPTCHA_V3_ENABLE') && GOOGLE_RECAPTCHA_V3_ENABLE == 'true'){
            if(defined('GOOGLE_RECAPTCHA_V3_SITE_KEY') && defined('GOOGLE_RECAPTCHA_V3_SECRET_KEY') 
                && !empty(GOOGLE_RECAPTCHA_V3_SITE_KEY) && !empty(GOOGLE_RECAPTCHA_V3_SECRET_KEY)){
                    $this->enable = true;
                    $this->siteKey = GOOGLE_RECAPTCHA_V3_SITE_KEY;
                    $this->siteSecret = GOOGLE_RECAPTCHA_V3_SECRET_KEY;
                    
                    $this->actionsScore = $this->mountActionsScoreArray();
                    $this->formActions = $this->mountFormActionsArray();
                    
                    // add/remove here the "notifiers => message class" that should be checked
                    // don't forget to link the html form name with an action on the plugin config
                    $this->notifiersToCheck = array(
                        'NOTIFY_CONTACT_US_CAPTCHA_CHECK' => 'contact',
                        'NOTIFY_WIDERRUFSFORMULAR_CAPTCHA_CHECK' => 'widerrufsformular',
			'NOTIFY_ASK_A_QUESTION_CAPTCHA_CHECK' => 'ask_a_question',
                       // 'NOTIFY_CREATE_ACCOUNT_CAPTCHA_CHECK' => 'create_account',
                       // 'NOTIFY_REVIEWS_WRITE_CAPTCHA_CHECK' => 'review_text',
                        //'NOTIFY_HEADER_START_CHECKOUT_CONFIRMATION' => 'checkout_confirmation',
                        //'NOTIFY_HEADER_START_CHECKOUT_PROCESS' => 'checkout',
                        //'NOTIFY_HEADER_START_LOGIN' => 'login',
                        //'NOTIFY_HEADER_START_OPRC_LOGIN' => 'checkout',
                    );
                    
            }else{
                global $messageStack;
                $messageStack->add('header', RECAPTCHA_V3_MISSING_API_KEYS, 'warning');
            }
        }
        
        if($this->enable){
            require_once __DIR__ . '/observers/google/autoload.php';
        }
        
    }
    
    private function mountActionsScoreArray(){
        
        $return = array();
        $actions = explode(',', GOOGLE_RECAPTCHA_V3_ACTIONS_MIN_SCORE);
        foreach ($actions as $action){
            $info = explode('=', $action);
            if(isset($info[1])){
                $return[trim($info[0])] = (float)$info[1];
            }
        }
        if(!isset($return['default'])) $return['default'] = 0.5;
        
        return $return;
    }
    
    private function mountFormActionsArray(){
        $return = array();
        $forms = explode(',', GOOGLE_RECAPTCHA_V3_FORM_ACTIONS);
        foreach ($forms as $form){
            $info = explode('=', $form);
            if(isset($info[1])){
                $return[trim($info[0])] = trim($info[1]);
            }
        }
        return $return;
    }
    
    function validate_recaptcha($responseToken, $messagesClass = 'recaptcha'){
        global $messageStack;
        
        if(!$this->is_enable()) return ['success' => false, 'error-codes' => array('recaptcha-disabled')];
        
        
        $recaptcha = new \ReCaptcha\ReCaptcha($this->siteSecret, new \ReCaptcha\RequestMethod\CurlPost());
        $response = $recaptcha->verify($responseToken, $_SERVER['REMOTE_ADDR']);
        
        if(!empty($response)){
            if($response->isSuccess()){
                
                $minScore = $this->actionsScore['default'];
                if(!empty($response->getAction()) && isset($this->actionsScore[$response->getAction()])){
                    $minScore = $this->actionsScore[$response->getAction()];
                }
                
                if($response->getScore() >= $minScore){
                    return true;
                }else{
                    $messageStack->add($messagesClass, sprintf(RECAPTCHA_V3_LOW_SCORE, number_format($minScore, 1), number_format($response->getScore(), 1)), 'error');
                }
            }else if( !empty($response->getErrorCodes()) ){
                foreach ($response->getErrorCodes() as $error){
                    $messageStack->add($messagesClass, $this->get_recaptcha_message($error), 'error');
                }
            }
        }else{
            $messageStack->add($messagesClass, RECAPTCHA_V3_VALIDATION_CONNECTION_ERROR ,'error');
        }
        
        return false;
    }
    
    function get_recaptcha_message($mesage_code){
        $constant = 'RECAPTCHA_V3_' . str_replace('-', '_', strtoupper($mesage_code) );
        if(defined($constant)){
            return constant($constant);
        }
        return $mesage_code;
    }
    
    function get_notifiers_to_check(){
        if($this->enable && is_array($this->notifiersToCheck)) {
            return array_keys($this->notifiersToCheck);
        }
        return array();
    }
    
    function get_notifier_message_class($pageNotifier){
        if($this->enable && isset($this->notifiersToCheck[$pageNotifier])){
            return $this->notifiersToCheck[$pageNotifier];
        }
        return $pageNotifier;
    }
    
    function get_form_actions_as_json(){
        return json_encode($this->formActions);
    }
    
    
    function is_enable() {
        return $this->enable;
    }
    
    function get_site_key(){
        return $this->siteKey;
    }
    
    function get_page_action($page){
        return 'default';
    }
}
