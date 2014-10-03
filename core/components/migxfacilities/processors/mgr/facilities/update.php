<?php

/**
 * XdbEdit
 *
 * Copyright 2010 by Bruno Perner <b.perner@gmx.de>
 *
 * This file is part of XdbEdit, for editing custom-tables in MODx Revolution CMP.
 *
 * XdbEdit is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * XdbEdit is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * XdbEdit; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA 
 *
 * @package xdbedit
 */
/**
 * Update and Create-processor for xdbedit
 *
 * @package xdbedit
 * @subpackage processors
 */
//if (!$modx->hasPermission('quip.thread_view')) return $modx->error->failure($modx->lexicon('access_denied'));


if (empty($scriptProperties['object_id'])) {
    $updateerror = true;
    $errormsg = $modx->lexicon('quip.thread_err_ns');
    return;
}

$config = $modx->migx->customconfigs;

$includeTVList = $modx->getOption('includeTVList', $config, '');
$includeTVList = !empty($includeTVList) ? explode(',', $includeTVList) : array();
$includeTVs = $modx->getOption('includeTVs', $config, false);
$classname = 'modResource';

//$saveTVs = false;
/*
if ($modx->lexicon) {
$modx->lexicon->load($packageName . ':default');
}
*/
if (isset($scriptProperties['data'])) {
    //$scriptProperties = array_merge($scriptProperties, $modx->fromJson($scriptProperties['data']));
    $data = $modx->fromJson($scriptProperties['data']);
}

$data['id'] = $modx->getOption('object_id', $scriptProperties, null);

$parent = $modx->getOption('resource_id', $scriptProperties, false);
$checkresponse = true;
$handlerelated = true;

$task = $modx->getOption('task', $scriptProperties, '');

switch ($task) {
    case 'publish':
        $response = $modx->runProcessor('resource/publish', $data);
        $handlerelated = false;
        break;
    case 'unpublish':
        $response = $modx->runProcessor('resource/unpublish', $data);
        $handlerelated = false;
        break;
    case 'delete':
        $response = $modx->runProcessor('resource/delete', $data);
        $handlerelated = false;
        break;
    case 'recall':
        $object = $modx->getObject($classname, $scriptProperties['object_id']);
        $object->set('deleted', '0');
        $object->save();
        $checkresponse = false;
        $handlerelated = false;
        break;

    default:

        //$modx->migx->loadConfigs();
        //$tabs = $modx->migx->getTabs();

        $data['context_key'] = $modx->getOption('context_key', $data, $scriptProperties['wctx']);
        if ($includeTVs) {
            $c = $modx->newQuery('modTemplateVar');
            $collection = $modx->getCollection('modTemplateVar', $c);
            foreach ($collection as $tv) {
                $tvname = $tv->get('name');
                if (isset($data[$tvname])) {
                    $value = $data[$tvname];
                    $data['tv' . $tv->get('id')] = $value;
                    unset($data[$tvname]);
                }
            }

            $data['tvs'] = 1;
        }

        $data['class_key'] = 'facilityResource';
        $data['context_key'] = 'web';

        if ($scriptProperties['object_id'] == 'new') {
            //$object = $modx->newObject($classname);
            if (!empty($parent)) {
                $data['parent'] = $parent;
            }
            $response = $modx->runProcessor('resource/create', $data);
        } else {
            //$object = $modx->getObject($classname, $scriptProperties['object_id']);
            //if (empty($object)) return $modx->error->failure($modx->lexicon('quip.thread_err_nf'));
            $response = $modx->runProcessor('resource/update', $data);
        }
}

if ($checkresponse) {
    if ($response->isError()) {
        $updateerror = true;
        $errormsg = $response->getMessage();
    }
    $object = $response->getObject();

    if ($handlerelated) {
        $resource_id = $modx->getOption('id', $object);

        $facility_type = $modx->getOption('facility_type', $data);
        $classname = 'mfFacility';
        $classalias = 'Facility';
        $subclassname = 'mf' . $facility_type;
        if ($fac_object = $modx->getObject($classname, array('resource_id' => $resource_id))) {
            $facility_id = $fac_object->get('id');
            $old_facility_type = $fac_object->get('facility_type');
            if ($old_facility_type != $facility_type) {
                //facility-type has changed, remove sub-object
                $old_subclassname = 'mf' . $old_facility_type;
                if ($fac_subobject = $modx->getObject($old_subclassname, array('facility_id' => $facility_id))) {
                    $fac_subobject->remove();
                }
            }

        } else {
            $fac_object = $modx->newObject($classname);
            $fac_object->set('resource_id', $resource_id);
        }

        $fac_object->set('facility_type', $facility_type);
        $len = strlen($classalias) + 1;
        foreach ($data as $key => $value) {
            if (substr($key, 0, $len) == $classalias . '_') {
                $field = substr($key, $len);
                $fac_object->set($field, $value);
            }
        }
        $fac_object->save();

        $facility_id = $fac_object->get('id');


        if ($fac_subobject = $modx->getObject($subclassname, array('facility_id' => $facility_id))) {

        } else {
            $fac_subobject = $modx->newObject($subclassname);
            $fac_subobject->set('facility_id', $facility_id);
        }

        $len = strlen($facility_type) + 1;
        foreach ($data as $key => $value) {
            if (substr($key, 0, $len) == $facility_type . '_') {
                $field = substr($key, $len);
                $fac_subobject->set($field, $value);
            }
        }
        $fac_subobject->save();
    }


}
