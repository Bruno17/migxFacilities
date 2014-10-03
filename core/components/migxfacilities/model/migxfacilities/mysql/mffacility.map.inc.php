<?php
$xpdo_meta_map['mfFacility']= array (
  'package' => 'migxfacilities',
  'version' => '1.0.0',
  'table' => 'mf_facilities',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'resource_id' => NULL,
    'facility_type' => NULL,
    'address' => NULL,
  ),
  'fieldMeta' => 
  array (
    'resource_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'attributes' => 'unsigned',
      'index' => 'index',
    ),
    'facility_type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => true,
    ),
    'address' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
  'composites' => 
  array (
    'TennisCourt' => 
    array (
      'class' => 'mfTennisCourt',
      'local' => 'id',
      'foreign' => 'facility_id',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
    'SwimmingPool' => 
    array (
      'class' => 'mfSwimmingPool',
      'local' => 'id',
      'foreign' => 'facility_id',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'Resource' => 
    array (
      'class' => 'modResource',
      'local' => 'resource_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
