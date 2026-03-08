<?php
/**
* Widerrufsformular für Zen Cart 1.5.7 deutsch
* @copyright Copyright 2026 webchills (www.webchills.at)
* @copyright Copyright 2003-2026 Zen Cart Development Team
* Zen Cart German Version - www.zen-cart-pro.at
* @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
* @version $Id: widerrufsformular_dashboard.php 2026-03-08 09:39:40Z webchills $
*/
class widerrufsformular_dashboard{
	/* FORM RELATED INSTANCE VARIABLES */
	private $action = '';
	private $request_id = 0;
	private $form_id = 0;
	private $order_id = 0;
	private $customer_name = '';	
	private $customer_email = '';	
	private $message = '';
	private $status = '';
	private $message_timestamp = '';
	private $images_url = '';
	
	function __construct(){
		$this->InitVars();
	}
	
	/* PUBLIC METHODS */
	public function InitVars( $rID = 0 ){
		global $db;
		#FORM ID
		if( (int)$rID > 0 ){
			$this->SetRequestId( (int)$rID );
		}else if( isset($_GET['rID']) and (int)$_GET['rID'] > 0 ){
			$this->SetRequestId( (int)$_GET['rID'] );
		}else if( isset($_POST['rID']) and (int)$_POST['rID'] > 0 ){
			$this->SetRequestId( (int)$_POST['rID'] );
		}
		#ACTION
		if( isset($_GET['action']) ){
			$this->SetAction( $_GET['action'] );
		}else if( isset($_POST['action']) ){
			$this->SetAction( $_POST['action'] );
		}
		
		#LOAD VALUES FROM DATABASE
		if( $this->GetRequestId() > 0 ){
			$sql = "SELECT `form_id`,
			      `order_id`,
						`customer_name`, 	
						`customer_email`,
						`message`, 
						`status`, 
						`message_timestamp`
					FROM `" . TABLE_WIDERRUFSFORMULAR_REQUESTS . "`
					WHERE `request_id` = :requestID";
			$sql = $db->BindVars($sql, ':requestID', $this->GetRequestId(), 'integer');
			
			$rec = $db->Execute($sql);
			if( !$rec->EOF ){
				$this->SetFormId( $rec->fields['form_id'] );
				$this->SetOrderNumber( $rec->fields['order_id'] );			
				$this->SetCustomerName( $rec->fields['customer_name'] );				
				$this->SetCustomerEmail( $rec->fields['customer_email'] );							
				$this->SetMessage( $rec->fields['message'] );
				$this->SetStatus( $rec->fields['status'] );
				$this->SetMessageTimestamp( $rec->fields['message_timestamp'] );
			}
		}
		
		
	}
	
	public function GetForm( $action = '', $rID = 0, $ffID = 0, $oID = 0 ){
		/* BASED ON ACTION and REQUEST ID
		 * RETURN A FORMATTED ARRAY READY FOR THE CONTENTS[] RIGHT-BOX LOADER.
		 */
		$form = '';
		$form = zen_draw_form(
				'frmCustomForm', 
				FILENAME_WIDERRUFSFORMULAR_DASHBOARD, 
				zen_get_all_get_params(array('action', 'rID'))
			);
		
		if( $action != '' ){
			$form .= zen_draw_hidden_field( 'action', $action );
		}
		
		if( (int)$rID > 0 ){
			$form .= zen_draw_hidden_field( 'rID', (int)$rID );
		}
			
		return array('form' => $form);
	}
	
