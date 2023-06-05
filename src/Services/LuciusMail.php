<?php

namespace Drupal\demo_mail\Services;


/**
 * Class DemoMail.
 */
class DemoMail{


   * @var $route
   */
  protected $mailer;

  /**
   * @var $current_user
   */
  protected $current_user;

  /**
   * Constructor.
   *
   * @param $mailer
   * @param $current_user
   * @param $members
   */
  public function __construct($mailer, $current_user) {
    $this->mailer = $mailer;
    $this->current_user = $current_user;
  }

  /**
   * Sends the mails.
   * @param array $params
   */
  public function sendMail($params = array()) {

    // Build mail vars.
    $module = 'demo_mail';
    $key = 'demo_mail';
    $lang_code = $this->current_user->getPreferredLangcode();
    $params['base_url'] = \Drupal::request()->getSchemeAndHttpHost();
    $params['sender_name'] = $this->current_user->getAccountName();

    // Send emails.
    $users = $params['users'];
    $user_count = count($users);
    foreach ($users as $user) {
      $params['name_recipient'] = $user->name;
      $result = $this->mailer->mail($module, $key, $user->mail, $lang_code, $params, NULL, TRUE);
    }

    if($result['result'] === true){
      \Drupal::messenger()->addStatus($user_count .t(' user(s) notified successfully.'));
    } else {
      \Drupal::messenger()->addError(t('Unable to send emails, please contact administrator!'));
    }

  }

}
