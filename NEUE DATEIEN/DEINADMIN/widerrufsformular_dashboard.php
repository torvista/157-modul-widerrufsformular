<?php
/**
* Widerrufsformular für Zen Cart 1.5.7 deutsch
* @copyright Copyright 2026 webchills (www.webchills.at)
* @copyright Copyright 2003-2026 Zen Cart Development Team
* Zen Cart German Version - www.zen-cart-pro.at
* @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
* @version $Id: widerrufsformular_dashboard.php 2026-03-08 08:39:40Z webchills $
*/
	require('includes/application_top.php');
	
	require(DIR_WS_CLASSES . 'widerrufsformular_dashboard.php');
	$highest_page_number ='';
	$cf = new widerrufsformular_dashboard();
	/* DATABASE OPERATIONS */
	switch ($cf->GetAction()) {
		case 'updateRequest':
			$cf->UpdateRequest($_POST);
			break;
		case 'deleteRequestConfirmed':
			$cf->DeleteRequest();
			break;
		default:
			break;
	}
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
  <head>
    <?php require DIR_WS_INCLUDES . 'admin_html_head.php'; ?>
</head>
<body>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<div class="container-fluid">
<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
		<td width="100%" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
					<td class="pageHeading"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
					<td class="pageHeading" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top">
<?php
	#GET NUMBER OF ROWS
	$number_of_rows = 10; #DEFAULT/MIN NUMBER OF ROWS
	if( (int)WIDERRUFSFORMULAR_NUMBER_ROWS > 10 ){
		$number_of_rows = WIDERRUFSFORMULAR_NUMBER_ROWS; #CONFIGURATION
	}
	#GET PARAMETERS
	$parameters = zen_get_all_get_params(array('action','rID','page'));
	#LOAD DATA
	if( $sniffer->table_exists( TABLE_WIDERRUFSFORMULAR_REQUESTS ) ){
		$sql = "SELECT `request_id`, `customer_name`, `order_id`, `customer_email`, `status`, `message_timestamp`
				 FROM `" . TABLE_WIDERRUFSFORMULAR_REQUESTS . "`";
		$rec = $db->Execute($sql);
		if(!$rec->EOF){
			$total_records = $rec->RecordCount();
			$pages_split = new splitPageResults( $_GET['page'], $number_of_rows, $sql, $total_records );
			$rec = $db->Execute($sql);
			echo $pages_split->display_count($total_records, $number_of_rows, (int)$_GET['page'], PAGINATION_LABEL);
?>
						<br /><br />
						<table border="0" width="100%" cellspacing="0" cellpadding="2">
							<tr class="dataTableHeadingRow">
								<td class="dataTableHeadingContent"><?php echo TBL_HEAD_REQUEST_ID; ?></td>								
								<td class="dataTableHeadingContent"><?php echo TBL_HEAD_NAME; ?></td>
								<td class="dataTableHeadingContent"><?php echo TBL_HEAD_ORDER_NUMBER; ?></td>									
								<td class="dataTableHeadingContent"><?php echo TBL_HEAD_EMAIL; ?></td>								
								<td class="dataTableHeadingContent"><?php echo TBL_HEAD_TIMESTAMP; ?></td>
								<td class="dataTableHeadingContent"><?php echo TBL_HEAD_STATUS; ?></td>
								<td class="dataTableHeadingContent"><?php echo TBL_HEAD_ACTION; ?></td>
							</tr>
<?php
			while( !$rec->EOF ){
				if( $cf->GetRequestId() == 0 ){
					$cf->InitVars( $rec->fields['request_id'] );
				}
				$tr_class = 'dataTableRow';
				if( $cf->GetRequestId() == $rec->fields['request_id'] ){
					$tr_class = 'dataTableRowSelected';
				}
?>
							<tr class="<?php echo $tr_class; ?>" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo zen_href_link(FILENAME_WIDERRUFSFORMULAR_DASHBOARD, zen_get_all_get_params(array('action','rID')) . 'rID=' . $rec->fields['request_id'] ); ?>'" \>
								<td class="dataTableContent"><?php echo $rec->fields['request_id']; ?></td>
								<td class="dataTableContent"><?php echo $rec->fields['customer_name']; ?></td>
								<td class="dataTableContent"><a href="index.php?cmd=orders&oID=<?php echo $rec->fields['order_id']; ?>&action=edit"><?php echo $rec->fields['order_id']; ?></a></td>								
								<td class="dataTableContent"><?php echo $rec->fields['customer_email']; ?></td>	
								<td class="dataTableContent"><?php echo date("d.m.Y H:i", strtotime($rec->fields['message_timestamp'])); ?></td>
								<td class="dataTableContent"><?php echo $rec->fields['status']; ?></td>
								<td class="dataTableContent" width="90"><?php
	echo ' <a href="' . $cf->ActionUrl('editRequest', $rec->fields['request_id']) . '">' .
				zen_image(DIR_WS_IMAGES . 'icon_edit.gif', ICON_EDIT) . 
			'</a>' . 
			' <a href="'.$cf->ActionUrl('deleteRequest', $rec->fields['request_id']).'">' .
			zen_image(DIR_WS_IMAGES . 'icon_delete.gif', ICON_DELETE) . '</a>' . 
			( $cf->GetRequestId() == $rec->fields['request_id'] ? zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '', '', '', 'style="margin-left:10px;"') : zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO, '', '', 'style="margin-left:10px;"') );
?>
								</td>
							</tr>
<?php
				$rec->MoveNext();
			}
