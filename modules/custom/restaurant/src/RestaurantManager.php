<?php

namespace Drupal\restaurant;

use Drupal\paragraphs\Entity\Paragraph;
use Drupal\webform\Entity\Webform;
use Drupal\webform\Entity\WebformSubmission;
use Drupal\user\Entity\User;

class RestaurantManager {
    
  /**
   * addTimeSlot
   *
   * @param  mixed $data
   * @return void
   */
  public function addTimeSlot($data) {
    $response = [
      'status' => 'error'
    ];
    if ($data) {
      $start = $data['start'];
      $end = $data['end'];
      $title = $data['title'];
      if ($start && $end && $title) {
        $date = $start / 1000;
        $start = $start / 1000;
        $end = $end / 1000;
        $paragraph = Paragraph::create([
          'type' => 'time_slot',
          'field_date' => $date,
          'field_start' => $start,
          'field_end' => $end,
          'field_title' => $title
        ]);
        $paragraph->save();
        $response = [
          'status' => 'success'
        ];
      }
    }
    return $response;
  }

  
  /**
   * sendEmailNotifications
   *
   * @param  mixed $webform_submission
   * @return void
   */
  public function sendEmailNotifications($webform_submission) {
    if ($webform_submission && $webform_submission->getWebform()->id() == 'restaurant_booking') {
      $data = $webform_submission->getData();
      if ($data) {

        $restaurant = ucwords($data['restaurant_name']);
        $name = ucwords($data['first_name']) . ' ' . ucwords($data['last_name']);
        $admin = User::load(1);
        $adminMail = $admin->getEmail();

        // Send mail to user.
        $subject = 'Reservation placed on ' . $restaurant . ' at ' . $data['time_slot'];
        $body = 'Hi ' . $name . ',<br>';
        $body .= ' Your have booked a reservation on ' . $restaurant . ' at ' . $data['time_slot'];
        $to = $data['email'];
        $mailManager = \Drupal::service('plugin.manager.mail');
        $module = 'restaurant';
        $params['message'] = [
          'subject' => $subject,
          'body' => $body,
        ];
        $langcode = \Drupal::currentUser()->getPreferredLangcode();
        $send = true;
        $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

        // Send mail to admin.
        $subject = $name . ' placed a reservation on ' . $restaurant . ' at ' . $data['time_slot'];
        $body = 'Hi ' . ucwords($admin->getUsername()) . ',<br>';
        $body .= $name . ' has booked a reservation on ' . $restaurant . ' at ' . $data['time_slot'];
        $body .= ' from ' . $data['email'];
        $to = $adminMail;
        $mailManager = \Drupal::service('plugin.manager.mail');
        $module = 'restaurant';
        $params['message'] = [
          'subject' => $subject,
          'body' => $body,
        ];
        $langcode = \Drupal::currentUser()->getPreferredLangcode();
        $send = true;
        $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
      }
    }
  }
}