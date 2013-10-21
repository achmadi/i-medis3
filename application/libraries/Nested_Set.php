<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nested_Set {

	/**
	 * Name of the database table to model.
	 *
	 * @var    string
	 */
	protected $table_name = '';

	/**
	 * Name of the primary key field in the table.
	 *
	 * @var    string
	 */
	protected $table_key = '';

	/**
	 * Object property holding the primary key of the parent node.  Provides
	 * adjacency list data for nodes.
	 *
	 * @var    integer
	 */
	public $parent_id;

	/**
	 * Object property holding the depth level of the node in the tree.
	 *
	 * @var    integer
	 */
	public $level;

	/**
	 * Object property holding the left value of the node for managing its
	 * placement in the nested sets tree.
	 *
	 * @var    integer
	 */
	public $lft;

	/**
	 * Object property holding the right value of the node for managing its
	 * placement in the nested sets tree.
	 *
	 * @var    integer
	 * @since  11.1
	 */
	public $rgt;
	
	/**
	 * Object property to hold the location type to use when storing the row.
	 * Possible values are: ['before', 'after', 'first-child', 'last-child'].
	 *
	 * @var    string
	 */
	protected $_location;
	
	/**
	 * Object property to hold the primary key of the location reference node to
	 * use when storing the row.  A combination of location type and reference
	 * node describes where to store the current node in the tree.
	 *
	 * @var    integer
	 */
	protected $_location_id;
	
	/**
	 * Constructor
	 *
	 * @access      public
	 */
	public function __construct()   {
		$CI =& get_instance(); // to access CI resources, use $CI instead of $this
		$this->db =& $CI->db;
	}
	
	public function getKey() {
		return $this->{$this->table_key};
	}
	
	public function getLevel() {
		return $this->level;
	}
	
	public function getLeft() {
		return $this->lft;
	}
	
	public function getRight() {
		return $this->rgt;
	}
	
	/**
	 * On initialising the instance, this method should be called to set the
	 * database table name that we're dealing and also to identify the names
	 * of the left and right value columns used to form the tree structure.
	 * Typically, this would be done automatically by the model class that
	 * extends this "base" class (eg. a Categories class would set the table_name
	 * to "categories", a Site_structure class would set the table_name to
	 * "pages" etc)
	 *
	 * @param string $table_name The name of the db table to use
	 * @param string $table_key The name of the primary identifier field
	 * @param string $lft The name of the field representing the left identifier
	 * @param string $rgt The name of the field representing the right identifier
	 * @param string $parent_id The name of the parent column field
	 * @param string $level The name of the level column field
	 */
	public function setControlParams($table_name, $table_key = 'id') {
		$this->table_name = $table_name;
		$this->table_key = $table_key;
        $this->{$this->table_key} = 0;
	}
	
	public function setKeyValue($value) {
        $this->{$this->table_key} = $value;
	}
	
	/**
	 * Method to get an array of nodes from a given node to its root.
	 *
	 * @param   integer  $pk          Primary key of the node for which to get the path.
	 * @param   boolean  $diagnostic  Only select diagnostic data for the nested sets.
	 *
	 * @return  mixed    Boolean false on failure or array of node objects on success.
	 */
	public function getPath($pk = null, $diagnostic = false) {
		// Initialise variables.
		$k = $this->table_key;
		$pk = (is_null($pk)) ? $this->$k : $pk;

		// Get the path from the node to the root.
		$select = ($diagnostic) ? 'p.'.$k.', p.parent_id, p.level, p.lft, p.rgt' : 'p.*';
		$this->db->select($select);
		$this->db->where('n.lft BETWEEN p.lft AND p.rgt');
		$this->db->where('n.'.$k.' = '.(int) $pk);
		$path = $this->db->get($this->table_name.' AS n, '.$this->table_name.' AS p')->result();

		return $path;
	}
	
	/**
	 * Method to get a node and all its child nodes.
	 *
	 * @param   integer  $pk          Primary key of the node for which to get the tree.
	 * @param   boolean  $diagnostic  Only select diagnostic data for the nested sets.
	 *
	 * @return  mixed    Boolean false on failure or array of node objects on success.
	 */
	public function getTree($pk = null, $diagnostic = false) {
		// Initialise variables.
		$k = $this->table_key;
		$pk = (is_null($pk)) ? $this->$k : $pk;

		// Get the node and children as a tree.
		$select = ($diagnostic) ? 'n.' . $k . ', n.parent_id, n.level, n.lft, n.rgt' : 'n.*';
		$this->db->select($select);
		$this->db->where('n.lft BETWEEN p.lft AND p.rgt');
		$this->db->where('p.'.$k.' = '.(int) $pk);
		$this->db->order_by('n.lft');
		$tree = $this->db->get($this->table_name.' AS n, '.$this->table_name.' AS p')->result();

		return $tree;
	}
	
	/**
	 * Method to set the location of a node in the tree object.  This method does not
	 * save the new location to the database, but will set it in the object so
	 * that when the node is stored it will be stored in the new location.
	 *
	 * @param   integer  $referenceId  The primary key of the node to reference new location by.
	 * @param   string   $position     Location type string. ['before', 'after', 'first-child', 'last-child']
	 *
	 * @return  boolean  True on success.
	 */
	public function setLocation($referenceId, $position = 'after') {
		// Make sure the location is valid.
		if (($position != 'before') && ($position != 'after') && ($position != 'first-child') && ($position != 'last-child')) {
			/*
			$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_INVALID_LOCATION', get_class($this)));
			$this->setError($e);
			*/
			return false;
		}

		// Set the location properties.
		$this->_location = $position;
		$this->_location_id = $referenceId;

		return true;
	}
	
	/**
	 * Method to move a node and its children to a new location in the tree.
	 *
	 * @param   integer  $referenceId  The primary key of the node to reference new location by.
	 * @param   string   $position     Location type string. ['before', 'after', 'first-child', 'last-child']
	 * @param   integer  $pk           The primary key of the node to move.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link    http://docs.joomla.org/JTableNested/moveByReference
	 * @since   11.1
	 */

	public function moveByReference($referenceId, $position = 'after', $pk = null) {
		/*
		if ($this->_debug) {
			echo "\nMoving ReferenceId:$referenceId, Position:$position, PK:$pk";
		}
		*/

		// Initialise variables.
		$k = $this->table_key;
		$pk = (is_null($pk)) ? $this->$k : $pk;

		// Get the node by id.
		if (!$node = $this->_getNode($pk)) {
			// Error message set in getNode method.
			return FALSE;
		}

		// Get the ids of child nodes.
		$this->db->select($k);
		$this->db->where('lft BETWEEN '.(int) $node->lft.' AND '.(int) $node->rgt);
		$result = $this->db->get($this->table_name)->result();
        $children = array();
        foreach ($result as $row)
            $children[] = $row->id;
		
		/*
		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_MOVE_FAILED', get_class($this), $this->_db->getErrorMsg()));
			$this->setError($e);
			return false;
		}
		if ($this->_debug) {
			$this->_logtable(false);
		}
		*/

		// Cannot move the node to be a child of itself.
		if (in_array($referenceId, $children)) {
			/*
			$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_INVALID_NODE_RECURSION', get_class($this)));
			$this->setError($e);
			*/
			return FALSE;
		}
		
		/*
		// Lock the table for writing.
		if (!$this->_lock()) {
			return false;
		}
		*/

		/*
		 * Move the sub-tree out of the nested sets by negating its left and right values.
		 */
		$this->db->set('lft', 'lft * (-1)', FALSE);
		$this->db->set('rgt', 'rgt * (-1)', FALSE);
		$this->db->where('lft BETWEEN '.(int) $node->lft.' AND '.(int) $node->rgt);
		$this->db->update($this->table_name);

		/*
		 * Close the hole in the tree that was opened by removing the sub-tree from the nested sets.
		 */
		// Compress the left values.
		$this->db->set('lft', 'lft - '.(int) $node->width, FALSE);
		$this->db->where('lft > '.(int) $node->rgt);
		$this->db->update($this->table_name);

		// Compress the right values.
		$this->db->set('rgt', 'rgt - '.(int) $node->width, FALSE);
		$this->db->where('rgt > '.(int) $node->rgt);
		$this->db->update($this->table_name);

		// We are moving the tree relative to a reference node.
		if ($referenceId) {
			// Get the reference node by primary key.
			if (!$reference = $this->_getNode($referenceId)) {
				/*
				// Error message set in getNode method.
				$this->_unlock();
				*/
				return FALSE;
			}

			// Get the reposition data for shifting the tree and re-inserting the node.
			if (!$repositionData = $this->_getTreeRepositionData($reference, $node->width, $position)) {
				/*
				// Error message set in getNode method.
				$this->_unlock();
				*/
				return FALSE;
			}
		}
		// We are moving the tree to be the last child of the root node
		else {
			// Get the last root node as the reference node.
			$this->db->select($this->table_key.', parent_id, level, lft, rgt');
			$this->db->where('parent_id', 0);
			$this->db->order_by('lft', 'DESC');
			$reference = $this->db->get($this->table_name)->row();
			
			if (!$reference) {
				return FALSE;
			}
			
			/*
			// Check for a database error.
			if ($this->_db->getErrorNum())
			{
				$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_MOVE_FAILED', get_class($this), $this->_db->getErrorMsg()));
				$this->setError($e);
				$this->_unlock();
				return false;
			}
			if ($this->_debug)
			{
				$this->_logtable(false);
			}
			*/

			// Get the reposition data for re-inserting the node after the found root.
			if (!$repositionData = $this->_getTreeRepositionData($reference, $node->width, 'last-child')) {
				/*
				// Error message set in getNode method.
				$this->_unlock();
				*/
				return FALSE;
			}
		}
		
		/*
		 * Create space in the nested sets at the new location for the moved sub-tree.
		 */
		// Shift left values.
		$this->db->set('lft', 'lft + '.(int) $node->width, FALSE);
		$this->db->where($repositionData->left_where);
		$this->db->update($this->table_name);

		// Shift right values.
		$this->db->set('rgt', 'rgt + '.(int) $node->width, FALSE);
		$this->db->where($repositionData->right_where);
		$this->db->update($this->table_name);

		/*
		 * Calculate the offset between where the node used to be in the tree and
		 * where it needs to be in the tree for left ids (also works for right ids).
		 */
		$offset = $repositionData->new_lft - $node->lft;
		$levelOffset = $repositionData->new_level - $node->level;

		// Move the nodes back into position in the tree using the calculated offsets.
		$this->db->set('rgt', (int) $offset.' - rgt', FALSE);
		$this->db->set('lft', (int) $offset.' - lft', FALSE);
		$this->db->set('level', 'level + '.(int) $levelOffset, FALSE);
		$this->db->where('lft < 0');
		$this->db->update($this->table_name);

		// Set the correct parent id for the moved node if required.
		if ($node->parent_id != $repositionData->new_parent_id) {
			$this->db->set('parent_id', (int) $repositionData->new_parent_id);
			$this->db->where($this->table_key.' = '.(int) $node->$k);
			$this->db->update($this->table_name);
		}
		
		/*
		// Unlock the table for writing.
		$this->_unlock();
		*/

		// Set the object values.
		$this->parent_id = $repositionData->new_parent_id;
		$this->level = $repositionData->new_level;
		$this->lft = $repositionData->new_lft;
		$this->rgt = $repositionData->new_rgt;

		return TRUE;
	}
	
	/**
	 * Method to delete a node and, optionally, its child nodes from the table.
	 *
	 * @param   integer  $pk        The primary key of the node to delete.
	 * @param   boolean  $children  True to delete child nodes, false to move them up a level.
	 *
	 * @return  boolean  True on success.
	 */
	public function delete($pk = null, $children = true) {
		// Initialise variables.
		$k = $this->table_key;
		$pk = (is_null($pk)) ? $this->$k : $pk;
		
		/*
		// Lock the table for writing.
		if (!$this->_lock())
		{
			// Error message set in lock method.
			return false;
		}
		*/
		/*
		// If tracking assets, remove the asset first.
		if ($this->_trackAssets)
		{
			$name = $this->_getAssetName();
			$asset = JTable::getInstance('Asset');

			// Lock the table for writing.
			if (!$asset->_lock())
			{
				// Error message set in lock method.
				return false;
			}

			if ($asset->loadByName($name))
			{
				// Delete the node in assets table.
				if (!$asset->delete(null, $children))
				{
					$this->setError($asset->getError());
					$asset->_unlock();
					return false;
				}
				$asset->_unlock();
			}
			else
			{
				$this->setError($asset->getError());
				$asset->_unlock();
				return false;
			}
		}
		*/
		// Get the node by id.
		if (!$node = $this->_getNode($pk)) {
			/*
			// Error message set in getNode method.
			$this->_unlock();
			*/
			return false;
		}

		// Should we delete all children along with the node?
		if ($children) {
			// Delete the node and all of its children.
			$this->db->where('lft BETWEEN '.(int) $node->lft.' AND '.(int) $node->rgt);
			$this->db->delete($this->table_name);

			// Compress the left values.
			$this->db->set('lft', 'lft - '.(int) $node->width, FALSE);
			$this->db->where('lft > '.(int) $node->rgt);
			$this->db->update($this->table_name);

			// Compress the right values.
			$this->db->set('rgt', 'rgt - '.(int) $node->width, FALSE);
			$this->db->where('rgt > '.(int) $node->rgt);
			$this->db->update($this->table_name);
		}
		// Leave the children and move them up a level.
		else {
			// Delete the node.
			$this->db->where('lft', (int) $node->lft);
			$this->db->delete($this->table_name);

			// Shift all node's children up a level.
			$this->db->set('lft', 'lft - 1', FALSE);
			$this->db->set('rgt', 'rgt - 1', FALSE);
			$this->db->set('level', 'level - 1', FALSE);
			$this->db->where('lft BETWEEN '.(int) $node->lft . ' AND ' . (int) $node->rgt);
			$this->db->update($this->table_name);

			// Adjust all the parent values for direct children of the deleted node.
			$this->db->set('parent_id', (int) $node->parent_id);
			$this->db->where('parent_id', (int) $node->$k);
			$this->db->update($this->table_name);

			// Shift all of the left values that are right of the node.
			$this->db->set('lft', 'lft - 2', FALSE);
			$this->db->where('lft > '.(int) $node->rgt);
			$this->db->update($this->table_name);

			// Shift all of the right values that are right of the node.
			$this->db->set('rgt', 'rgt - 2', FALSE);
			$this->db->where('rgt > '.(int) $node->rgt);
			$this->db->update($this->table_name);
			
		}
		
		/*
		// Unlock the table for writing.
		$this->_unlock();
		*/

		return true;
	}
	
	/**
	 * Method to store a node in the database table.
	 *
	 * @param   boolean  $updateNulls  True to update null values as well.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link    http://docs.joomla.org/JTableNested/store
	 * @since   11.1
	 */
	public function store($data) {
		// Initialise variables.
		$k = $this->table_key;
        $this->$k = $data[$k];
		
		/*
		if ($this->_debug) {
			echo "\n" . get_class($this) . "::store\n";
			$this->_logtable(true, false);
		}
		*/
		
		/*
		 * If the primary key is empty, then we assume we are inserting a new node into the
		 * tree.  From this point we would need to determine where in the tree to insert it.
		 */
		if (empty($data[$k])) {
			/*
			 * We are inserting a node somewhere in the tree with a known reference
			 * node.  We have to make room for the new node and set the left and right
			 * values before we insert the row.
			 */
			if ($this->_location_id >= 0) {
				// Lock the table for writing.
				/*
				if (!$this->_lock()) {
					// Error message set in lock method.
					return false;
				}
				*/

				// We are inserting a node relative to the last root node.
				if ($this->_location_id == 0) {
					// Get the last root node as the reference node.
					$this->db->select($this->table_key.', parent_id, level, lft, rgt');
					$this->db->where('parent_id', 0);
					$this->db->order_by('lft', 'DESC');
					$reference = $this->db->get($this->table_name)->row();
					
					// Check for a database error.
					/*
					if ($this->_db->getErrorNum())
					{
						$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_STORE_FAILED', get_class($this), $this->_db->getErrorMsg()));
						$this->setError($e);
						$this->_unlock();
						return false;
					}
					if ($this->_debug)
					{
						$this->_logtable(false);
					}
					*/
				}
				// We have a real node set as a location reference.
				else {
					// Get the reference node by primary key.
					if (!$reference = $this->_getNode($this->_location_id)) {
						/*
						// Error message set in getNode method.
						$this->_unlock();
						*/
						return false;
					}
				}

				// Get the reposition data for shifting the tree and re-inserting the node.
				if (!($repositionData = $this->_getTreeRepositionData($reference, 2, $this->_location))) {
					/*
					// Error message set in getNode method.
					$this->_unlock();
					*/
					return false;
				}

				// Create space in the tree at the new location for the new node in left ids.
				$this->db->set('lft', 'lft + 2', FALSE);
				$this->db->where($repositionData->left_where);
				$this->db->update($this->table_name);

				// Create space in the tree at the new location for the new node in right ids.
				$this->db->set('rgt', 'rgt + 2', FALSE);
				$this->db->where($repositionData->right_where);
				$this->db->update($this->table_name);

				// Set the object values.
				$this->parent_id = $repositionData->new_parent_id;
				$this->level = $repositionData->new_level;
				$this->lft = $repositionData->new_lft;
				$this->rgt = $repositionData->new_rgt;
			}
			else {
				// Negative parent ids are invalid
				/*
				$e = new JException(JText::_('JLIB_DATABASE_ERROR_INVALID_PARENT_ID'));
				$this->setError($e);
				*/
				return false;
			}
		}
		/*
		 * If we have a given primary key then we assume we are simply updating this
		 * node in the tree.  We should assess whether or not we are moving the node
		 * or just updating its data fields.
		 */
		else {
			// If the location has been set, move the node to its new location.
			if ($this->_location_id > 0) {
				if (!$this->moveByReference($this->_location_id, $this->_location, $this->$k)) {
					// Error message set in move method.
					return false;
				}
			}
			
			/*
			// Lock the table for writing.
			if (!$this->_lock())
			{
				// Error message set in lock method.
				return false;
			}
			*/
		}
		
		// Store the row to the database.
		$id = $data['id'];
		unset($data['id']);
		unset($data['old_parent_id']);
		if (isset($this->parent_id))
			$data['parent_id']	= $this->parent_id;
		else
			unset($data['parent_id']);
		if (isset($this->level))
			$data['level']		= $this->level;
		if (isset($this->lft))
			$data['lft']		= $this->lft;
		if (isset($this->rgt))
			$data['rgt']		= $this->rgt;
		
		if ($id > 0) {
			$this->db->where('id', $id);
			$result = $this->db->update($this->table_name, $data);
		}
		else {
			$result = $this->db->insert($this->table_name, $data);
			$id = $this->db->insert_id();
		}
		if (!$result) {
			//log_message('error', 'Node addition failed for ' . $leftval . ' - ' . $rightval);
			return false;
		}
		/*
		// Store the row to the database.
		if (!parent::store($updateNulls)) {
			$this->_unlock();
			return false;
		}
		*/
		/*
		if ($this->_debug) {
			$this->_logtable();
		}
		*/
		
		/*
		// Unlock the table for writing.
		$this->_unlock();
		*/

		return $id;
	}
	
	/**
	 * Method to move a node one position to the left in the same level.
	 *
	 * @param   integer  $pk  Primary key of the node to move.
	 *
	 * @return  boolean  True on success.
	 */
	public function orderUp($pk) {
		$this->db->trans_start();
		
		// Initialise variables.
		$k = $this->table_key;
		$pk = (is_null($pk)) ? $this->$k : $pk;

		// Get the node by primary key.
		if (!$node = $this->_getNode($pk)) {
			return false;
		}

		// Get the left sibling node.
		if (!$sibling = $this->_getNode($node->lft - 1, 'right')) {
			return false;
		}

		// Get the primary keys of child nodes.
		$this->db->select($this->table_key);
		$this->db->where('lft BETWEEN '.(int) $node->lft.' AND '.(int) $node->rgt, NULL, FALSE);
		$result = $this->db->get($this->table_name)->result();
		
		$children = array();
		foreach ($result as $row) {
			$children[] = $row->id;
		}
		//$test = implode(',', $children);

		// Shift left and right values for the node and it's children.
		$this->db->set('lft', 'lft - '.(int) $sibling->width, FALSE);
		$this->db->set('rgt', 'rgt - '.(int) $sibling->width, FALSE);
		$this->db->where('lft BETWEEN '.(int) $node->lft.' AND '.(int) $node->rgt, NULL, FALSE);
		$this->db->update($this->table_name);

		// Shift left and right values for the sibling and it's children.
		$this->db->set('lft', 'lft + '.(int) $node->width, FALSE);
		$this->db->set('rgt', 'rgt + '.(int) $node->width, FALSE);
		$this->db->where('lft BETWEEN '.(int) $sibling->lft.' AND '.(int) $sibling->rgt, NULL, FALSE);
		$this->db->where($this->table_key.' NOT IN ('.implode(',', $children).')', NULL, FALSE);
		$this->db->update($this->table_name);

		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Method to move a node one position to the right in the same level.
	 *
	 * @param   integer  $pk  Primary key of the node to move.
	 *
	 * @return  boolean  True on success.
	 */
	public function orderDown($pk) {
		$this->db->trans_start();
		
		// Initialise variables.
		$k = $this->table_key;
		$pk = (is_null($pk)) ? $this->$k : $pk;

		// Get the node by primary key.
		if (!$node = $this->_getNode($pk)) {
			return false;
		}

		// Get the right sibling node.
		if (!$sibling = $this->_getNode($node->rgt + 1, 'left')) {
			return false;
		}

		// Get the primary keys of child nodes.
		$this->db->select($this->table_key);
		$this->db->where('lft BETWEEN '.(int) $node->lft.' AND '.(int) $node->rgt, NULL, FALSE);
		$result = $this->db->get($this->table_name)->result();
		
		$children = array();
		foreach ($result as $row) {
			$children[] = $row->id;
		}

		// Shift left and right values for the node and it's children.
		$this->db->set('lft', 'lft + '.(int) $sibling->width, FALSE);
		$this->db->set('rgt', 'rgt + '.(int) $sibling->width, FALSE);
		$this->db->where('lft BETWEEN '.(int) $node->lft.' AND '.(int) $node->rgt, NULL, FALSE);
		$this->db->update($this->table_name);

		// Shift left and right values for the sibling and it's children.
		$this->db->set('lft', 'lft - '.(int) $node->width, FALSE);
		$this->db->set('rgt', 'rgt - '.(int) $node->width, FALSE);
		$this->db->where('lft BETWEEN '.(int) $sibling->lft.' AND '.(int) $sibling->rgt, NULL, FALSE);
		$this->db->where($this->table_key.' NOT IN ('.implode(',', $children).')', NULL, FALSE);
		$this->db->update($this->table_name);

		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * Gets the ID of the root item in the tree
	 *
	 * @return  mixed  The ID of the root row, or false and the internal error is set.
	 */
	public function getRootId() {
		// Get the root item.
		$k = $this->table_key;

		// Test for a unique record with parent_id = 0
		$this->db->select($k);
		$this->db->where('parent_id', 0);
		$result = $this->db->get($this->table_name)->row();
		
		/*
		if ($this->_db->getErrorNum()) {
			$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_GETROOTID_FAILED', get_class($this), $this->_db->getErrorMsg()));
			$this->setError($e);
			return false;
		}
		*/

		if (count($result) == 1) {
			$parentId = $result->id;
		}
		else {
			// Test for a unique record with lft = 0
			$this->db->select($k);
			$this->db->where('lft', 0);
			$result = $this->db->get($this->table_name)->row();
			/*
			if ($this->_db->getErrorNum()) {
				$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_GETROOTID_FAILED', get_class($this), $this->_db->getErrorMsg()));
				$this->setError($e);
				return false;
			}
			*/
			
			if (count($result) == 1) {
				$parentId = $result->id;
			}
			else {
				/*
				$e = new JException(JText::_('JLIB_DATABASE_ERROR_ROOT_NODE_NOT_FOUND'));
				$this->setError($e);
				*/
				return false;
			}
		}
		return $parentId;
	}
	
	/**
	 * Method to rebuild the node's path field from the alias values of the
	 * nodes from the current node to the root node of the tree.
	 *
	 * @param   integer  $pk  Primary key of the node for which to get the path.
	 *
	 * @return  boolean  True on success.
	 */
	public function rebuildPath($pk = null) {
		// If there is no alias or path field, just return true.

		// Initialise variables.
		$k = $this->table_key;
		$pk = (is_null($pk)) ? $this->$k : $pk;

		// Get the aliases for the path from the node to the root node.
		$this->db->select('p.'.$this->table_key.', p.jenis');
		$this->db->where('n.lft BETWEEN p.lft AND p.rgt');
		$this->db->where('n.'.$this->table_key.' = '.(int) $pk);
		$this->db->order_by('p.lft');
		$segments = $this->db->get($this->table_name.' AS n, '.$this->table_name.' AS p')->result();
		
		// Make sure to remove the root path if it exists in the list.
		if (intval($segments[0]->jenis) == "Root") {
			array_shift($segments);
		}
		
		$aSegments = array();
		foreach ($segments as $seg) {
			$aSegments[] = $seg->id;
		}

		// Build the path.
		$path = trim(implode('/', $aSegments), ' /\\');

		// Update the path field for the node.
		$this->db->set('path', $path);
		$this->db->where($this->table_key.' = '.(int) $pk);
		$result = $this->db->update($this->table_name);

		// Check for a database error.
		if (!$result) {
			return false;
		}

		// Update the current record's path to the new one:
		//$this->path = $path;

		return true;
	}
	
	/**
	 * Method to get nested set properties for a node in the tree.
	 *
	 * @param   integer  $id   Value to look up the node by.
	 * @param   string   $key  Key to look up the node by.
	 *
	 * @return  mixed    Boolean false on failure or node object on success.
	 */
	protected function _getNode($id, $key = null) {
		// Determine which key to get the node base on.
		switch ($key) {
			case 'parent':
				$k = 'parent_id';
				break;
			case 'left':
				$k = 'lft';
				break;
			case 'right':
				$k = 'rgt';
				break;
			default:
				$k = $this->table_key;
				break;
		}

		// Get the node data.
		$this->db->select($this->table_key.', parent_id, level, lft, rgt');
		$this->db->where($k.' = '.(int) $id);
		$row = $this->db->get($this->table_name, 1, 0)->row();

		// Check for a database error or no $row returned
		/*
		if ((!$row) || ($this->_db->getErrorNum()))
		{
			$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_GETNODE_FAILED', get_class($this), $this->_db->getErrorMsg()));
			$this->setError($e);
			return false;
		}
		*/
		if (!$row) {
			return false;
		}

		// Do some simple calculations.
		$row->numChildren = (int) ($row->rgt - $row->lft - 1) / 2;
		$row->width = (int) $row->rgt - $row->lft + 1;

		return $row;
	}
	
	/**
	 * Method to get various data necessary to make room in the tree at a location
	 * for a node and its children.  The returned data object includes conditions
	 * for SQL WHERE clauses for updating left and right id values to make room for
	 * the node as well as the new left and right ids for the node.
	 *
	 * @param   object   $referenceNode  A node object with at least a 'lft' and 'rgt' with
	 * which to make room in the tree around for a new node.
	 * @param   integer  $nodeWidth      The width of the node for which to make room in the tree.
	 * @param   string   $position       The position relative to the reference node where the room
	 * should be made.
	 *
	 * @return  mixed    Boolean false on failure or data object on success.
	 */
	protected function _getTreeRepositionData($referenceNode, $nodeWidth, $position = 'before') {
		// Make sure the reference an object with a left and right id.
		if (!is_object($referenceNode) && isset($referenceNode->lft) && isset($referenceNode->rgt)) {
			return false;
		}

		// A valid node cannot have a width less than 2.
		if ($nodeWidth < 2) {
			return false;
		}

		// Initialise variables.
		$k = $this->table_key;
		$data = new stdClass;

		// Run the calculations and build the data object by reference position.
		switch ($position) {
			case 'first-child':
				$data->left_where = 'lft > ' . $referenceNode->lft;
				$data->right_where = 'rgt >= ' . $referenceNode->lft;

				$data->new_lft = $referenceNode->lft + 1;
				$data->new_rgt = $referenceNode->lft + $nodeWidth;
				$data->new_parent_id = $referenceNode->$k;
				$data->new_level = $referenceNode->level + 1;
				break;

			case 'last-child':
				$data->left_where = 'lft > ' . ($referenceNode->rgt);
				$data->right_where = 'rgt >= ' . ($referenceNode->rgt);

				$data->new_lft = $referenceNode->rgt;
				$data->new_rgt = $referenceNode->rgt + $nodeWidth - 1;
				$data->new_parent_id = $referenceNode->$k;
				$data->new_level = $referenceNode->level + 1;
				break;

			case 'before':
				$data->left_where = 'lft >= ' . $referenceNode->lft;
				$data->right_where = 'rgt >= ' . $referenceNode->lft;

				$data->new_lft = $referenceNode->lft;
				$data->new_rgt = $referenceNode->lft + $nodeWidth - 1;
				$data->new_parent_id = $referenceNode->parent_id;
				$data->new_level = $referenceNode->level;
				break;

			default:
			case 'after':
				$data->left_where = 'lft > ' . $referenceNode->rgt;
				$data->right_where = 'rgt > ' . $referenceNode->rgt;

				$data->new_lft = $referenceNode->rgt + 1;
				$data->new_rgt = $referenceNode->rgt + $nodeWidth;
				$data->new_parent_id = $referenceNode->parent_id;
				$data->new_level = $referenceNode->level;
				break;
		}
		
		/*
		if ($this->_debug)
		{
			echo "\nRepositioning Data for $position" . "\n-----------------------------------" . "\nLeft Where:    $data->left_where"
				. "\nRight Where:   $data->right_where" . "\nNew Lft:       $data->new_lft" . "\nNew Rgt:       $data->new_rgt"
				. "\nNew Parent ID: $data->new_parent_id" . "\nNew Level:     $data->new_level" . "\n";
		}
		*/
		
		return $data;
	}

}

?>