<?php
/**
* Widerrufsformular für Zen Cart 1.5.7 deutsch
* @copyright Copyright 2026 webchills (www.webchills.at)
* @copyright Copyright 2003-2026 Zen Cart Development Team
* Zen Cart German Version - www.zen-cart-pro.at
* @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
* @version $Id: widerrufsformular.php 2026-03-09 18:24:40Z webchills $
*/
class widerrufsformular extends base{
	private $form_id = 0;
	private $order_id = 0;
	private $ordernumber = 0;
	private $form = '';
	private $action = '';
	private $required_fields = array();	
	private $main_page = '';
	private $page_url = '';	
	private $form_title = '';
	private $description = '';
	private $page_title = '';
	private $page_heading = '';
	private $navbar_title = '';		

	
	function __construct() {
		/* CALLED FROM THE PAGE'S HEADER FILE
		 */
		global $db;
		#LOAD INSTANCE VARIABLES
		if( isset($_GET['form_id']) and (int)$_GET['form_id'] > 0 ){
			$this->form_id = (int)$_GET['form_id'];
			$sql = "SELECT `form_title`, `page_title`, `page_heading`, `navbar_title`, `form_description`
					FROM `" . TABLE_WIDERRUFSFORMULAR . "`
					WHERE `form_id` = :formID";
			$sql = $db->BindVars($sql, ':formID', zen_db_prepare_input($this->form_id), 'integer');
			$rec = $db->Execute( $sql );
			if( !$rec->EOF ){
				$this->form_title = $rec->fields['form_title'];
				$this->page_title = $rec->fields['page_title'];
				$this->page_heading = $rec->fields['page_heading'];
				$this->navbar_title = $rec->fields['navbar_title'];
				$this->description = $rec->fields['form_description'];
			}
		}
		if( isset($_POST['action']) ){
			$this->action = $_POST['action'];
		}else if( isset($_GET['action']) ){
			$this->action = $_GET['action'];
		}
		
		#CURRENT PAGE'S URL (WITHOUT ACTION)
		if( isset($_GET['main_page']) ){
			$this->main_page = $_GET['main_page'];
			$this->page_url = 'index.php?main_page=' . $_GET['main_page'];
			$parameters = zen_get_all_get_params(array('action'));
			if( strlen($parameters) > 3 ){
				$this->page_url .= '&' . trim($parameters, '&');
			}
		}	
		
		#LOAD REQUIRED FIELDS ARRAY
		
			array_push( $this->required_fields, 'txtCustomerName' );		
		
		
			array_push( $this->required_fields, 'txtCustomerEmail' );
		
		
			array_push( $this->required_fields, 'txtOrderNumber' );
		
		$sql = "SELECT `field_name`
				FROM `" . TABLE_WIDERRUFSFORMULAR_FIELDS . "`
				WHERE `required` = 1
					AND`form_id` = :formID
				ORDER BY `sort_order` ASC";
		$sql = $db->BindVars($sql, ':formID', zen_db_prepare_input($this->form_id), 'integer');
		$rec = $db->Execute($sql);
		while( !$rec->EOF ){
			array_push( $this->required_fields, $rec->fields['field_name'] );
			$rec->MoveNext();
		}
		if( $this->action == 'send_request' ){
			$this->ProcessSubmitForm();
		}
		$this->LogFormHits();
		$this->form = $this->Widerrufsformular();
	}
	
