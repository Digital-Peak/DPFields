<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

	// Needed if we want to search for the file. Let's keep it simple for now
	// JLoader::import('joomla.filesystem.path');

	JLoader::import('joomla.filesystem.file');

	$fields = $displayData['item']->dpfields ?: array();
	if (!empty($displayData['fieldlist']))
	{
		$fieldlist = $displayData['fieldlist'];

		foreach ($fieldlist as $field => $v)
		{
			if (!$v)
			{
				unset($fieldlist[$field]);
			}
		}
	}
	else
	{
		$fieldlist = $fields;
	}

	foreach ($fieldlist as $field => $v)
	{
		$field = (int) $field;
		$label = $fields[$field]->title;
		$value = $fields[$field]->value;

		// We do not show any empty fields
		if (! $value)
		{
			continue;
		}

		$class = '';
		if (isset($fields[$field]->class))
		{
			$class = $fields[$field]->class;
		}
		$id = $fields[$field]->id;

		//check if an individual override exists for this field

		$rawPath  = 'efield' . (int) $id . '.php';
		$fullpath = __FILE__;
		$fullpath = str_replace('.php', '' , $fullpath );
		//$fullpath = JPath::find($fullpath, $rawPath);
		$sub_exists = JFile::exists($fullpath.'/'.$rawPath);

		if ($sub_exists)
		{
			$basePath = JPATH_ROOT .'/components/com_dpfields/layouts';

			echo JLayoutHelper::render('field.erender.efield'.(int) $id, array('field' => $fields[$field]),
			$basePath, array(
			'component' => 'com_content',
			'client' => 0
		));
		}
		else
		{
	?>

	<dd class="dpfield-entry <?php echo $class;?>">
		<span class="dpfield-label"><?php echo htmlentities($label);?>: </span>
		<span class="dpfield-value"><?php echo $value;?></span>
	</dd>

  <?php
		}
	}
  ?>
