<?php
namespace App\Helpers;

class CalendarHelper 
{

    public function displayCalendars($defaultView, $title, $description, $location, $startDate, $endDate = null) 
    {
    	$startDateString = date('Ymd', strtotime($startDate)).'T'.date('His', strtotime($startDate));
    	$endDateString = ($endDate != null ? date('Ymd', strtotime($endDate)).'T'.date('His', strtotime($endDate)) : date('Ymd', strtotime($startDate)).'T'.date('His', strtotime($startDate.'+2 hours')));
    	$todayDateString = date('Ymd').'T'.date('His');

   		// Google
    	$urls[] = '<a href=\'https://www.google.com/calendar/render?action=TEMPLATE&text='.urlencode($title).'&dates='.$startDateString.'/'.$endDateString.'&details='.urlencode($description).'&location='.urlencode($location).'&trp=false&sprop=&sprop=name:\' target=\'_blank\' class=\'item\'>Google Kalender</a>';
    	
    	// Yahoo
    	$urls[] = '<a href=\'http://calendar.yahoo.com/?v=60&TITLE='.urlencode($title).'&DESC='.urlencode($description).'&ST='.$startDateString.'&DUR=0200&in_loc='.urlencode($location).'\'  target=\'_blank\' class=\'item\'>Yahoo! Kalender</a>';
    
    	// Yahoo
    	$urls[] = '<a href=\''.url('create-ics?title='.urlencode($title).'&description='.urlencode($description)).'&location='.urlencode($location).'&startdate='.$startDateString.'&enddate='.$endDateString.'&todaydate='.$todayDateString.'\' target\'_blank\' class=\'item\'>iCalendar</a>';
    	
    	if ($defaultView == 1) {
    		return '<div class=\'ui simple dropdown icon blue button\'><div class=\'text\'>Toevoegen aan agenda</div><div class=\'menu\'>'.implode(' ', $urls).'</div></div>';
    	} else {
			return implode(' - ', $urls);
    	}
    }
    
}