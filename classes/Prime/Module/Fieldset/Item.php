<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Prime Module Fieldset List
 *
 * @author Birkir Gudjonsson (birkir.gudjonsson@gmail.com)
 * @package Prime/Module/Fieldset
 * @category Prime
 * @copyright (c) 2013 SOLID Productions
 */
class Prime_Module_Fieldset_Item extends Prime_Module {

	/**
	 * Parameters to configure module
	 * 
	 * @return array
	 */
	public function params()
	{
		return array
		(
			'fieldset_item' => array
			(
				'name'  => 'Fieldset Item',
				'group' => 'General',
				'field' => 'Prime_Field_Fieldset',
				'properties' => array(
					'type' => 'item'
				)
			),
			'template' => array
			(
				'name'    => 'Template',
				'group'   => 'Layout',
				'field'   => 'Prime_Field_Template',
				'properties' => array(
					'scope' => 'module/fieldset/item'
				),
				'default' => 'default'
			)
		);
	}

	public function route($uri = NULL)
	{
		// load uri
		$uri = explode('/', $uri);

		// check if first part is item
		if ($uri[0] === 'item')
		{
			// check fieldset item existance
			return ORM::factory('Prime_Module_Fieldset_Item', intval($uri[1]))->loaded();
		}

		return FALSE;
	}

	/**
	 * Render module contents
	 * 
	 * @return View
	 */
	public function render()
	{
		// set default template if template not found.
		if ( ! Kohana::find_file('views/module/fieldset/item', $this->settings['template']))
		{
			$this->settings['template'] = 'default';
		}

		// check if view exists
		if ( ! Kohana::find_file('views/module/fieldset/item', $this->settings['template']))
		{
			// throw error
			Kohana::$log->add(Log::ERROR, 'Could not locate template for module [:template]', array(
				':template' => $this->settings['template']
			));

			return View::factory('Prime/Region/Error')
			->set('message', 'Could not locate template '.$this->settings['template']);
		}

		// setup view
		$view = View::factory('module/fieldset/item/'.$this->settings['template'])
		->bind('fieldset', $fieldset);

		// load fieldset item
		$fieldset = ORM::factory('Prime_Module_Fieldset', $this->settings['fieldset_item']);

		// if fieldset was not loaded
		if ( ! $fieldset->loaded() OR $fieldset->type === 'category')
		{
			// return error view
			return View::factory('Prime/Region/Error')->set('message', 'Could not find fieldset.');
		}

		// output view
		return $view;
	}

} // End Prime Module Fieldset Insert Class