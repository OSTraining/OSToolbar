<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

jimport('joomla.application.component.model');

class OstoolbarModelTutorials extends OstoolbarModel
{
    protected $view       = null;
    protected $context    = null;
    protected $pagination = null;

    protected $data = null;

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $this->view    = $app->input->getCmd('view', 'tutorials');
        $this->context = $this->option . '.' . $this->view;
    }

    protected function populateState()
    {
        $app = JFactory::getApplication();

        $filter_order = $app->getUserStateFromRequest(
            $this->context . '.filter.order',
            'filter_order',
            't.name',
            'string'
        );
        $this->setState('filter.order', $filter_order);

        $filter_order_Dir = $app->getUserStateFromRequest(
            $this->context . '.filter.order_dir',
            'filter_order_Dir',
            'ASC',
            'string'
        );
        $this->setState('filter.order_dir', $filter_order_Dir);

        $category = $app->getUserStateFromRequest($this->context . '.category', 'category', null);
        $this->setState('category', $category);
    }

    public function getList($all = false)
    {
        $this->data = OstoolbarCache::callback($this, '_fetchList', array(), null, true);

        $params   = JComponentHelper::getParams('com_ostoolbar');
        $selected = array();
        if (!$all && $params->get("videos")) {
            $selected = preg_split("/,/", $params->get("videos"));
        }
        if (count($selected)) {
            $data = array();
            foreach ($selected as $item) {
                foreach ($this->data as $row) {
                    if ("s_" . $row->id == $item) {
                        $data[] = $row;
                        break;
                    }
                }
            }
            $this->data = $data;
        }

        return $this->data;
    }

    public function _fetchList()
    {
        $data = array('resource' => 'articles');

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

    public function getFilters($rows)
    {
        $filters = array();

        $cats    = array();
        $options = array();
        if ($rows) {
            foreach ($rows as $row) {
                if ($row->ostcat_id && !in_array($row->ostcat_id, $cats)) {
                    $cats[]    = $row->ostcat_id;
                    $options[] = JHtml::_('select.option', $row->ostcat_id, $row->ostcat_name);
                }
            }
        }
        JArrayHelper::sortObjects($options, 'text');
        array_unshift($options, JHtml::_('select.option', '', 'All'));

        $attributes = "class='inputbox' onchange='document.adminForm.submit();'";

        $filters['category'] = JHtml::_(
            'select.genericlist',
            $options,
            'category',
            $attributes,
            'value',
            'text',
            $this->getState('category')
        );

        return $filters;
    }

    public function applyFilters($rows)
    {
        $filters = array();
        if ($this->getState('category', '') != '') {
            $category  = $this->getState('category');
            $filters[] = array('field' => 'ostcat_id', 'value' => $category);
        }

        if ($filters) {
            $filtered = array();
            foreach ($rows as $row) {
                $pass = true;
                foreach ($filters as $f) {
                    if ($row->{$f['field']} != $f['value']) {
                        $pass = false;
                    }
                }
                if ($pass) {
                    $filtered[] = $row;
                }
            }
        } else {
            $filtered = $rows;
        }

        return $filtered;
    }
}
