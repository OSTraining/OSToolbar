<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();


class OstoolbarViewTutorial extends OstoolbarViewAdmin
{
    /**
     * @var string
     */
    protected $return = null;

    /**
     * @var OstoolbarModelTutorial
     */
    protected $model = null;

    /**
     * @var object
     */
    protected $row = null;

    public function display($tpl = null)
    {
        $app = JFactory::getApplication();

        if ($app->input->getCmd('tmpl', '') == 'component') {
            $this->setLayout('popup');
        }

        $this->model  = $this->getModel();
        $this->row    = $this->model->getData();
        $this->return = 'index.php?option=' . $this->option . '&view=tutorials';

        $this->setToolBar();
        parent::display($tpl);
    }

    protected function setToolBar($addDivider = true)
    {
        $this->setTitle();

        OstoolbarToolbarHelper::link(
            'tutorials',
            JText::_('COM_OSTOOLBAR_TUTORIALS'),
            'index.php?option=com_ostoolbar&view=tutorials'
        );
        parent::setToolBar();
    }
}