	public function GetDisplayRequest(){
		$contents = array();
		#ID
		$contents[] = array('text' => zen_draw_label(
				TBL_HEAD_REQUEST_ID . ': ',
				'',
				'class="info-labels"'
			) . $this->GetRequestId()
		);
		#NAME
		$contents[] = array('text' => zen_draw_label(
				TBL_HEAD_NAME . ': ',
				'',
				'class="info-labels"'
			) . $this->GetCustomerName()
		);
		
		#ORDERNUMBER
		$contents[] = array('text' => zen_draw_label(
				TBL_HEAD_ORDER_NUMBER . ': ',
				'',
				'class="info-labels"'
			) . $this->GetOrderNumber()
		);
		
		
		#EMAIL
		$contents[] = array('text' => zen_draw_label(
				TBL_HEAD_EMAIL . ': ',
				'',
				'class="info-labels"'
			) . $this->GetCustomerEmail()
		);		
		
		#REQUEST STATUS
		$contents[] = array('text' => zen_draw_label(
				TBL_HEAD_STATUS . ': ',
				'',
				'class="info-labels"'
			) . $this->GetStatus()
		);
		#REQUEST DATE/TIME
		$contents[] = array('text' => zen_draw_label(
				TBL_HEAD_TIMESTAMP . ': ',
				'',
				'class="info-labels"'
			) . $this->GetMessageTimestamp()
		);
		#MESSAGE
		$contents[] = array('text' => zen_draw_label(
				TBL_HEAD_MESSAGE . ': ',
				'',
				'class="info-label-head"'
			) . $this->GetMessage()
		);

		return $contents;
	}
	
	public function GetAvailableStatus(){
		$output = array();
		$status = array('', 'erhalten', 'in Arbeit', 'erledigt');
		foreach($status as $s){
			$output[] = array(
				'id' => $s,
				'text' => $s
			);
		}
		return $output;
	}
	public function ActionUrl( $action = '', $rID = 0 ){
		/* BASED ON ACTION AND REQUEST ID
		 * RETURN A FORMATTED URL
		 */
		
		$pars = zen_get_all_get_params(array('action', 'rID'));
		
		if( $action != '' ){
			$pars = 'action=' . $action;
		}
		
		if( (int)$rID  > 0 ){
			$pars .= ( $pars != '' ? '&' : '' );
			$pars .= 'rID='  . $rID;
		}
			
		$url = zen_href_link(
				FILENAME_WIDERRUFSFORMULAR_DASHBOARD, 
				$pars,
				'SSL'
			);
		
		return $url;
	}
	
	public function ProcessMessage( $msg ){
		/* CONVERT JSON BACK INTO A READABLE MESSAGE
		 */
		$message = '';
		$mArray = json_decode($msg);
		
		
		if( is_array($mArray) ){
			foreach( $mArray as $m ){
				$mInfo = get_object_vars($m);
				foreach($mInfo as $label => $value){
					if( is_array($value) ){
						$tmp = $value;
						$value = '<ul>';
						foreach($tmp as $v){
							$value .= '<li>' . $this->GetTextFromOptionValue( $v ) . '</li>' . "\n";
						}
						$value .= '</ul>';
					}else{
						
							$value = $this->GetTextFromOptionValue( $value );
		
					}
					$message .= '<div class="myMessage">' . zen_draw_label(
							$label . ': ',
							'',
							'style="font-weight:bold;margin-right:10px;"'
						) . 
						str_replace(JSON_LINE_BREAK_PLACEHOLDER, '<br />', $value) . '</div>' . "\n";
				}
			}
		}
		
		return $message;
	}
	
	private function GetTextFromOptionValue( $optVal ){
		global $db;
		
		$optText = $optVal; #DEFAULT
		$sql = "SELECT f.`field_type`, fo.`field_text`
				FROM `" . TABLE_WIDERRUFSFORMULAR_FIELDS . "` AS f 
					JOIN `" . TABLE_WIDERRUFSFORMULAR_FIELDS_OPTIONS . "` AS fo
						ON f.`form_field_id` = fo.`form_field_id`
				WHERE f.`form_id` = :fID 
					AND fo.`field_value` = :fValue";
		$sql = $db->BindVars($sql, ':fID', $this->GetFormId(), 'integer');
		$sql = $db->BindVars($sql, ':fValue', $optVal, 'string');
		$rec = $db->Execute( $sql );
		if( !$rec->EOF ){
			if( in_array($rec->fields['field_type'], array('Dropdown', 'Radio', 'Checkbox') ) ){
				$optText = $rec->fields['field_text'];
			}
		}
		return $optText;
	}
	
