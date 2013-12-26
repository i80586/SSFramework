<?php

/**
 * User Model
 * @author Rasim
 */
class User
{
	/**
	 * Collection name
	 * @var type 
	 */
	private $_collectionName = 'users';
	
	/**
	 * Create new model
	 * @staticvar null $instance
	 * @return \self
	 */
	public static function model()
	{
		static $instance = null;
		
		if(null === $instance) {
			$instance = new self;
		}
		
		return $instance; 
	}
	
	/**
	 * Insert new user to collection
	 * @param array $data
	 */
	public function createUser(array $data)
	{
		$collection = Application::mongoWrapper()->getCollection($this->_collectionName);
		$result = iterator_to_array($collection->find(array('id' => $data['id'])));
		
		if (empty($result)) {
			$collection->insert($data);
		}
	}
	
}