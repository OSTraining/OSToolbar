<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

class OstoolbarModelTutorial extends OstoolbarModel
{
    protected $data = null;

    protected function populateState()
    {
        $app = JFactory::getApplication();

        $id = $app->input->get('cid', array(), 'request', 'array');
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

        $body = $response->getBody();

        // @TODO: A terrible way to get what we want
        list(, $urlVars) = explode('?', OstoolbarRequest::getHostUrl());
        parse_str($urlVars, $urlQuery);
        $body->jversion = $urlQuery['v'];
        // End terribleness

        return $body;
    }
}
