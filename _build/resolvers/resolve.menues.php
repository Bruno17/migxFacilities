<?php

$modx = &$object->xpdo;
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:

            $modx->getVersionData();
            if (version_compare($modx->version['full_version'], '2.3', '>=')) {

                $menues = $modx->fromJson('[{"MIGX_id":"1","text":"Facilities","parent":"","description":"","icon":"","menuindex":"","params":"&configs=mf_facilities","handler":"","permissions":"","action.id":"","action.namespace":"","action.controller":"","action.haslayout":"0","action.lang_topics":"","action.assets":""}]');

                if (is_array($menues) && count($menues) > 0) {
                    $modx->log(modX::LOG_LEVEL_INFO, 'Prepare menu for MODX Revolution 2.3.x');

                    foreach ($menues as $props) {
                        $text = !empty($props['text']) ? $props['text'] : '';
                        if ($object = $modx->getObject('modMenu', array('text' => $text))) {
                            $parent = $object->get('parent');
                            if (empty($parent)) {
                                $object->set('parent', 'topnav');
                            }
                            $object->set('action', 'index');
                            $object->set('namespace', !empty($props['action.namespace']) ? $props['action.namespace'] : 'migx');
                            $object->save();

                            if ($action = $object->getOne('Action')) {
                                //$action->remove();
                            }
                        }
                    }
                }


            } else {

            }

            break;
    }

}
return true;
