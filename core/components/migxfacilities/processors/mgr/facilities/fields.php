<?php

//defined formnames related to class-alias, create a table to define them?
$formnames = array();
$formnames['SwimmingPool'] = 'mf_swimmingpools';
$formnames['TennisCourt'] = 'mf_tenniscourts';

$form_record = $modx->fromJson($modx->getOption('record_json',$scriptProperties,''));

$configs = $modx->getOption('MIGX_formname',$form_record,'');
if (!empty($configs)){
    $modx->migx->loadConfigs( true, true, array('configs'=>$configs));
}

$config = $modx->migx->customconfigs;
$prefix = isset($config['prefix']) && !empty($config['prefix']) ? $config['prefix'] : null;
$object_id = $modx->getOption('object_id',$scriptProperties,'new');

if (isset($config['use_custom_prefix']) && !empty($config['use_custom_prefix'])) {
    $prefix = isset($config['prefix']) ? $config['prefix'] : '';
}
$packageName = $config['packageName'];
$sender = 'default/fields';

$packagepath = $modx->getOption('core_path') . 'components/' . $packageName . '/';
$modelpath = $packagepath . 'model/';
if (is_dir($modelpath)){
    $modx->addPackage($packageName, $modelpath, $prefix);
}
$classname = $config['classname'];

$joinalias = isset($config['join_alias']) ? $config['join_alias'] : '';

$joins = isset($config['joins']) && !empty($config['joins']) ? $modx->fromJson($config['joins']) : false;

if (!empty($joinalias)) {
    if ($fkMeta = $modx->getFKDefinition($classname, $joinalias)) {
        $joinclass = $fkMeta['class'];
    } else {
        $joinalias = '';
    }
}

if ($this->modx->lexicon) {
    $this->modx->lexicon->load($packageName . ':default');
}

if (empty($scriptProperties['object_id']) || $scriptProperties['object_id'] == 'new') {
    if ($object = $modx->newObject($classname)){
        $object->set('object_id', 'new');
    }
    
} else {
    $c = $modx->newQuery($classname, $scriptProperties['object_id']);
    $pk = $modx->getPK($classname);
    $c->select('
        `' . $classname . '`.*,
    	`' . $classname . '`.`' . $pk . '` AS `object_id`
    ');
    if (!empty($joinalias)) {
        $c->leftjoin($joinclass, $joinalias);
        $c->select($modx->getSelectColumns($joinclass, $joinalias, 'Joined_'));
    }
    if ($joins) {
        $modx->migx->prepareJoins($classname, $joins, $c);
    }
    if ($object = $modx->getObject($classname, $c)){
        $object_id = $object->get('id');
    }
}

$_SESSION['migxWorkingObjectid'] = $object_id;

//handle json fields
if ($object){
    $record = $object->toArray();
    
    $record['MIGX_formname'] = $modx->getOption($object->get('Facility_facility_type'),$formnames,'');
    
}
else{
    $record = array();
}


foreach ($record as $field => $fieldvalue) {
    if (!empty($fieldvalue) && is_array($fieldvalue)) {
        foreach ($fieldvalue as $key => $value) {
            $record[$field . '.' . $key] = $value;
        }
    }
}