	private function Widerrufsformular(){
		global $db, $messageStack;
		
		$output = '';
		$customer = array(
			'name'		=> '',		
			'email'		=> '',
			'orderid'	=> ''		
		);
		
		#PROCESS ACTION
		if( $this->action != '' ){
			switch( $this->action ){
				case 'request_confirmation':
					#SHOW REQUEST CONFIRMATION PAGE: NO FORM
					$output = $this->RequestConfirmationLoader();
					break;
				case 'send_request':
					#SHOW NOTHING, WILL REDIRECT: NO FORM
					break;
				case 'request_success':
					#SHOW SUCCESS PAGE: NO FORM
					$output  = '<h1>' . HEAD_SUCCESS . '</h1>' . "\n";
					$output .= $this->GetMessage( $this->main_page );
				
					$output .= '
			
		</form>' . "\n";
					break;
				default:
					#PRELOAD CUSTOMER ARRAY
					
					if( isset($_POST['txtCustomerName']) ){
						$customer['name'] = $_POST['txtCustomerName'];
					}
					if( isset($_POST['txtCustomerEmail']) ){
						$customer['email'] = $_POST['txtCustomerEmail'];
					}
					if( isset($_POST['txtOrderNumber']) ){
						$customer['ordernumber'] = $_POST['txtOrderNumber'];
					}
					#LOAD FORM
					$output = $this->FormLoader( $customer );
					break;
			}
		}else{ #ACTION NOT SET YET
			#PRE-LOAD CUSTOMER INFO IF POSSIBLE
			if( isset($_SESSION["customer_id"]) and (int)$_SESSION["customer_id"] > 0 ){
				$sql = "SELECT `customers_firstname`, `customers_lastname`, `customers_email_address`
						FROM `" . TABLE_CUSTOMERS . "`
						WHERE `customers_id` = :customerID";
				$sql = $db->BindVars($sql, ':customerID', (int)$_SESSION["customer_id"], 'integer');
				$rec = $db->Execute($sql);
				if( !$rec->EOF ){
					$customer['name'] = trim($rec->fields['customers_firstname'] . ' ' . $rec->fields['customers_lastname']);
					$customer['email'] = $rec->fields['customers_email_address'];					
				}
			}
			#LOAD FORM
			$output = $this->FormLoader( $customer );
		}
		
		return $output;
	}
	
