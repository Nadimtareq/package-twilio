

<!DOCTYPE html>
<html>
<head>
	<title></title>

	<style>
		@page{
			margin: 0;
			padding-top: 30%;
			width: 100%;
		}
	</style>
	<style type="text/css">

		@import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700,800,300);


		body {
			background-color: #114C8D;
			color: #000033;
			font-family: "verdana", "sans-serif";
			margin: 0px;
			padding-top: 0px;
			font-size: 1.2em;
		}

		h1 {
			font-size: 1.1em;
			color: #114C8D;
			font-style: italic;
		}

		h2 {
			font-size: 1.05em;
			color: #114C8D;
		}

		h3 {
			font-size: 1em;
			color: #114C8D;
		}

		img {
			border: none;
		}

		img.border {
			border: 1px solid #114C8D;
		}

		pre {
			font-family: "verdana", "sans-serif";
			color: #FFFFff;
			font-size: 0.7em;
		}

		ul {
			color: #BEAC8B;
			list-style-type: circle;
			list-style-position: inside;
			margin: 0px;
			padding: 3px;
		}

		li {
			color: #000033;
		}

		li.alpha {
			list-style-type: lower-alpha;
			margin-left: 15px;
		}

		p {
			font-size: 0.8em;
		}

		a:link,
		a:visited {
			text-decoration: none;
			color: #114C8D;
		}

		a:hover {
			text-decoration: underline;
			color: #860000;
		}

		hr {
			border: 0;
		}

		#page_header {
			position: relative; /* required to make the z-index work */
			z-index: 2;
		}

		#body {
			background-color: #F9F0E9;
			padding: 12px 0.5% 2em 3px;
			min-height: 20em;
			margin: 0px;
			width: 100%;
		}

		#body pre {
			color: #000033;
		}

		#left_column {
			width: 84%;
			height: auto;
			padding-right: 8px;
			padding-bottom: 30px;
		}

		#right_column {
			/*  position: absolute;
              right: 0.5%;*/
			padding-left: 16px;
			width: 15%;
			min-width: 160px;
		}

		/* Footer
        -----------------------------------------------------------------------*/
		#footer {
			color: #FFFFff;
			border-top: 1px solid #000033;
		}

		#copyright {
			padding: 5px;
			font-size: 0.6em;
			background-color: #114C8D;
		}

		#footer_spacer_row {
			border-spacing: 0;
			width: 100%;
		}
		.footertwo {
			position: fixed;
			left: 0px; bottom: -150px; right: 0px; height: 150px;
		}

		#footer_spacer_row td {
			padding: 0px;
			border-bottom: 1px solid #000033;
			background-color: #F7CF07;
			height: 2px;
			font-size: 2px;
			line-height: 2px;
		}

		/* Tables
        -----------------------------------------------------------------------*/
		table {
			empty-cells: show;
		}

		.head td {
			color: #8B7958;
			background-color: #E5D9C3;
			font-weight: bold;
			font-size: 0.7em;
			padding: 3px;
		}

		.head input {
			font-weight: normal;
		}

		.sub_head td {
			border: none;
			white-space: nowrap;
			font-size: 10px;
		}

		.foot td {
			color: #8B7958;
			background-color: #E5D9C3;
			font-size: 0.8em;
		}

		.label {
			color: #8B7958;
			background-color: #F8F5F2;
			padding: 3px;
			font-size: 0.75em;
		}

		.label_right {
			color: #8B7958;
			background-color: #F8F5F2;
			padding: 3px;
			font-size: 0.75em;
			text-align: right;
			padding-right: 1em;
		}

		.sublabel {
			color: #8B7958;
			font-size: 0.6em;
			padding: 0px;
			text-align: center;
		}

		.field {
			color: #000033;
			background-color: #F9F0E9;
			padding: 3px;
			font-size: 0.75em;
		}

		.field_center {
			color: #000033;
			background-color: #F9F0E9;
			padding: 3px;
			font-size: 0.75em;
			text-align: center;
		}

		.field_nw {
			color: #000033;
			background-color: #F9F0E9;
			padding: 3px;
			font-size: 0.75em;
			white-space: nowrap;
		}

		.field_money {
			color: #000033;
			background-color: #F9F0E9;
			padding: 3px;
			font-size: 0.75em;
			white-space: nowrap;
			text-align: right;
		}

		.field_total {
			color: #000033;
			background-color: #F9F0E9;
			padding: 3px;
			font-size: 0.75em;
			white-space: nowrap;
			text-align: right;
			font-weight: bold;
		}

		/* Table Data
        -----------------------------------------------------------------------*/
		.h_scrollable {
			overflow: -moz-scrollbars-horizontal;
		}

		.v_scrollable {
			overflow: -moz-scrollbars-vertical;
		}

		.scrollable {
			overflow: auto;/*-moz-scrollbars-horizontal;*/
		}

		tr.head>td.center,
		tr.list_row>td.center,
		.center {
			text-align: center;
		}

		.left,
		tr.head>td.left,
		tr.list_row>td.left {
			text-align: left;
			padding-left: 2em;
		}

		.total,
		.right,
		.list tr.head td.right,
		tr.list_row td.right,
		tr.foot td.right,
		tr.foot td.total {
			text-align: right;
			padding-right: 2em;
		}

		.list tr.foot td {
			font-weight: bold;
		}

		.no_wrap {
			white-space: nowrap;
		}


		.total {
			font-weight: bold;
		}

		.summary_spacer_row {
			line-height: 2px;
		}

		.light {
			color: #999999;
		}

		/* Detail
        -----------------------------------------------------------------------*/
		.fax_head,
		.narrow,
		.detail {
			border-spacing: 1px;
			border-top: 1px solid #8B7958;
			width: 99%;
			padding: 3px;
			margin-bottom: 10px;
		}

		.detail td.label {
			width: 16%;
			background-color: #F9F0E9;
		}

		.detail td.field {
			width: 33%;
			text-align: center;
			background-color: #F8F5F2;
		}

		.detail_spacer_row td {
			background-color: #BEAC8B;
			font-size: 2px;
			line-height: 2px;
			padding: 0px;
			border-top: 1px solid #F9F0E9;
			border-bottom: 1px solid #F9F0E9;
		}

		.detail td.field_money {
			width: 33%;
			background-color: #F8F5F2;
		}

		.narrow {
			width: 60%;
		}

		.narrow td.label {
			width: 50%;
			background-color: #F9F0E9;
		}

		.narrow td.field_money,
		.narrow td.field_total,
		.narrow td.field {
			width: 49%;
		}

		.narrow td.field_money,
		.narrow td.field {
			background-color: #F8F5F2;
		}

		.narrow td.field_total,
		.narrow td.field_money {
			padding-right: 4em;
		}

		.detail td.field {
			text-align: center;
			background-color: #F8F5F2;
		}

		.fax_head td.label {
			width: 7%;
		}

		.fax_head td.field {
			width: 26%;
		}

		.operation {
			width: 1%;
		}

		/* Wizards
        -----------------------------------------------------------------------*/
		.wizard {
			/*  border-spacing: 0px; */
			border-top: 1px solid #8B7958;
			border-bottom: 1px solid #8B7958;
		}

		.wizard_buttons {
			font-size: 0.75em;
			margin: 3px;
		}

		/* Forms
        -----------------------------------------------------------------------*/
		.form {
			/*  border-spacing: 0px; */
			border-top: 1px solid #8B7958;
			border-bottom: 1px solid #8B7958;
			padding: 1px;
		}

		.form tr.head input {
			font-weight: normal;
		}

		.form tr.head td {
			padding: 2px;
		}

		.form tr.foot td {
			text-align: center;
			padding: 2px;
		}


		/* Lists
        -----------------------------------------------------------------------*/
		.list {
			border-collapse: collapse;
			border-spacing: 0px;
			width: 99%;
			margin-top: 3px;
		}

		.list tr.head td {
			font-size: 0.7em;
			white-space: nowrap;
			padding-right: 0.65em;
			border-bottom: 1px solid #8B7958;
		}

		.list table.sub_head td {
			border: none;
			white-space: nowrap;
			font-size: 10px;
		}

		.list tr.foot td {
			border-top: 1px solid #8B7958;
			font-size: 0.7em;
		}

		tr.list_row>td {
			background-color: #EDF2F7;
			border-bottom: 1px dotted #8B7958;
			font-size: 0.65em;
			padding: 3px;
		}

		tr.list_row:hover td {
			background-color: #F8EEE4;
		}

		tr.problem_row>td {
			background-color: #FDCCCC;
			border-bottom: 1px dotted #8B7958;
			font-size: 0.65em;
			padding: 3px;
		}

		tr.problem_row:hover td {
			background-color: #F8EEE4;
		}

		.row_form td {
			font-size: 0.7em;
			padding: 3px;
			white-space: nowrap;
			/*  text-align: center; */
		}

		.row_form td.label {
			text-align: left;
			white-space: normal;
		}

		.inline_header td {
			color: #8B7958;
			font-size: 0.6em;
			white-space: nowrap;
			text-align: center;
		}

		/* Sub-Tables
        -----------------------------------------------------------------------*/
		.sub_table {
			border-spacing: 0px;
		}

		.sub_table tr.head td {
			font-size: 11px;
			padding: 3px;
			background-color: #F9F0E9;
		}

		.sub_table td {
			padding: 3px;
		}

		/* Reports
        -----------------------------------------------------------------------*/
		.report {
			border-collapse: collapse;
			border-spacing: 0px;
			border-top: 1px solid #8B7958;
			border-bottom: 1px solid #8B7958;
			width: 80%;
			margin-top: 3px;
		}

		.report tr td {
			padding: 4px 6px;
		}

		.report tr.head td {
			font-size: 0.7em;
			white-space: nowrap;
			text-align: center;
			border-bottom: 1px solid #8B7958;
		}

		.report tr.foot td {
			font-size: 0.7em;
			border-top: 1px solid #8B7958;
		}

		.report tr.list_row>td {
			background-color: #EDF2F7;
			border-bottom: 1px dotted #8B7958;
			font-size: 0.65em;
		}

		.report tr.list_row:hover td {
			background-color: #F8EEE4;
		}

		.report td.total_col {
			font-weight: bold;
			border-left: 1px dotted #8B7958;
			text-align: center;
			width: 10%;
		}

		.report tr.head td.group_col {
			text-align: left;
		}

		.report td.group_col {
			font-weight: bold;
			text-align: left;
			border-right: 1px dotted #8B7958;
			width: 12%;
		}

		.graph {
			width: 80%;
			margin-top: 2em;
			margin-bottom: 3em;
			text-align: center;
		}


		/* Notifications
        -----------------------------------------------------------------------*/
		.notification_list {
			border-collapse: collapse;
			border-spacing: 0px;
			border-top: 1px solid #8B7958;
			border-bottom: 1px solid #8B7958;
			width: 99%;
		}

		.notification_list tr.head td {
			font-size: 0.65em;
			white-space: nowrap;
			text-align: center;
			border-bottom: 1px solid #8B7958;
		}

		.notification_list tr.foot td {
			border-top: 1px solid #8B7958;
		}

		.notification_list tr.list_row td {
			padding: 7px;
		}

		div.notif_list_text {
			margin-bottom: 1px;
			font-size: 1.1em;
		}

		.list_row>td.notif_list_job {
			white-space: nowrap;
			text-align: center;
			font-weight: bold;
			font-size: 0.65em;
			white-space: nowrap;
		}

		/* Some of the system messages are long and look bad with a highlighted
        background... */
		#system_notif_table tr.list_row:hover > td {
			background-color: #EDF2F7;
		}

		.notif_select_column {
			width: 2%;
			padding: 0px;
			text-align: center;
		}

		.notif_job_column {
			width: 8%;
			white-space: nowrap;
			padding-left: 0px;
			font-weight: bold;
			text-align: center;
		}

		.notif_notif_column {
			width: auto;
		}

		.notif_date_column {
			width: 15%;
			text-align: center;
			white-space: nowrap;
			padding-right: 3px;
		}



		/* Notes
        -----------------------------------------------------------------------*/
		/* Note Table */
		table#topic_list {
			border-bottom: 1px solid #E5D9C3;
			border-collapse: separate;
		}

		/* Note Form */
		.note_form {
			background-color: #F9F0E9;
			position: absolute;
			left: 20%;
			display: none;
			border: 2px solid #114C8D;
		}

		.note_form table.form {
			margin-top: 2em;
		}

		.handle {
			background-color: #114C8D;
			color: #FFFFff;
			margin-bottom: 3px;
			height: 16px;
		}

		.note_form_close {
			font-weight: bold;
			font-size: 9px;
			padding: 0px 2px 0px 2px;
			margin-right: 2px;
			position: absolute;
			right: 0%;
			border: 1px solid #114C8D;
		}

		a.note_form_close:hover {
			text-decoration: none;
		}

		.list_row:hover>td table.add_note tr.add_note_foot td,
		.list_row:hover>td table.add_note tr.add_note_head td { background-color: #E5D9C3; }
		.list_row:hover>td table.add_note tr td { background-color: #F9F0E9; }

		.add_note td {
			border: none;
			padding: 3px;
			background-color: #F9F0E9;
			font-size: 9px;
		}

		.add_note_head td {
			background-color: #E5D9C3;
			border-top: 1px solid #8B7958;
			border-bottom: 1px solid #8B7958;
			color: #8B7958;
			padding: 3px;
			text-align: center;
			font-weight: bold;
			font-size: 9px;
		}

		.add_note input {
			color: #114C8D;
			background-color: #FFFFff;
			border: 1px solid #114C8D;
			padding: 1px 2px 1px 2px;
			text-decoration: none;
			font-size: 9px;
		}

		.add_note textarea {
			color: #114C8D;
			background-color: #FFFFff;
			border: 1px solid #114C8D;
			padding: 1px 2px 1px 2px;
			font-family: "verdana", "sans-serif";
			font-size: 9px;
		}

		.add_note select   {
			color: #114C8D;
			background-color: #FFFFff;
			font-size: 9px;
		}

		.add_note_foot td {
			background-color: #E5D9C3;
			border-bottom: 1px solid #8B7958;
			color: #8B7958;
			padding: 3px;
			text-align: center;
			font-weight: bold;
			font-size: 9px;
		}

		/* Note List */
		.note>td {
			background-color: #EDF2F7;
			padding-left: 10px;
			border-bottom: 1px dotted #E5D9C3;
		}

		.note:hover>td,
		.note:hover>td>p {
			background-color: #EDF2F7;
		}

		.note_author {
			font-size: 0.65em;
			text-align: center;
			border-right: 1px dotted #E5D9C3;
		}

		.note p {
			margin-left: 3%;
			font-size: 0.75em;
			background-color: #EDF2F7;
		}

		.topic_spacer td {
			line-height: 2px;
		}

		td.note_indent {
			background-color: #F9F0E9;
			width: 2%;
			border-bottom: none;
		}

		.note_control td {
			padding-left: 2%;
			padding-bottom: 1%;
			font-weight: normal;
			font-size: 0.6em;
			background-color: #EDF2F7;
		}

		.topic_title {
			font-size: 0.8em;
			font-weight: bold;
		}

		.note_title {
			font-size: 0.8em;
		}

		.problem .topic_title {
			color: #860000;
		}

		.thread>tr { display: none; }

		/* Summaries
        -----------------------------------------------------------------------*/
		.summary {
			border: 1px solid black;
			background-color: white;
			padding: 1%;
			font-size: 0.8em;
		}

		.summary h1 {
			color: black;
			font-style: normal;
		}

		/* Print preview
        -----------------------------------------------------------------------*/
		.page {
			/*background-color: white;*/
			/*padding: 0px;*/
			/*!*  font-size: 0.7em; *!*/
			/*width: 95%;*/
			/*margin-bottom: 15px;*/
			/*margin-right: 5px;*/
			/*padding: 20px;*/
		}

		.page table.header td {
			padding: 0px;
		}

		.page table.header td h1 {
			padding: 0px;
			margin: 0px;
		}

		.page h1 {
			color: black;
			font-style: normal;
			font-size: 1.3em;
		}

		.page h2 {
			color: black;
		}

		.page h3 {
			color: black;
			font-size: 1em;
		}

		.page p {
			text-align: justify;
			font-size: 0.8em;
		}

		.page table {
			font-size: 0.8em;
		}

		.page em {
			font-weight: bold;
			font-style: normal;
			text-decoration: underline;
			margin-left: 1%;
			margin-right: 1%;
		}

		.page table.money_table {
			font-size: 1.1em;
			border-collapse: collapse;
			width: 85%;
			margin-left: auto;
			margin-right: auto;
		}

		.page table.money_table tr.foot td {
			font-size: 1em;
			font-weight: bold;
			background-color: white;
			color: black;
		}

		.page table.money_table tr.foot td.right {
			padding-right: 1px;
		}

		.written_field {
			border-bottom: 1px solid black;
		}

		.page .written_field {
			border-bottom: 0.4pt solid black;
		}

		.page .indent * { margin-left: 4em; }

		.checkbox {
			border: 1px solid black;
			padding: 1px 2px;
			font-size: 7px;
			font-weight: bold;
		}


		table.signature_table {
			width: 80%;
			font-size: 0.7em;
			margin: 2em auto 2em auto;
		}

		table.signature_table tr td {
			padding-top: 1.5em;
			vertical-align: top;
			white-space: nowrap;
		}

		#special_conditions {
			font-size: 1.3em;
			font-style: italic;
			margin-left: 2em;
			font-weight: bold;
		}

		.sa_head p {
			font-size: 1em;
		}


		.page hr {
		}

		.page table.detail,
		.page table.fax_head {
			margin-left: auto;
			margin-right: auto;
		}

		.page .narrow,
		.page .fax_head {
			border: none;
		}

		.page tr.head td {
			color: black;
			background-color: #eee;
		}

		.page td.label {
			color: black;
			background-color: white;
			width: 20%;
		}

		.page td.label_right {
			color: black;
			background-color: white;
		}

		.page td.field {
			background-color: white;
			font-weight: bold;
		}

		.page td.field_money {
			background-color: white;
		}

		.page td.field_total {
			font-weight: bold;
			background-color: white;
		}

		.page tr.detail_spacer_row td {
			background-color: white;
		}

		.page .header {
			border-spacing: 0px;
			border-collapse: collapse;
			padding: 0px;
		}

		.page .header tr td {
			border-top: 1px solid black;
			background-color: #eee;
		}
		/* Style definitions for printable pages */


		/* Hide non-printing stuff
        -----------------------------------------------------------------------*/
		#page_header,
		#main_menu,
		#right_column,
		#footer {
			display: none;
		}

		/* General
        -----------------------------------------------------------------------*/

		body {
			background-color: white;
			color: black;
		}

		h1 {
			color: black;
		}

		h2 {
			color: black;
		}

		pre {
			color: black;
		}

		ul {
			color: black;
		}

		a:link,
		a:visited {
			color: black;
		}

		a:hover {
			text-decoration: none;
			color: black;
		}

		p a {
			display: none;
		}

		#body {
			background-color: white;
		}

		#body pre {
			color: black;
		}

		/* Inputs
        -----------------------------------------------------------------------*/


		a.button {
			display: none;
		}

		a.block_button {
			display: none;
		}

		input[type=button],
		input[type=submit],
		input[type=reset] {
			display: none;
		}

		/* Tooltips
        -----------------------------------------------------------------------*/
		.tooltip {
			display: none;
		}

		/* Message area
        -----------------------------------------------------------------------*/
		#message_area {
			display: none;
		}

		/* Section Header
        -----------------------------------------------------------------------*/
		#section_header {
			background-color: #ddd;
		}

		/* Content
        -----------------------------------------------------------------------*/
		.page_buttons {
			display: none;
		}

		.link_bar {
			display: none;
		}

		/* Tables
        -----------------------------------------------------------------------*/
		.head td {
			color: black;
			background-color: white;
		}

		.head input {
		}

		.foot td {
			color: black;
			background-color: white;
		}

		.label {
			color: black;
			background-color: white;
		}

		.sublabel {
			color: black;
		}

		.field {
			color: black;
			background-color: white;
		}

		.field_center {
			color: black;
			background-color: white;
		}

		.field_nw {
			color: black;
			background-color: white;
		}

		.field_money {
			color: black;
			background-color: white;
		}

		.field_total {
			color: black;
			background-color: white;
		}

		/* Detail
        -----------------------------------------------------------------------*/

		.detail td.label {
			background-color: white;
		}

		.detail td.field_total,
		.detail td.field {
			font-weight: bold;
			background-color: #eee;
		}

		.detail td.field_money {
			background-color: #eee;
		}

		.detail_spacer_row td {
			background-color: white;
		}

		.narrow td.label {
			background-color: white;
		}

		.narrow td.field {
			background-color: #eee;
		}

		-----------------------------------------------------------------------*/


		.list tr.head>td {
		}
		.list tr.foot td {
		}

		tr.list_row>td {
			background-color: white;
		}

		tr.list_row:hover td {
			background-color: white;
		}

		/* Notes
        -----------------------------------------------------------------------*/
		/* Note Table */
		table#topic_list {
			border-bottom: 1px solid #eee;
		}

		/* Note Form */
		.note_form {
			display: none;
		}

		/* Note List */
		.note>td {
			background-color: white
			border-bottom: 1px dotted #eee;
		}

		.note:hover>td,
		.note:hover>td>p {
			background-color: white;
		}

		.note_author {
			border-right: 1px dotted #eee;
		}

		.note td {
			background-color: white;
		}

		.note p {
			background-color: white;
		}

		.topic_spacer td {
			border-bottom: 1px solid black;
		}

		td.note_indent {
			background-color: white;
		}

		.note_control td {
			background-color: white;
			border-bottom: 1px dotted #eee;
		}

		.problem .topic_title {
			color: black;
		}

		/* Summaries
        -----------------------------------------------------------------------*/
		.summary {
			background-color: white;
		}

		.summary h1 {
			color: black;
		}

		/* Pages
        -----------------------------------------------------------------------*/
		.page>*>p, .page>p {
			font-size: 1.5em;
		}

		.written_field {
			font-size: 1em;
			border-bottom: 1px solid black;
		}

		.page h1 {
			font-size: 1em;
		}

		.page h2 {
			font-size: 0.9em;
		}

		/*@page {*/
		/*margin-bottom: 0.75in;*/
		/*}*/
		/* General
        -----------------------------------------------------------------------*/
		body { background-color: white; }

		/* Detail
        -----------------------------------------------------------------------*/

		.narrow td.field,
		.detail td.field {
			text-align: left;
			padding-left: 1em;
			background-color: white;
		}

		/* Lists
        -----------------------------------------------------------------------*/
		.list tr.head td {
		}

		tr.list_row>td {
			background-color: white;
		}

		.list tr.foot td {
			background-color: #eee;
		}

		/* Pages
        -----------------------------------------------------------------------*/
		.page {
			font-size: 1em;
			border: none;
			margin: none;
			width: auto;
			padding: 0px;
		}

		.foot td {
			font-size: 1em;
		}


		.page>*>p, .page>p {
			font-size: 0.8em;
		}

		table.signature_table {
			width: 88%;
			font-size: 0.6em;
		}

		#special_conditions {
			font-size: 1.5em;
		}

		.header h1 {
			font-size: 0.8em;
		}

		p.small {
			font-size: 0.8em;
		}

		.page td {
			padding: 1px;
		}

		td.label {
			font-size: 0.7em;
		}

		td.field {
			font-size: 0.7em;
		}

		td.field_money {
			font-size: 0.7em;
		}

		#footer_spacer_row td {
			padding: 0px;
			border-bottom: 1px solid #000033;
			background-color: #F7CF07;
			height: 2px;
			font-size: 2px;
			line-height: 2px;
		}
		.footertwo p:first-child
		{
			color: #F3F3F3;
			width: 80%;
			margin: 90px auto 0 auto;
		}
		.footertwo p span
		{
			color: #F3F3F3 !important;
		}

		h1,h2,h3,h4,h5,h6 {
			color: #1d2590;
		}

		body {
			color: #000033;
			font-family: "verdana", "sans-serif";
			margin: 0px;
			padding-top: 0px;
			font-size: 0.8em;
			/*position: relative;*/
		}

		body{
			margin: 300px 0 300px 0;
		}

		.header-lead {
			max-width: 100%;
			width: 100%;
			top: 0;
			right: 0;
			left: 0;
			position: fixed;
			z-index: -1000;
		}
		.header-lead img {
			z-index: 235px;
		}
		footer, .footer-last {
			max-width: 100%;
			width: 1009px;
			position: fixed;
			/*left: 0;*/
			z-index: -1000;
			bottom: 190px;
			left: 0;
			right: 0;
		}

		.content-block {
			page-break-inside: avoid;
		}

		.content {
			margin: 0 10%;
			font-size: large;
		}

		.logo-text {
			margin-left: 84px;
			color: #f3f3f3;
			font-size: 21px;
			height: 300px;
			position: relative;
			top: -245px;
			z-index: 1414;

		}
		.iban-header {
			margin-left: 5px;
			position: relative;
			top: -465px;
			z-index: 1412;
			color: #f3f3f3;
		}
	</style>
