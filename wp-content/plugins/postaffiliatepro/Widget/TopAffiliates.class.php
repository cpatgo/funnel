<?php
class postaffiliatepro_Widget_TopAffiliates extends WP_Widget {
    const TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME = 'pap-top-affiliates-widget-settings-page';
    const TOP_AFFILAITES_REFRESHTIME = 'pap-top-affiliates-refresh-time';
    const TOP_AFFILAITES_REFRESHINTERVAL = 'pap-top-affiliates-refresh-interval';
    const TOP_AFFILAITES_CACHE = 'pap-top-affiliates-cache';
    const TOP_AFFILAITES_ORDER_BY = 'pap-top-affiliates-order-by';
    const TOP_AFFILAITES_ORDER_ASC = 'pap-top-affiliates-order-asc';
    const TOP_AFFILAITES_LIMIT = 'pap-top-affiliates-limit';
    const TOP_AFFILAITES_ROW_TEMPLATE = 'pap-top-affiliates-row-template';

    private $topAffiliatesHelper;

    function __construct() {
        parent::__construct(false, $name = 'Top affiliates');
        $this->topAffiliatesHelper = new postaffiliatepro_Util_TopAffiliatesHelper();
    }

    public function initSettings() {
        register_setting(self::TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME, self::TOP_AFFILAITES_REFRESHTIME);
        register_setting(self::TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME, self::TOP_AFFILAITES_REFRESHINTERVAL);
        register_setting(self::TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME, self::TOP_AFFILAITES_CACHE);
        register_setting(self::TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME, self::TOP_AFFILAITES_ORDER_BY);
        register_setting(self::TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME, self::TOP_AFFILAITES_ORDER_ASC);
        register_setting(self::TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME, self::TOP_AFFILAITES_LIMIT);
        register_setting(self::TOP_AFFILAITES_WIDGET_SETTINGS_PAGE_NAME, self::TOP_AFFILAITES_ROW_TEMPLATE);
    }

    private function setCache(Gpf_Data_RecordSet $affiliates) {
        update_option(self::TOP_AFFILAITES_CACHE, serialize($affiliates));
    }

    /**
     * @return Gpf_Data_RecordSet
     */
    private function getCache() {
        $recordset = new Gpf_Data_RecordSet();
        $recordset = unserialize(get_option(self::TOP_AFFILAITES_CACHE));
        return $recordset;
    }

    private function getVariablesArray() {
        return array(
            'firstname',
            'lastname',
            'userid',
            'parentuserid',
            'clicksAll',
            'salesCount',
            'commissions'
        );
    }

    private function fillVariables($row, $template) {
        $variables = $this->getVariablesArray();
        foreach ($variables as $variable) {
            $template = preg_replace('/\{\$'.$variable.'\}/i', $row->get($variable), $template);
        }
        return $template;
    }

    protected function renderContent($instance) {
        if (get_option(self::TOP_AFFILAITES_REFRESHTIME)=='' ||
                time()-get_option(self::TOP_AFFILAITES_REFRESHTIME)-$instance[self::TOP_AFFILAITES_REFRESHINTERVAL]*60 >= 0) {
            $affilites = $this->topAffiliatesHelper->getTopAffiliatesList($this->getOrderBy($instance), false, $this->getLimit($instance));
            $this->setCache($affilites);
            update_option(self::TOP_AFFILAITES_REFRESHTIME, time());
        } else {
            $affilites = $this->getCache();
        }
        echo '<ol>';
        foreach ($affilites as $row) {
            echo '<li>' . $this->fillVariables($row, $this->getRowTemplate($instance)). '</li>';
        }
        echo '</ol>';
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        if ( $title )
            echo $before_title . $title . $after_title;
		$this->renderContent($instance);
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance[self::TOP_AFFILAITES_REFRESHINTERVAL] = strip_tags($new_instance[self::TOP_AFFILAITES_REFRESHINTERVAL]);
        $instance[self::TOP_AFFILAITES_ORDER_BY] = strip_tags($new_instance[self::TOP_AFFILAITES_ORDER_BY]);
        $instance[self::TOP_AFFILAITES_ORDER_ASC] = strip_tags($new_instance[self::TOP_AFFILAITES_ORDER_ASC]);
        $instance[self::TOP_AFFILAITES_LIMIT] = strip_tags($new_instance[self::TOP_AFFILAITES_LIMIT]);
        $instance[self::TOP_AFFILAITES_ROW_TEMPLATE] = strip_tags($new_instance[self::TOP_AFFILAITES_ROW_TEMPLATE]);

        update_option(self::TOP_AFFILAITES_REFRESHINTERVAL, strip_tags($new_instance[self::TOP_AFFILAITES_REFRESHINTERVAL]));
        update_option(self::TOP_AFFILAITES_ORDER_BY, strip_tags($new_instance[self::TOP_AFFILAITES_ORDER_BY]));
        update_option(self::TOP_AFFILAITES_ORDER_ASC, strip_tags($new_instance[self::TOP_AFFILAITES_ORDER_ASC]));
        update_option(self::TOP_AFFILAITES_LIMIT, strip_tags($new_instance[self::TOP_AFFILAITES_LIMIT]));
        update_option(self::TOP_AFFILAITES_ROW_TEMPLATE, strip_tags($new_instance[self::TOP_AFFILAITES_ROW_TEMPLATE]));

        update_option(self::TOP_AFFILAITES_REFRESHTIME, '');
        return $instance;
    }

