<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellegacy');

class OSToolbarModelTutorials extends OstoolbarModel
{
    /**
     * @var array
     */
    protected $data = array();

    public function getList()
    {
        $this->data = OstoolbarCache::callback($this, 'fetchList', array(), null, true);

        $params   = JComponentHelper::getParams('com_ostoolbar');
        $selected = array();
        if ($params->get('videos')) {
            $selected = preg_split('/,/', $params->get('videos'));
        }
        if (count($selected)) {
            $data = array();
            foreach ($selected as $item) {
                foreach ($this->data as $row) {
                    if ('s_' . $row->id == $item) {
                        $data[] = $row;
                        break;
                    }
                }
            }
            $this->data = $data;
        }

        return $this->data;
    }

    public function fetchList()
    {
        $data     = array('resource' => 'articles');
        $response = OstoolbarRequest::makeRequest($data);

        if ($response->hasError()) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_OSTOOLBAR_API_KEY_ERROR'), 'error');
            return false;
        }

        $list = $response->getBody();
        for ($i = 0; $i < count($list); $i++) {
            $list[$i]->link = 'index.php?option=com_ostoolbar&view=tutorial&id=' . $list[$i]->id;
        }

        return $list;
    }
}
