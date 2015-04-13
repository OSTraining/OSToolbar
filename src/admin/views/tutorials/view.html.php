<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class OstoolbarViewTutorials extends OstoolbarViewAdmin
{
    protected $model   = null;
    protected $rows    = array();
    protected $filters = null;

    public function display($tpl = null)
    {
        $app = JFactory::getApplication();

        $this->model = $this->getModel();

        if ($app->input->get('session', 0) && $app->input->getCmd('tmpl', '') == 'component') {
            $session    = JFactory::getSession();
            $this->rows = $session->get('helparticles', array(), 'OSToolbar');
            $this->setLayout('popup');
        } else {
            $this->rows = $this->model->getList();
            if ($errors = $this->model->getErrors()) {
                OstoolbarHelper::renderErrors($errors);
            }
        }

        $this->filters = $this->model->getFilters($this->rows);

        $params = JComponentHelper::getParams('com_ostoolbar');
        if (OstoolbarRequest::$isTrial) {
            if ($params->get('api_key')) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_OSTOOLBAR_API_KEY_INVALIAD'), 'error');
            }
        }

        $this->setToolBar();
        parent::display($tpl);
    }

    protected function setToolbar($addDivider = false)
    {
        $this->setTitle('COM_OSTOOLBAR_TUTORIALS');
        parent::setToolBar($addDivider);
    }

}
