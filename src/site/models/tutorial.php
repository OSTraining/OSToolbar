<?php
defined('_JEXEC') or die;

class OSToolbarModelTutorial extends OstoolbarModel
{
    /**
     * @var object
     */
    protected $data = null;

    protected function populateState()
    {
        $app = JFactory::getApplication();

        $id = $app->get('cid', array(), 'request', 'array');
        if (empty($id)) {
            $id = $app->input->getInt('id', 0);
        } else {
            $id = $id[0];
        }
        $this->setState('id', $id);
    }

    public function getData()
    {
        $id         = $this->getState('id', 0);
        $this->data = OstoolbarCache::callback($this, 'fetchData', array($id));

        return $this->data;
    }

    public function fetchData($id)
    {
        $data = array('resource' => 'article', 'id' => $id);

        $response = OstoolbarRequest::makeRequest($data);
        if ($response->hasError()) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_OSTOOLBAR_API_KEY_ERROR'), 'error');
            return false;
        }

        $body            = $response->getBody();
        $body->introtext = OstoolbarRequest::filter($body->introtext);
        $body->fulltext  = OstoolbarRequest::filter($body->fulltext);

        return $body;
    }
}