	private function ProcessSubmitForm(){
		/* PROCESS FORM SUBMISSION
		 ***/
		global $db, $messageStack;
		
		#LOCAL VARIABLES
		$submission_processed = false;
		$name = '';
		$email = '';
		$ordernumber = '';
		$accountID = 0;		
		$message = '';
		$email_text_customer = '';
		$email_html_customer = '';
		$email_text_admin = '';
		$email_html_admin = '';
		
		#LOAD VALUES
		$form_id = 0;
		if( isset($_POST['form_id']) ){
			$form_id = (int)$_POST['form_id'];
		}
		if( isset($_POST['txtCustomerName']) ){
			$name = $_POST['txtCustomerName'];
		}		
		if( isset($_POST['txtCustomerEmail']) ){
			$email = $_POST['txtCustomerEmail'];
		}
		if( isset($_POST['txtOrderNumber']) ){
			$ordernumber = $_POST['txtOrderNumber'];
		}
		if( isset($_SESSION['customer_id']) ){
			$accountID = (int)$_SESSION['customer_id'];
		}
	
		$message = $this->CompileJsonFromPostedValues();		
		
		
		#ENSURE ALL REQUIRED FIELDS ARE SET
		if( count($this->required_fields) > 0 ){
			
			#RELOAD MESSAGE STACK
			foreach( $this->required_fields as $rf ){
				if( !isset( $_POST[$rf]) or $_POST[$rf] == '' ){
					$field_label = $this->GetFieldLabelFromFieldName( $rf );
					$messageStack->add_session($this->main_page,sprintf(MESSAGE_REQUIRED_FIELD_MISSING, $field_label), 'info');
				}
			}
		}
		
		if( $messageStack->size( $this->main_page ) == 0 ){ #NO REQUIRED FIELD PENDING
			#SAVE DATA TO THE DATABASE, PREP EMAILS
			
			$sql = "INSERT INTO `" . TABLE_WIDERRUFSFORMULAR_REQUESTS . "`
					(
						`form_id`,
						`customer_name`,	
						`customer_email`,
						`order_id`,						
						`message`,
						`status`,
						`message_timestamp`
					)VALUES(
						:formID,
						:cName,	
						:cEmail,					
						:orderID,	
						:message,
						:status,
						:timestamp
					)";
			
			/* CONTACT SECTION */
			
	#STATUS
			$sql = $db->BindVars( $sql, ':status', zen_db_prepare_input(DEFAULT_REQUEST_STATUS), 'string' );
			
			#TIME STAMP
			$sql = $db->BindVars( $sql, ':timestamp', zen_db_prepare_input(date('Y-m-d H:i:s')), 'string' );
			#ORDERNUMBER
			$sql = $db->BindVars( $sql, ':formID', zen_db_prepare_input($form_id), 'integer' );
			$sql = $db->BindVars( $sql, ':orderID', zen_db_prepare_input($ordernumber), 'string' );

        $timestamp = date('d.m.Y H:i:s');
        $email_text_customer .= LABEL_TIMESTAMP . "\n" . $timestamp . "\n\n";
				$email_html_customer .= '<p>' . LABEL_TIMESTAMP . '<br />' . $timestamp . '</p>' . "\n";			

			
			#NAME
			$sql = $db->BindVars( $sql, ':formID', zen_db_prepare_input($form_id), 'integer' );
			$sql = $db->BindVars( $sql, ':cName', zen_db_prepare_input($name), 'string' );

			#EMAIL
			$sql = $db->BindVars( $sql, ':cEmail', zen_db_prepare_input($email), 'string' );
			
			/* MESSAGE SECTION */
			
			#MESSAGE
			$sql = $db->BindVars( $sql, ':message', zen_db_prepare_input($message), 'string' );
			
			if( strlen($message) > 10 ){
				$message_array = json_decode($message);
				foreach($message_array as $elObj ){
					$elArray = (array)$elObj;
					$fLabel = key($elArray);
					$fValue = $elArray[key($elArray)];
				$fValue = $this->GetTextFromOptionValue( $fValue );
					
					#LOAD EMAIL
					
					$email_text_customer .= $fLabel . ":\n" . $this->PrepStringForTextEmail($fValue) . "\n\n";
					$email_html_customer .= '<p>' . $fLabel . '<br />' . $this->PrepStringForHTMLEmail($fValue) . '</p>' . "\n";
					
				}
			}
			$email_footer_customer = EMAIL_FOOTER_CUSTOMER;
			$email_text_customer .= $email_footer_customer . "\n\n";
			$email_html_customer .= '<p></p><p>' . $email_footer_customer . '</p><p></p>' . "\n";
			
			
			
			#FINALIZE EMAIL MESSAGES
			
			$email_html_admin .= $email_html_customer;
			$email_text_admin .= $email_text_customer;		
			
			
			#SAVE MESSAGE INTO THE DATABASE
			$submission_processed = $db->Execute( $sql );
			$request_id = $db->Insert_ID();
			#SEND CUSTOMER AN EMAIL
			$from_email_name = STORE_NAME;
			$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
			if( isset($_POST['txtCustomerEmail']) and strpos($_POST['txtCustomerEmail'], '@') > 0 ){
				$to_name = ( isset($_POST['txtCustomerName']) ? $_POST['txtCustomerName'] : '' );
				$to_address = $_POST['txtCustomerEmail'];
				
				#SEND EMAIL TO CUSTOMER
				@zen_mail($to_name, $to_address, EMAIL_SUBJECT_CUSTOMER, $email_text_customer, $from_email_name, $from_email_address, array('EMAIL_MESSAGE_HTML' => $email_html_customer) );
			}
			
			
			#NOTIFY ADMIN
			$email_subject ='';
			if( WIDERRUFSFORMULAR_RECIPIENT_EMAIL != '' ){
				$email_subject .= sprintf(EMAIL_SUBJECT_ADMIN, $request_id);
				
				$admin_emails = explode(";", WIDERRUFSFORMULAR_RECIPIENT_EMAIL);
				foreach( $admin_emails as $to_address ){
					@zen_mail('', $to_address, $email_subject, $email_text_admin, $from_email_name, $from_email_address, array('EMAIL_MESSAGE_HTML' => $email_html_admin) );
				}
			}
			
			#REDIRECT
			if( $submission_processed ){
				$messageStack->add_session('widerrufsformular',MESSAGE_FORM_SUBMITION_SUCCESS, 'success');
				
				$redirect_url = 'index.php?main_page=' . $this->main_page . '&'. zen_get_all_get_params(array('main_page','action')) . '&action=request_success';
				
				#RELOAD THE SAME PAGE WITHOUT POST
				zen_redirect( $redirect_url );
			}else{
				$messageStack->add_session('widerrufsformular',MESSAGE_FORM_SUBMITION_ERROR, 'error');
			}
		} #END: if message stack has content
	} #END: ProcessSubmitForm()
	
