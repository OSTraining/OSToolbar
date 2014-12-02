<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class OstoolbarViewTutorials extends OstoolbarViewAdmin
{
    protected $model = null;
    protected $rows = array();
    protected $filters = null;

    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $this->generateToolbar();

        $this->model = JModelLegacy::getInstance('Tutorials', 'OSToolbarModel');

        if ($app->input->get('session', 0) && $app->input->getCmd('tmpl', '') == 'component') {
            $session    = JFactory::getSession();
            $this->rows = $session->get('helparticles', array(), 'OSToolbar');
            $this->setLayout('popup');
        } else {
            $this->rows = $this->model->getList();
            if ($errors = $this->model->getErrors()) {
                OSToolbarHelper::renderErrors($errors);
            }
        }

        $this->filters = $this->model->getFilters($this->rows);

        $params = JComponentHelper::getParams('com_ostoolbar');
        if (OSToolbarRequestHelper::$isTrial) {
            if ($params->get('api_key')) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_OSTOOLBAR_API_KEY_INVALIAD'), 'error');
            }
        }

        parent::display($tpl);
    }

    private function generateToolbar()
    {
        OSToolbarHelper::setPageTitle(JText::_('COM_OSTOOLBAR_TUTORIALS'));
        OSToolbarHelper::customButton(
            JText::_('COM_OSTOOLBAR_TUTORIALS'),
            'icon-32-tutorials',
            'tutorials',
            'index.php?option=com_ostoolbar&view=tutorials'
        );
        JToolBarHelper::preferences('com_ostoolbar', 500, 700);
    }
}