</head>
<body>


<div class="header-lead">
	<img src="{{ public_path() .'/pdf/pdf-header.jpg' }}" width="100%" alt="header">
	<div class="logo-text">
		<span class="logo-top-text">Uw</span> <br>
		<span class="logo-top-text">Voordeel</span>
	</div>

	<table class="iban-header">
		<tr>
			<td style="padding-right: 20px">IBAN</td>
			<td>NL91 ABNA 0417 1643 00</td>
		</tr>
		<tr>
			<td style="padding-right: 20px">BTW</td>
			<td>855755659B01</td>
		</tr>
		<tr>
			<td style="padding-right: 20px">KVK</td>
			<td>0417 1643 00</td>
		</tr>
	</table>
</div>


<div class="content">

{!! isset($contentBlock[45]) ? $contentBlock[45] : '' !!}

	<div style="page-break-before:always">&nbsp;</div>
	<table>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td colspan="2" class="colHead">Bankgegevens t.b.v. automatisch incasso:</td>
		</tr>
		<tr>
			<td style="width: 300px;">IBAN:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $contactIBAN }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Ter name van:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $contactIBAName }}</td>
		</tr>
		<tr>
			<td colspan="2" class="colHead">Voor akkoord automatisch incasso:</td>
		</tr>
		<tr>
			<td style="width: 300px;">Datum:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyDate }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Bedrijfsnaam:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyName }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Naam tekenbevoegd persoon:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $contactName }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Functie:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $contactRole }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Handtekening:</td>
			<td>
				@if ($companySignature)
				<img  style="width: 200px;" src="data:{{ $companySignature }}">
				@endif
			</td>
		</tr>
		<tr>
			<td colspan="2" class="colHead">Websitegegevens:</td>
		</tr>
		<tr>
			<td style="width: 300px;">KVK:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyKVK }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">BTW:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyBTW }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Adres:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyAddress }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Postcode / plaats:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyZipcode.' '.$companyCity }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">E-mail reserveringen:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyEmailReservation }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">E-mail administratie:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $companyEmailAdmin }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Mobiel telefoonnummer:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $contactMobile }}</td>
		</tr>
		<tr>
			<td style="width: 300px;">Vast telefoonnummer:</td>
			<td style="border-bottom: 1px dotted #000;">{{ $contactPhone }}</td>
		</tr>
	</table>
   	
	<div class="footertwo">
   		{!! isset($contentBlock[46]) ? $contentBlock[46] : '' !!}
    </div>
    
   	{{--<div style="page-break-before:always">&nbsp;</div> --}}
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
		<h4>Algemene Voorwaarden</h4>
		{!! isset($contentBlock[43]) ? $contentBlock[43].'<br /><br />' : '' !!}

		<table>
			<tr>
				<td><span style="border-bottom: 1px dotted #000; padding-right: 20px; font-size: 16px;">{{ $contactName }}</span></td>
				<td><span style="border-bottom: 1px dotted #000; padding-right: 20px; font-size: 16px;">{{ $companyDate }}</span></td>
				<td>
					@if ($companySignature)
					<span style="border-bottom: 1px dotted #000; padding-right: 20px; font-size: 16px;"><img style="width: 200px;" src="data:{{ $companySignature }}"></span>
					@endif
				</td>
			</tr>
			<tr>
				<td style="width: 250px; font-weight: bold;">Naam contactpersoon:</td>
				<td style="width: 250px; font-weight: bold;">Datum:</td>
				<td style="width: 25px; font-weight: bold;">Handtekening:</td>
			</tr>
		</table>



	</div>
	<img src="{{ public_path() . '/pdf/pdf-footer.jpg' }}" alt="footer-img" class="footer-last">
	</body>
	</html>