	private function FormLoader( $customer ){
		global $db, $messageStack, $template_dir;
		
		#DETERMINE TEXT SIZE, MAXLENGTH
		$size = WIDERRUFSFORMULAR_TEXT_MAX_CHAR;
		$maxlen = WIDERRUFSFORMULAR_TEXT_MAX_CHAR;
		if( $size > 32 ) $size = 32;
		#LOAD PARAMETERS
		if( $this->page_url != '' ){
			$frm = zen_draw_form(
					'widerrufsformular',
					$this->page_url,
					'post',
					'enctype="multipart/form-data" onSubmit="return ValidateForm(this);"'
				);
		}
		$frm .= zen_draw_hidden_field('action', 'request_confirmation');
		$frm .= zen_draw_hidden_field('form_id', $this->form_id);
		
		$frm .= '<div id="customProductForm">' . "\n";
		
		$frm .= $this->GetMessage( $this->main_page );
		
		#CUSTOMER INFORMATION
		$show_contact_info = false;
		$cInfo = '<hr />' . "\n";
		$cInfo .= '<h1>' . HEAD_WIDERRUF_INFORMATION . '</h1>';
		$cInfo .= '<table>' . "\n";
		
		#ORDER NUMBER
		
			$cInfo .= '
			<tr>
				<th>' . 
					zen_draw_label(LABEL_ORDER_NUMBER . ': ', 'txtOrderNumber') .
					( in_array('txtOrderNumber', $this->required_fields) ? REQUIRED_FLAG : '' ) .
				'</th>
				<td class="tblFormFields">' . 
					zen_draw_input_field('txtOrderNumber', $customer['orderid'], zen_set_field_length(TABLE_WIDERRUFSFORMULAR_REQUESTS, 'order_id', $maxlen)) .
				'</td>
			</tr>';
			$show_contact_info = true;
		
		#CUSTOMER NAME
		
			$cInfo .= '
			<tr>
				<th>' . 
					zen_draw_label(LABEL_CUSTOMER_NAME . ': ', 'txtCustomerName') .
					( in_array('txtCustomerName', $this->required_fields) ? REQUIRED_FLAG : '' ) .
				'</th>
				<td class="tblFormFields">' . 
					zen_draw_input_field('txtCustomerName', $customer['name'], zen_set_field_length(TABLE_WIDERRUFSFORMULAR_REQUESTS, 'customer_name', $maxlen)) .
				'</td>
			</tr>';
			$show_contact_info = true;
		
		#CUSTOMER'S E-MAIL
		
			$cInfo .= '
			<tr>
				<th>' . 
					zen_draw_label(LABEL_CUSTOMER_EMAIL . ': ', 'txtCustomerEmail') . 
					( in_array('txtCustomerEmail', $this->required_fields) ? REQUIRED_FLAG : '' ) .
				'</th>
				<td>' . 
					zen_draw_input_field('txtCustomerEmail', $customer['email'], zen_set_field_length(TABLE_WIDERRUFSFORMULAR_REQUESTS, 'customer_email', $maxlen)) . 
				'</td>
			</tr>';
			$show_contact_info = true;	

		
		$cInfo .= '</table>' . "\n";
		
		if( $show_contact_info ){
			$frm .= $cInfo;
		}
		
		#CUSTOM FORM FIELDS
		$show_widerrufsformular = false;
		$cForm = '<hr />' . "\n";
		$cForm .= '<h1>' . $this->form_title . '</h1>';
		
		$sql = "SELECT `form_field_id`, `field_type`, `field_name`, `label`, `description`, `required`
				FROM `" . TABLE_WIDERRUFSFORMULAR_FIELDS . "`
				WHERE `form_id` = :formID
				ORDER BY `sort_order` ASC";
		$sql = $db->BindVars($sql, ':formID', zen_db_prepare_input($this->form_id), 'integer');
		$rec = $db->Execute($sql);
		if( !$rec->EOF ){
			$show_widerrufsformular = true;
		}else{
			#CUSTOM FORM NOT DEFINED: SHOW AN ERROR MESSAGE AND REDIRECT
			$messageStack->add_session('header', MESSAGE_NO_CUSTOM_FORM, 'error');
			zen_redirect(zen_href_link(FILENAME_DEFAULT));
		}
		$cForm .= '<table>';
		while( !$rec->EOF ){
			#LOAD OPTIONS IF ANY
			$options = $this->GetFormFieldOptions( $rec->fields['form_field_id'] );
			
			#SKIP SELECTED FIELDS IF NO OPTIONS ARE AVAILABLE
			if( in_array($rec->fields['field_type'], array('Dropdown', 'Radio', 'Checkbox') ) and count($options) == 0 ){
				$rec->MoveNext();
				continue;
			}
			
			#LOAD POSTED VALUE IF ANY
			$postValue = '';
			if( isset( $_POST[$rec->fields['field_name']] ) ){
				$postValue = $_POST[$rec->fields['field_name']];
			}
			
			#REQUIRED?
			$required = false;
			if( $rec->fields['required'] == 1 ){
				$required = true;
				$this->required_fields[] = $rec->fields['field_name'];
			}
			
			#START: FIELD OUTPUT
			$field = '<tr>
				<th>
					' . zen_draw_label($rec->fields['label'] . ': ', $rec->fields['field_name']) .
					( $required ? REQUIRED_FLAG : '' ) . '
				</th>' . "\n";
			
			switch( $rec->fields['field_type'] ){
				case 'Text':
					$defaultText = '';
					if( $postValue != '' ){
						$defaultText = $postValue;
					}else if( isset($options[0]) ){
						$defaultText = $options[0]['value'];
					}
					$field .= '
						<td class="tblFormFields">' . 
							zen_draw_input_field($rec->fields['field_name'], $defaultText, 'size="' . $size . '" maxlength="' . $maxlen . '"', 'text') . 
						'</td>' . "\n";
					break;
				case 'Text Area':
					$defaultText = '';
					if( $postValue != '' ){
						$defaultText = $postValue;
					}else if( isset($options[0]) ){
						$defaultText = $options[0]['value'];
					}
					$field .= '
						<td class="tblFormFields">' . 
							zen_draw_textarea_field($rec->fields['field_name'], 30, 7, $defaultText,'style="margin:0;width:100%;"') . 
						'</td>' . "\n";
					break;
				
				default:
					break;
			}
			
			#FIND QUESTION ICON
			
			if( $rec->fields['description'] != '' ){
				$field .= '<td class="tooltip">
							<span class="tooltipicon"><img src="images/widerrufsformular-help.png" class="customformhelp"></span>
							<span class="tooltiptext">' . $rec->fields['description'] . '</span>
						</td>' . "\n";
			}else{
				$field .= '<td> </td>' . "\n";
			}
			
			$field .= '</tr>' . "\n";
			$cForm .= $field;
			$rec->MoveNext();
		}
		$cForm .= '</table>' . "\n";
		if( $show_widerrufsformular ){
			$frm .= $cForm;
		}
		
		$frm .= '</div>' . "\n";
		
		#SEND / RETURN BUTTONS
		$frm .= '
			<div class="buttonRow forward"><input class="cssButton submit_button button  button_send" onmouseover="this.className=\'cssButtonHover  button_send button_sendHover\'" onmouseout="this.className=\'cssButton submit_button button  button_send\'" type="submit" value="'. BTN_WIDERRUFSFORMULAR_CONTINUE . '"></div>
			
			<div class="buttonRow back" onClick="window.history.back();return false;" style="cursor:pointer;"><span class="cssButton normal_button button  button_back" onmouseover="this.className=\'cssButtonHover normal_button button  button_back button_backHover\'" onmouseout="this.className=\'cssButton normal_button button  button_back\'">' . BTN_BACK . '</span></div>
		</form>' . "\n";
		
		return $frm;
	} #END: FORM LOADER METHOD
	
