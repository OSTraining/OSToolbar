<?php 
defined('_JEXEC') or die;
jimport('joomla.application.component.model');

class OSToolbarModelTutorials extends JModel {

		
	public function getList() {
		$cids = JRequest::getVar('cid', array());
		//$this->data = $this->_fetchList();
		$this->data = OSToolbarCacheHelper::callback($this, '_fetchList', array(), null, true);

		$params		= JComponentHelper::getParams('com_ostoolbar');
		$selected = array();
		if ($params->get("videos"))
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


}
