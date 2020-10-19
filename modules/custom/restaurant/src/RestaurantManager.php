<?php

namespace Drupal\restaurant;

use Drupal\paragraphs\Entity\Paragraph;
use Drupal\webform\Entity\Webform;
use Drupal\webform\Entity\WebformSubmission;
use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;

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
   * removeTimeSlot
   *
   * @param  mixed $data
   * @return void
   */
  public function removeTimeSlot($data) {
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
        $paragraphs = \Drupal::entityTypeManager()
        ->getStorage('paragraph')
        ->loadByProperties([
          'type' => 'time_slot',
          'field_date' => $date,
          'field_start' => $start,
          'field_end' => $end,
          'field_title' => $title
        ]);
        if ($paragraphs) {
          $paragraph = reset($paragraphs);
          $paragraph->delete();
          $response = [
            'status' => 'success'
          ];
        }
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
        $body .= ' You have booked a reservation on ' . $restaurant . ' at ' . $data['time_slot'];
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
  
  /**
   * createCalendarPage
   *
   * @return void
   */
  public function createCalendarPage() {
    $node = Node::load(1);
    if (empty($node)) {
      $body = '<h1>Week Calendar Demo</h1>';
      $body .= '<p class="description">Click on date cells to add a time slot. ';
      $body .= 'Click on timeslot to remove it. When you finish adding time ';
      $body .= 'slots go back to node add/edit form and save the modifications.</p>';
      $body .= '<div id="calendar">&nbsp;</div>';
      $properties = [
        'type' => 'page',
        'title' => 'Calendar',
        'body' => $body
      ];
      $calendar = Node::create($properties);
      $calendar->body->format = 'full_html';
      $calendar->save();
    }
  }
}