	private function RequestConfirmationLoader(){
		global $messageStack;
		
		/* START: ENSURE ALL REQUIRED FIELDS ARE SET */
		if( count($this->required_fields) > 0 ){
			#RELOAD MESSAGE STACK
			foreach( $this->required_fields as $rf ){
				if( !isset( $_POST[$rf]) or $_POST[$rf] == '' ){
					$field_label = $this->GetFieldLabelFromFieldName( $rf );
					$messageStack->add_session($this->main_page,sprintf(MESSAGE_REQUIRED_FIELD_MISSING, $field_label), 'info');
				}
			}
		}	
		
		
		if( $messageStack->size( $this->main_page ) > 0 ){
			$customer = array(
				'name'		=> '',				
				'email'		=> '',
				'orderid'	=> ''
				
			);
			
			if( isset($_POST['txtCustomerName']) ){
				$customer['name'] = $_POST['txtCustomerName'];
			}
			if( isset($_POST['txtCustomerEmail']) ){
				$customer['email'] = $_POST['txtCustomerEmail'];
			}
			if( isset($_POST['txtOrdernumber']) ){
				$customer['ordernumber'] = $_POST['txtOrdernumber'];
			}
			
			return $this->FormLoader( $customer );
		}
		/* END: ENSURE ALL REQUIRED FIELDS ARE SET */
		
		$output = '<h1>' . HEAD_CONFIRMATION . '</h1>' . "\n";
		
		if( $this->page_url != '' ){
			$output .= zen_draw_form(
				'widerrufsformular',
				$this->page_url,
				'post',
				'enctype="multipart/form-data"'
			) .
			zen_draw_hidden_field('action', 'send_request') . 
			zen_draw_hidden_field('form_id', $this->form_id) . "\n";
		}
		
		$output .= '<table id="tblConfirm">' . "\n";
		$no_show = array('securityToken','action','form_id');
		$rowCount = 0;
		if( isset($_POST) and is_array($_POST) ){
			foreach( $_POST as $fieldName => $value ){
				if( !in_array($fieldName, $no_show) ){
					$output .= '<tr class="'.($rowCount%2==0?'evenRow':'oddRow').'">';
					$label = $this->GetFieldLabelFromFieldName( $fieldName );
					$output .= '<td>' . zen_draw_label($label, '') . '</td>' . "\n";
					if( is_array($value) ){
						$output .= '<td>' . "\n";
						foreach( $value as $v ){
							$output .= nl2br($this->GetTextFromOptionValue( $v )) . '<br />' . "\n";
							$output .= zen_draw_hidden_field($fieldName . '[]', $v) . "\n";
						}
						$output .= '</td>' . "\n";
					}else{
						$output .= '<td>' . nl2br($this->GetTextFromOptionValue( $value )) . '</td>' . "\n";
						$output .= zen_draw_hidden_field($fieldName, $value) . "\n";
					}
					$output .= '</tr>' . "\n";
				}
				$rowCount++;
			}
			
		}
		$output .= '</table>' . "\n";
		
		#SEND / RETURN BUTTONS
		$output .= '
			<div class="buttonRow forward"><input class="cssButton submit_button button  button_send" onmouseover="this.className=\'cssButtonHover  button_send button_sendHover\'" onmouseout="this.className=\'cssButton submit_button button  button_send\'" type="submit" value="'. BTN_WIDERRUFSFORMULAR_SEND . '"></div>
			
			<div class="buttonRow back" onClick="window.history.back();return false;" style="cursor:pointer;"><span class="cssButton normal_button button  button_back" onmouseover="this.className=\'cssButtonHover normal_button button  button_back button_backHover\'" onmouseout="this.className=\'cssButton normal_button button  button_back\'">' . BTN_BACK . '</span></div>
		</form>' . "\n";
		
		return $output;
	}
	