	/********** DATABASE OPERATIONS **********/
	#UPDATE
	public function UpdateRequest( $post ){
		global $db, $messageStack;
		
		$error_message = '';
		if( !zen_not_null($post['cbxStatus']) ){
			$error_message = MSG_TITLE_REQUIRED_ERROR;
		}
		if( $error_message != '' ){
			$messageStack->add_session($error_message, 'error');
			zen_redirect( $this->ActionUrl() );
		}else{
			$sql = "UPDATE `" . TABLE_WIDERRUFSFORMULAR_REQUESTS . "`
					SET `status` = :status
					WHERE `request_id` = :requestID";
			$sql = $db->BindVars($sql, ':status', $post['cbxStatus'], 'string');
			$sql = $db->BindVars($sql, ':requestID', $post['rID'], 'integer');
			$success = $db->Execute($sql);			
			if( $success ){
				$messageStack->add_session(MSG_REQUEST_UPDATED, 'success');
				zen_redirect( $this->ActionUrl('', $this->request_id) );
			}else{ #ERROR
				$messageStack->add_session(MSG_REQUEST_NOT_UPDATED, 'error');
				zen_redirect( $this->ActionUrl('', $this->request_id) );
			}
		}
	} //END: UPDATE REQUEST METHOD
	
	#DELETE
	public function DeleteRequest(){
		global $db, $messageStack;
		$error_message = '';
		if( !zen_not_null( $this->GetRequestId() ) ){
			$error_message = MSG_MISSING_REQUEST_ID_ERROR;
		}
		if( $error_message != '' ){
			$messageStack->add_session($error_message, 'error');
			zen_redirect( $this->ActionUrl() );
		}else{
			$sql = "DELETE FROM `" . TABLE_WIDERRUFSFORMULAR_REQUESTS . "`
					WHERE `request_id` = :requestID";
			$sql = $db->BindVars($sql, ':requestID', $this->GetRequestId(), 'integer');
			$success = $db->Execute($sql);			
			if( $success ){
				$messageStack->add_session(MSG_REQUEST_DELETED, 'success');
				zen_redirect( $this->ActionUrl() );
			}else{ #ERROR
				$messageStack->add_session(MSG_REQUEST_NOT_DELETED, 'error');
				zen_redirect( $this->ActionUrl('', $this->request_id) );
			}
		}
	}
	
	/********** GETTER METHODS **********/
	public function GetRequestId(){
		return $this->request_id;
	}
	public function GetFormId(){
		return $this->form_id;
	}
	public function GetAction(){
		return $this->action;
	}
	public function GetCustomerName(){
		return $this->customer_name;
	}	
	public function GetOrderNumber(){
		return $this->order_id;
	}	
	
	public function GetCustomerEmail(){
		return $this->customer_email;
	}
	
	public function GetMessage(){
		return $this->ProcessMessage( $this->message );
	}
	public function GetStatus(){
		return $this->status;
	}
	public function GetMessageTimestamp(){
		return $this->message_timestamp;
	}
	
	/**********  SETTER METHODS **********/
	public function SetRequestId( $id ){
		$this->request_id = $id;
	}
	public function SetFormId( $id ){
		$this->form_id = $id;
	}
	public function SetAction( $action ){
		$this->action = $action;
	}
	public function SetOrderNumber( $order_id ){
		$this->order_id = $order_id;
	}	
	public function SetCustomerName( $name ){
		$this->customer_name = $name;
	}	
	public function SetCustomerEmail( $email ){
		$this->customer_email = $email;
	}

	public function SetMessage( $message ){
		$this->message = $message;
	}
	public function SetStatus( $status ){
		$this->status = $status;
	}
	public function SetMessageTimestamp( $timestamp ){
		$this->message_timestamp = $timestamp;
	}
} #END: CLASS
