<?php
$xpdo_meta_map['facilityResource']= array (
  'package' => 'migxfacilities',
  'version' => '1.0.0',
  'extends' => 'modResource',
  'fields' => 
  array (
  ),
  'fieldMeta' => 
  array (
  ),
  'composites' => 
  array (
    'Facility' => 
    array (
      'local' => 'id',
      'class' => 'mfFacility',
      'foreign' => 'resource_id',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
  ),
);