	/* UTILITY METHODS - LIBRARY */
	private function GetTextFromOptionValue( $optVal ){
		global $db;
		
		$optText = $optVal; #DEFAULT
		$sql = "SELECT f.`field_type`, fo.`field_text`
				FROM `" . TABLE_WIDERRUFSFORMULAR_FIELDS . "` AS f 
					JOIN `" . TABLE_WIDERRUFSFORMULAR_FIELDS_OPTIONS . "` AS fo
						ON f.`form_field_id` = fo.`form_field_id`
				WHERE f.`form_id` = :fID 
					AND fo.`field_value` = :fValue";
		$sql = $db->BindVars($sql, ':fID', zen_db_prepare_input($this->form_id), 'integer');
		$sql = $db->BindVars($sql, ':fValue', zen_db_prepare_input($optVal), 'string');
		$rec = $db->Execute( $sql );
		if( !$rec->EOF ){
			if( in_array($rec->fields['field_type'], array('Dropdown', 'Radio', 'Checkbox') ) ){
				$optText = $rec->fields['field_text'];
			}
		}
		
		return $optText;
	}
	
	private function GetFieldLabelFromFieldName( $fieldName ){
		global $db;
		
		$fieldLabel = $fieldName; //default
		
		#STATIC VALUES
		switch ( $fieldName ){		
				case 'txtOrderNumber':
				$fieldLabel = LABEL_ORDER_NUMBER;
				break;
			case 'txtCustomerName':
				$fieldLabel = LABEL_CUSTOMER_NAME;
				break;
			case 'txtCustomerEmail':
				$fieldLabel = LABEL_CUSTOMER_EMAIL;
				break;
	
			default:
				if( $fieldName != '' ){
					$sql = "SELECT `label`
							FROM `" . TABLE_WIDERRUFSFORMULAR_FIELDS . "`
							WHERE `form_id` = :formID
								AND `field_name` = :fieldName";
					$sql = $db->BindVars($sql, ':formID', zen_db_prepare_input($this->form_id), 'integer');
					$sql = $db->BindVars($sql, ':fieldName', zen_db_prepare_input($fieldName), 'string');
					$rec = $db->Execute($sql);
					if( !$rec->EOF ){
						$fieldLabel = $rec->fields['label'];
					}
				}
				break;
		}
		
		return $fieldLabel;
	}
	
