<?php

/**
 * @file
 * Contains \Drupal\custom\Controller\PageJsonController.
 */

namespace Drupal\custom\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\node\Entity\Node;

class PageJsonController extends ControllerBase {
  public function pageJson($siteapikey, $nid) {
    
	//get stored Site API Key from config
	$submitted_api_key = \Drupal::config('custom.config')->get('siteapikey');

	if(($submitted_api_key == $siteapikey) && is_numeric($nid)){
	  $node = Node::load($nid);
	  if (is_object($node)) {
	    $type = $node->getType();
	    if($type == 'page'){
	      $serializer = \Drupal::service('serializer');
	      $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
	      return new Response($data);
	    }
	  }
	  //set access denied if node type not page or node not found.
	  throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException(); 
	}else{
	  //set access denied if Site API Key not matched with the URL API key or nid is not valid
	  throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
	}

  }
}
