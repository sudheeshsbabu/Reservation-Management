<?php

namespace Drupal\restaurant\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\NodeInterface;
use Drupal\Core\Render\Markup;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\taxonomy\Entity\Term;
use Drupal\Component\Utility\Html;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Defines RestaurantController class.
 */
class RestaurantController extends ControllerBase {
	
	/**
	 * addTimeSlot
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function addTimeSlot(Request $request) {
		$data = $request->request->get('data');
		$response = \Drupal::service('restaurant.manager')->addTimeSlot($data);
		return new JsonResponse( $response );
	}
	
	/**
	 * test
	 *
	 * @return void
	 */
	public function test() {
		$node = Node::load(2);
		$timeslots = $node->field_time_slots->getValue();
		foreach ($timeslots as $item) {
			$p = Paragraph::load($item['target_id']);
			kint($p);
		}
		// $p = Paragraph::load(3);
		// kint($p);
		// $p = Paragraph::load(4);
		// kint($p);
		// $pids = \Drupal::entityTypeManager()
		// ->getStorage('paragraph')
		// ->getQuery()
		// ->condition('type', 'time_slot')
		// // ->notExists('parent_id')
		// ->condition('parent_id', '', '<>')
		// ->execute();
		// kint($pids);
		die;
	}
}