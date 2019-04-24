<?php
////////////////////////////////////////////////////////////////////////////////
//   Copyright (C) ReloadCMS Development Team                                 //
//   http://reloadcms.com                                                     //
//   This product released under GNU General Public License v2                //
////////////////////////////////////////////////////////////////////////////////

class calendar  {
    var $_temp = array();
    var $_events = array();
    var $_highlight = array();
    
    function __construct($month, $year){
        global $system;
        $this->_temp['first_day_stamp'] = mktime(0, 0, 0, $month, 1, $year);
        $this->_temp['first_day_week_pos'] = date('w', $this->_temp['first_day_stamp']);
        $this->_temp['number_of_days'] = date('t', $this->_temp['first_day_stamp']);
    }
    
    function assignEvent($day, $link){
        $this->_events[(int)$day] = $link;
    }
    
    function highlightDay($day, $style = '!'){
        $this->_highlight[(int)$day] = $style;
    }
    
    function returnCalendar($template='calendar.tpl'){
        global $system;
        return rcms_parse_module_template($template,array('_temp'=>$this->_temp,'_events'=>$this->_events,'_highlight'=>$this->_highlight,));
    }
    
}
?>