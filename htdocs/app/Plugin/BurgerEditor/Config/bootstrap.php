<?php
App::uses('BurgerEditorUtil', 'BurgerEditor.Lib');
App::uses('BurgerEditorAssetDispatcher', 'BurgerEditor.Routing/Filter');
$filters = Configure::read('Dispatcher.filters');
Configure::write('Dispatcher.filters', array_merge(array('BurgerEditor.BurgerEditorAssetDispatcher'), $filters));
