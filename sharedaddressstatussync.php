<?php

require_once 'sharedaddressstatussync.civix.php';

function sharedaddressstatussync_civicrm_custom( $op, $groupID, $entityID, &$params ) {
  //if a master address' location type is changed to "Bad Address", set the shared address' location type to same.
  if ($op == 'edit') {
    //Custom Group ID for Address Status is 5.
    if($groupID == 5) {
			$status = $params[0]['value'];
      //find any addresses this address is a master address for.
      $params = array(
        'version' => 3,
        'sequential' => 1,
        'master_id' => $entityID,
        'return' => 'id',
      );
      $result = civicrm_api('Address', 'get', $params);

      //then change each of their statuses to match the master address.
      foreach($result['values'] as $address){
        $params = array(
          'version' => 3,
          'sequential' => 1,
          'id' => $address['id'],
          'custom_18' => $status,
        );
        $result = civicrm_api('Address', 'create', $params);
      }
    }
  }
}