	private function GetOptionText( $field_name, $field_value ){
		global $db;
		
		$value_label = $field_value; //DEFAULT
		
		$form_field_id = 0;
		//GET FORM FIELD ID
		$sql = "SELECT `field_type`, `form_field_id`
				FROM `" . TABLE_WIDERRUFSFORMULAR_FIELDS . "`
				WHERE `form_id` = :formID
					AND `field_name` = :fieldName";
		$sql = $db->BindVars($sql, ':formID', zen_db_prepare_input($this->form_id), 'integer');
		$sql = $db->BindVars($sql, ':fieldName', zen_db_prepare_input($field_name), 'string');
		$rec = $db->Execute($sql);
		if( !$rec->EOF ){
			if( in_array($rec->fields['field_type'], array('Text Area','Text', 'File', 'Read Only')) ){
				return $value_label; //NO NEED TO TRANSLATE
			}
			$form_field_id = $rec->fields['form_field_id'];
		}
		
		if( $form_field_id > 0 ){
			//GET OPTION TEXT
			$sql = "SELECT `field_text`
					FROM `" . TABLE_WIDERRUFSFORMULAR_FIELDS_OPTIONS . "`
					WHERE `form_field_id` = :formFieldID
						AND `field_value` = :fieldValue";
			$sql = $db->BindVars($sql, ':formFieldID', $form_field_id, 'integer');
			$sql = $db->BindVars($sql, ':fieldValue', zen_db_prepare_input($field_value), 'string');
			$rec = $db->Execute($sql);
			if( !$rec->EOF ){
				$value_label = $rec->fields['field_text'];
			}
		}
		
		return $value_label;
	}
	

	
	private function CompileJsonFromPostedValues(){
		$message = array();
		$ignore_list = array('securityToken', 'action', 'form_id');
		foreach( $_POST as $fieldName => $fieldValue ){
			if( in_array($fieldName, $ignore_list) ){
				continue;
			}
			

				$field_label = $this->GetFieldLabelFromFieldName( $fieldName );
				if( is_array($fieldValue) ){ #e.g. Checkbox
					$value = array();
					foreach( $fieldValue as $val ){
						$tmp = $this->GetOptionText( $fieldName, $val );
						$value[] = $this->PrepStringForJson( $tmp );
					}
				}else{
					$tmp = $this->GetOptionText( $fieldName, $fieldValue );
					$value = $this->PrepStringForJson( $tmp );
				}
				
				$message[] = array( $field_label => $value );
			
		}
		
		return json_encode( $message, JSON_UNESCAPED_UNICODE );
	}
	
