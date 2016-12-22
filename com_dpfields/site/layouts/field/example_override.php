//Code example to be used in override views

		<?php
		$basePath = JPATH_ROOT .'/components/com_dpfields/layouts';

		// Fields to output, false = no output, empty array = all fields
		$fields = array
		(
			'87.my-field-87' => true,
			'1.my-field-1' => false,
			'5,my-field-2' => true
		);

		echo JLayoutHelper::render('field.erender', array('item' => $this->item, 'fieldlist' => $fields),
		$basePath, array(
		'component' => 'com_content',
		'client' => 0
		));
		?>
	