?>
							<tr>
								<td colspan=9>&nbsp;</td>
							</tr>
							<tr>
								<td colspan=9 align=center><?php echo $pages_split->display_links($total_records, $number_of_rows, $highest_page_number, $_GET['page'], $parameters); ?></td>
							</tr>
						</table>
<?php
		}else{
			echo '<h1>' . INFO_NO_REQUEST . '</h1>' . "\n";
		}
	}else{
		echo '<h1>' . MSG_INSTALL_ERROR . '</h1>' . "\n";
	}
?>
					</td>
<?php
	/* RIGHT-SIDE INFO BOX */
	$heading = array();
	$contents = array();
	switch ($cf->GetAction()) {
		/***************************
		 ********** FORMS **********
		 ***************************/
		
		/********** DELETE **********/
		case 'deleteRequest':
			$heading[] = array(
				'text' => '<strong>' . INFO_HEAD_DELETE_REQUEST . '</strong>'
			);
			$contents = $cf->GetForm('deleteRequestConfirmed', $cf->GetRequestId());
			
			$contents[] = array('text' => '<p>' . INFO_DELETE_REQUEST_CONFIRM . '</p>');
			
			#LOAD FORM INFO DISPLAY
			if( $cf->GetRequestId() > 0 ){
				#LOAD FORM INFO DISPLAY
				$content = $cf->GetDisplayRequest( $cf->GetRequestId() );
				if( count($content) > 0 ){
					foreach($content as $c){
						$contents[] = $c;
					}
				}
			}
			
			$contents[] = array(
				'align' => 'center', 
				'text' => '<br />' . 
					zen_image_submit('button_delete.gif', IMAGE_DELETE) . 
					' <a href="' . $cf->ActionUrl('', $cf->GetRequestId()) . '">' . 
						zen_image_button('button_cancel.gif', IMAGE_CANCEL) . 
					'</a>');
			break;
		
		/********** EDIT **********/
		case 'editRequest':
			$heading[] = array('text' => '<strong>' . INFO_EDIT_REQUEST . '</strong>');
			$contents = $cf->GetForm('updateRequest', $cf->GetRequestId());
			
			#SHOW CUSTOMER REQUEST
			$rInfo = $cf->GetDisplayRequest();
			if( is_array($rInfo) ){
				foreach( $rInfo as $info ){
					$contents[] = $info;
				}
			}
			
			$contents[] = array(
				'text' => zen_draw_label(
						INFO_EDIT_REQUEST . ": ", 
						'cbxStatus', 
						'class="info-label-head"'
					)
			);
			$values = $cf->GetAvailableStatus();
			$contents[] = array(
				'text' => zen_draw_label(
						INFO_FORM_STATUS_LABEL . ": ", 
						'cbxStatus', 
						'class="info-labels"'
					) . 
				zen_draw_pull_down_menu('cbxStatus', $values, $cf->GetStatus())
			);
			
			$contents[] = array(
				'align' => 'center', 
				'text' => '<br />' . 
				zen_image_submit('button_update.gif', IMAGE_UPDATE) . 
				' <a href="' . $cf->ActionUrl('', $cf->GetRequestId()) . '">' . 
					zen_image_button('button_cancel.gif', IMAGE_CANCEL) . 
				'</a>'
			);
			break;
		default:
			if( $cf->GetRequestId() > 0 ){
				#NO ACTION BUT FORM SELECTED
				#SHOW FORM INFO
				$heading[] = array('text' => '<strong>' . INFO_HEAD_DISPLAY_REQUEST . '</strong>');
				
				$contents[] = array(
					'align' => 'center', 
					'text' => '
						<button type="button" class="btn btn-success btn-sm" aria-label="Left Align" onClick="window.location=\'' . $cf->ActionUrl('editRequest', $cf->GetRequestId()) . '\'">
							<span class="glyphicon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
							' . BTN_EDIT . '
						</button>
						
						<button type="button" class="btn btn-danger btn-sm" aria-label="Left Align" onClick="window.location=\'' . $cf->ActionUrl('deleteRequest', $cf->GetRequestId()) . '\'">
							<span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span>
							' . BTN_DELETE . '
						</button>'
				);
				#SHOW CUSTOMER REQUEST
				$rInfo = $cf->GetDisplayRequest();
				if( is_array($rInfo) ){
					foreach( $rInfo as $info ){
						$contents[] = $info;
					}
				}
			}else{
				#NO FIELD AVAILABLE: SHOW ADD BUTTON
				$contents[] = array(
						'text' => zen_draw_label( INFO_HEAD_FIELDS_SECTION_TITLE,
						'',
						'class="info-label-head"') . 
						zen_image_button(
							'button_add.gif', 
							BTN_ADD,
							'style="float:right;" onClick="window.location=\'?action=addField&fID=' . $cf->GetRequestId() . '\'" class="btn"'
						)
					);
			}
			break;
	}
?>
					<td class="noprint" valign="top" style="width:25%; min-width:200px;">
<?php
	if ( (zen_not_null($heading)) && (zen_not_null($contents)) ) {
		$box = new box;
		echo $box->infoBox($heading, $contents);
	}else{
		echo INFO_NO_REQUEST;
	}
?>
					</td>
				</tr>
			</table>
		</td>	
	</tr>
</table>
</div>
<?php require DIR_WS_INCLUDES . 'footer.php'; ?>
</body>
</html>
<?php require DIR_WS_INCLUDES . 'application_bottom.php'; ?>