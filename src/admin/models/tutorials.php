<?php 
defined('_JEXEC') or die;
jimport('joomla.application.component.model');

class OSToolbarModelTutorials extends OSToolbarModel {

	protected $option 		= null;
	protected $view			= null;
	protected $context		= null;
	protected $pagination 	= null;
	
	protected $list			= null;
	protected $total		= null;
	
  	public function __construct() {
    	parent::__construct();
		
		$this->option 	= JRequest::getCmd('option');
		$this->view 	= JRequest::getCmd('view');
		$this->context 	= $this->option.'.'.$this->view;
		
    	$this->populateState();    
  	}

	protected function populateState() {
		$app = JFactory::getApplication();
		//$search 			= $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
		//$this->setState('filter.search', $search);
		
		$filter_order		= $app->getUserStateFromRequest($this->context.'.filter.order', 'filter_order', 't.name', 'string');
		$this->setState('filter.order', $filter_order);
		
		$filter_order_Dir	= $app->getUserStateFromRequest($this->context.'.filter.order_dir', 'filter_order_Dir', 'ASC', 'string');
		$this->setState('filter.order_dir', $filter_order_Dir);
		
		$category			= $app->getUserStateFromRequest($this->context.'.category', 'category', null);
		$this->setState('category', $category);
  	}
		
	public function getList($all = false) {
		$cids = JRequest::getVar('cid', array());
		//$this->data = $this->_fetchList();
		$this->data = OSToolbarCacheHelper::callback($this, '_fetchList', array(), null, true);

		$params		= JComponentHelper::getParams('com_ostoolbar');
		$selected = array();
		if (!$all && $params->get("videos"))
			$selected = preg_split("/,/",$params->get("videos"));
		if (count($selected))
		{
			$data = array();
			foreach ($selected as $item)
			{
				foreach ($this->data as $row)
				{
					if ("s_".$row->id == $item)
					{
						$data[] = $row;
						break;
					}
				}
			}
			$this->data = $data;
		}


		return $this->data;
	}
		
	public function _fetchList() {
		$data	= array('resource' => 'articles');

		$response = OSToolbarRequestHelper::makeRequest($data);
		if ($response->hasError()) :
			JFactory::getApplication()->enqueueMessage(JText::_('COM_OSTOOLBAR_API_KEY_ERROR'), 'error');
			//$this->setError(JText::_('COM_OSTOOLBAR_ERROR').':  '.$response->getErrorMsg().' ('.JText::_('COM_OSTOOLBAR_ERROR_CODE').' '.$response->getErrorCode().')');
			return false;
		endif;
		
		$list	= $response->getBody();
		for($i=0; $i<count($list); $i++) :
			$list[$i]->link = 'index.php?option=com_ostoolbar&view=tutorial&id='.$list[$i]->id;
		endfor;
		
		return $list;
	}

	public function getFilters($rows) {
		$filters = array();
		
		$cats = array();
		$options = array();
		if ($rows) :
			foreach($rows as $row) :
				if ($row->ostcat_id && !in_array($row->ostcat_id, $cats)) :
					$cats[] = $row->ostcat_id;
					$options[] = JHTML::_('select.option', $row->ostcat_id, $row->ostcat_name);
					endif;
				endforeach;
		endif;
		JArrayHelper::sortObjects($options, 'text');
		$options = array_merge(
					array(
						JHTML::_('select.option', '', 'All'),
						JHTML::_('select.option', 'none', '--')
					),
					$options
				);
		
		$attributes = "class='inputbox' onchange='document.adminForm.submit();'";
		
		$filters['category'] 	= JHTML::_('select.genericlist', $options, 'category', $attributes, 'value', 'text', $this->getState('category'));
		
		return $filters;
	}

	public function applyFilters($rows) {
		$filters = array();
		if ($this->getState('category', '') != '') :
			$category = $this->getState('category');
			$category = $category == 'none' ? null : $category;
			$filters[] = array('field' => 'ostcat_id', 'value' => $category);
		endif;
		
		if ($filters) :	
			$filtered = array();
			foreach($rows as $row) :
				$pass = true;
				foreach($filters as $f) :
					if ($row->{$f['field']} != $f['value']) :
						$pass = false;
					endif;
				endforeach;
				if ($pass) :
					$filtered[] = $row;
				endif;
			endforeach;
		else :
			$filtered = $rows;
		endif;
		
		return $filtered;
	}

}
