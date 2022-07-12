<?php

namespace Drupal\devjobs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;

/**
 * Jobs controller.
 */
class JobsController extends ControllerBase {

  public function content() {

    $filter_by_title = \Drupal::request()->query->get('filter-by-title')??'';
    $filter_by_location = \Drupal::request()->query->get('filter-by-location')??'';
    $full_time = \Drupal::request()->query->get('full-time')??'';

    $query = \Drupal::entityQuery('node')
        ->condition('type', 'job')
        ->condition('field_location', $filter_by_location, 'CONTAINS');
    if ($full_time==='on') {
      $query = $query->condition('field_schedule', 'Full time');
    }
        
    $group = $query->orConditionGroup()
      ->condition('title', $filter_by_title, 'CONTAINS')
      ->condition('field_company', $filter_by_title, 'CONTAINS');
    $nids = $query->condition($group)
      ->execute();

    $style = ImageStyle::load('logo');

    $jobs = [];
    foreach ($nids as $nid) {
      $node = Node::load($nid);
      $fid = $node->field_logo->getValue()[0]['target_id']??null;
      $url = null;
      if ($fid) {
        $file = File::load($fid);
        $uri = $file->getFileUri();
        $url = $style->buildUrl($uri);
      }
      $jobs[$nid] = [
        'id' => $nid,
        'title' => $node->getTitle(),
        'company' => $node->field_company->getValue()[0]['value'],
        'body' => $node->body->getValue()[0]['value'],
        'schedule' => $node->field_schedule->getValue()[0]['value'],
        'location' => $node->field_location->getValue()[0]['value'],
        'logo' => $url,
        'created' => $node->created->getValue()[0]['value'],
      ];
    }

    return [
      // Theme hook name.
      '#theme' => 'devjobs_theme_hook',
      // Variables.
      '#jobs' => $jobs,
    ];
  }

}
