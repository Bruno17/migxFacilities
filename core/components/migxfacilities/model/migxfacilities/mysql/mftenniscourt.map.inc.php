<?php
$xpdo_meta_map['mfTennisCourt']= array (
  'package' => 'migxfacilities',
  'version' => '1.0.0',
  'table' => 'mf_tennis_courts',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'facility_id' => NULL,
    'number_of_courts' => NULL,
    'surface_type' => NULL,
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
    'number_of_courts' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'surface_type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
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
