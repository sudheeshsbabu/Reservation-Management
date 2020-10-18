<?php

namespace Drupal\restaurant;

use Drupal\paragraphs\Entity\Paragraph;

class RestaurantManager {
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
}