    private function getTitle($instance) {
        if (!array_key_exists('title',$instance)) {
            return 'Top affiliates';
        }
        return esc_attr($instance['title']);
    }

    private function getRefreshInterval($instance) {
        if (!array_key_exists(self::TOP_AFFILAITES_REFRESHINTERVAL,$instance)) {
            return '10';
        }
        return esc_attr($instance[self::TOP_AFFILAITES_REFRESHINTERVAL]);
    }

    private function getOrderBy($instance) {
        if (!array_key_exists(self::TOP_AFFILAITES_ORDER_BY,$instance)) {
            return postaffiliatepro_Util_TopAffiliatesHelper::COL_SALES_COUNT;
        }
        return esc_attr($instance[self::TOP_AFFILAITES_ORDER_BY]);
    }

    private function getLimit($instance) {
        if (!array_key_exists(self::TOP_AFFILAITES_LIMIT,$instance)) {
            return '5';
        }
        return esc_attr($instance[self::TOP_AFFILAITES_LIMIT]);
    }

    private function getRowTemplate($instance) {
        if (!array_key_exists(self::TOP_AFFILAITES_ROW_TEMPLATE,$instance)) {
            return '{$firstname} {$lastname} ({$userid}): clicks: {$clicksAll}; sales: {$salesCount}; commissions: {$commissions} [parent: {$parentuserid}]';
        }
        return esc_attr($instance[self::TOP_AFFILAITES_ROW_TEMPLATE]);
    }

    function form($instance) {
        $title = $this->getTitle($instance);
        $refreshInterval = $this->getRefreshInterval($instance);
        $orderBy = $this->getOrderBy($instance);
        $limit = $this->getLimit($instance);
        $rowTemplate = $this->getRowTemplate($instance);
        echo '<p>';
        echo '<label for=' . $this->get_field_id('title') . ">" . _e('Title:') . "</label>";
        echo "<input class='widefat' id=" . $this->get_field_id('title') . " name=" . $this->get_field_name('title') . " type='text' value=" . $title . " /><br/><br/>";
        echo '<label for=' . $this->get_field_id(self::TOP_AFFILAITES_REFRESHINTERVAL) . ">" . _e('Refresh interval [minutes]:') . "</label>";
        echo "<input class='widefat' id=" . $this->get_field_id(self::TOP_AFFILAITES_REFRESHINTERVAL) . " name=" . $this->get_field_name(self::TOP_AFFILAITES_REFRESHINTERVAL) . " type='text' value=" . $refreshInterval . " /><br/><br/>";
        echo '<label for=' . $this->get_field_id(self::TOP_AFFILAITES_ORDER_BY) . ">" . _e('Order by:') . "</label>";
        echo "<select class='widefat' id=" . $this->get_field_id(self::TOP_AFFILAITES_ORDER_BY) . " name=" . $this->get_field_name(self::TOP_AFFILAITES_ORDER_BY) . ">";
        echo $this->topAffiliatesHelper->getOrderOptions($orderBy);
        echo '</select><br /><br />';
        echo "<label for=" . $this->get_field_id(self::TOP_AFFILAITES_LIMIT) . ">" . _e('Limit [affiliates]:') . "</label>";
        echo "<input class='widefat' id=" . $this->get_field_id(self::TOP_AFFILAITES_LIMIT) . " name=" . $this->get_field_name(self::TOP_AFFILAITES_LIMIT) . " type='text' value=" . $limit . " /><br/><br/>";
        echo "<label for=" . $this->get_field_id(self::TOP_AFFILAITES_ROW_TEMPLATE) . ">" . _e('Table row template:') . "</label>";
        echo "<textarea class='widefat' id=" . $this->get_field_id(self::TOP_AFFILAITES_ROW_TEMPLATE) . " name=" . $this->get_field_name(self::TOP_AFFILAITES_ROW_TEMPLATE) . " rows=5>" . $rowTemplate . "</textarea>";
        echo '</p>';
    }
}

$topAffiliates = new postaffiliatepro_Widget_TopAffiliates();
add_action('admin_init', array($topAffiliates, 'initSettings'), 99);
add_action('widgets_init', create_function('', 'return register_widget("postaffiliatepro_Widget_TopAffiliates");'));