	private function PrepStringForJson( $my_string ){
		$my_string = str_replace("\r\n", JSON_LINE_BREAK_PLACEHOLDER, $my_string);
		$my_string = str_replace("\n\r", JSON_LINE_BREAK_PLACEHOLDER, $my_string);
		$my_string = str_replace("\n", JSON_LINE_BREAK_PLACEHOLDER, $my_string);
		$my_string = str_replace("\r", JSON_LINE_BREAK_PLACEHOLDER, $my_string);
		$my_string = addcslashes( $my_string, '"');
		
		return $my_string;
	}
	
	private function PrepStringForTextEmail( $my_string ){
		$my_string = str_replace(JSON_LINE_BREAK_PLACEHOLDER, "\r\n", $my_string);
		
		return $my_string;
	}
	
	private function PrepStringForHTMLEmail( $my_string ){
		$my_string = nl2br($my_string);
		$my_string = str_replace(JSON_LINE_BREAK_PLACEHOLDER, "<br />\r\n", $my_string);
		
		return $my_string;
	}
	
	private function LogFormHits(){
		/* RECORD HITS TO PAGES WITH NOTIFY_widerrufsformular_LOAD
		 * AND A FORM_ID GET PARAMETER.
		 */
		global $db;
		
		if( $this->form_id > 0 ){
			#DECLARE VARS WITH DEFAULT PARAMETERS
			$acountID = 0;
			$referer = '';
			
			#ACCOUNT ID (IF USER IS LOGGED IN)
			if( isset($_SESSION['customer_id']) ){
				$acountID = (int)$_SESSION['customer_id'];
			}
			#REFERING URL
			if( isset($_SERVER["HTTP_REFERER"]) ){
				$referer = $_SERVER["HTTP_REFERER"];
			}
			if( $referer != '' ){
				#DECLARE SQL WITH PLACEHOLDERS
				$sql = "INSERT INTO `" . TABLE_WIDERRUFSFORMULAR_HITS . "` (
							`form_id`, 							
							`referer`, 
							`timestamp`
						)VALUES(
							:formID,							
							:referer,
							:timeStamp
						)";
				
				#BIND SQL TO VALUES
				$sql = $db->BindVars($sql, ':formID', zen_db_prepare_input($this->form_id), 'integer');
				$sql = $db->BindVars($sql, ':referer', zen_db_prepare_input($referer), 'string');
				$sql = $db->BindVars($sql, ':timeStamp', zen_db_prepare_input(date('Y-m-d H:i:s')), 'string');
				
				#RUN SQL COMMAND
				$db->Execute($sql);
			}
		}
	}
	
	private function GetMessage( $class ){
		global $messageStack;
		ob_start();
		$messageStack->Output( $class );
		return ob_get_clean();
	}
	
	private function GetFormFieldOptions( $fieldID ){
		global $db;
		
		$options = array();
		$sql = "SELECT `field_text`, `field_value`, `selected`
				FROM `" . TABLE_WIDERRUFSFORMULAR_FIELDS_OPTIONS . "`
				WHERE `form_field_id` = :fieldID
				ORDER BY `sort_order` ASC";
		$sql = $db->BindVars($sql, ':fieldID', zen_db_prepare_input($fieldID), 'integer');
		$rec = $db->Execute($sql);
		while( !$rec->EOF ){
			$options[] = array(
				'text' => $rec->fields['field_text'],
				'value' => $rec->fields['field_value'],
				'selected' => $rec->fields['selected']
			);
			$rec->MoveNext();
		}
		
		return $options;
	}
	
	
	
	/* PUBLIC GETTER METHODS */
	public function GetForm(){
		return $this->form;
	}
	
	public function GetFormtitle(){
		return $this->form_title;
	}
	
	public function GetDescription(){
		return $this->description;
	}
	
	public function GetPageTitle(){
		return $this->page_title;
	}
	
	public function GetPageHeading(){
		return $this->page_heading;
	}
	
	public function GetNavbarTitle(){
		return $this->navbar_title;
	}
	public function GetRequiredFields(){
		return $this->required_fields;
	}
}