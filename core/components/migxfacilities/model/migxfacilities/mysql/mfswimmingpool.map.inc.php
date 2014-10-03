<?php
$xpdo_meta_map['mfSwimmingPool']= array (
  'package' => 'migxfacilities',
  'version' => '1.0.0',
  'table' => 'mf_swimming_pools',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'facility_id' => NULL,
    'pool_length' => NULL,
    'indoor_pool' => 0,
  ),
  'fieldMeta' => 
  array (
    'facility_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'attributes' => 'unsigned',
      'index' => 'index',
    ),
    'pool_length' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'indoor_pool' => 
    array (
      'dbtype' => 'int',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'aggregates' => 
  array (
    'Facility' => 
    array (
      'class' => 'mfFacility',
      'local' => 'facility_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
