checkConfiguration()
getUserInfo()
var editForm='form#editGroupForm';
var addForm='form#addGroupForm';
/////CHECKBOX ENABLING///
var generalCheckboxParameters={
	checkboxClass: 'icheckbox_square-blue',
	radioClass: 'iradio_square-blue',
	increaseArea: '20%' // optional
}
$('.checkbox.icheck').iCheck(generalCheckboxParameters);
////////////////////////////////
//////SELECT CHANGE FOR ENABLE////////
$('form#addGroupForm select[name="enable_flag"]').change(function(){
	console.log($('form#addGroupForm select[name="enable_flag"]').val())
	if ($('form#addGroupForm select[name="enable_flag"]').val() == 1 || $('form#addGroupForm select[name="enable_flag"]').val() == 2){
		$('form#addGroupForm div.enable_encrypt_section').show()
	} else {
		$('form#addGroupForm div.enable_encrypt_section').hide()
	}
})
$('form#editGroupForm select[name="enable_flag"]').change(function(){
	console.log($('form#editGroupForm select[name="enable_flag"]').val())
	if ($('form#editGroupForm select[name="enable_flag"]').val() == 1 || $('form#editGroupForm select[name="enable_flag"]').val() == 2){
		$('form#editGroupForm div.enable_encrypt_section').show()
	} else {
		$('form#editGroupForm div.enable_encrypt_section').hide()
	}
})
///////////////////////////
////////////////////////////////
////ADD USER FUNCTION///START//
function addGroup(){
	console.log('Adding new tacacs user group');
	$('.form-group.has-error').removeClass('has-error');
	$('p.text-red').remove();
	$('p.help-block').show();
	/////////ADD NEW DEVICE///START//
	var data = {
		"action": "POST",
		"name": $('form#addGroupForm input[name="name"]').val(),
		"enable": $('form#addGroupForm input[name="enable"]').val(),
		"enable_flag": $('form#addGroupForm select[name="enable_flag"]').val(),
		"enable_encrypt": $('form#addGroupForm input[name="enable_encrypt"]').prop('checked'),
		"message": $('form#addGroupForm textarea[name="message"]').val(),
		"manual": $('form#addGroupForm textarea[name="manual"]').val(),
		"test" : "none"
		};	
	$.ajax({
		type: "POST",
		dataType: "json",
		url: API_LINK+"tacacs/user/group/add/",
		cache: false,
		data: data,
		success: function(data) {
			console.log(data);
			if (data['error']['status']){
				//console.log(data['error']['validation'])
				for (v in data['error']['validation']){
					//console.log(v)
					if (!(data['error']['validation'][v] == null)){
						//console.log($('form#addGroupForm div.'+v))
						$('form#addGroupForm div.'+v).addClass('has-error')
						$('div.form-group.'+v+' p.help-block').hide()
						var error_message='';
						for (num in data['error']['validation'][v]){
							error_message='<p class="text-red">'+data['error']['validation'][v][num]+'</p>';
						}
						$('div.form-group.'+v).append(error_message)
					}
				}
				return;
			}
			toastr["success"]("User Group "+ $('form#addGroupForm input[name="name"]').val() +" was added")
			$("#addGroup").modal("hide");
			changeApplyStatus(data['changeConfiguration'])
			clearAddGroupModal();
			setTimeout( function () {dataTable.ajax.reload()}, 2000 );
		},
		error: function(data) {
			//console.log(data);
			errorHere(data);
		}
	});
	
}
///////////////////////////
function clearAddGroupModal(){
	$('form#addGroupForm input[name="name"]').val('')
	$('form#addGroupForm input[name="enable"]').val('')
	$('form#addGroupForm div.enable_encrypt_section').show()
	$('form#addGroupForm div.login_encrypt_section').show()
	$('form#addGroupForm input[name="enable_encrypt"]').iCheck('check')
	$('form#addGroupForm textarea[name="message"]').val('')
	$('form#addGroupForm textarea[name="manual"]').val('')
	$('.form-group.has-error').removeClass('has-error');
	
	$('a.manualConfTrigger').show()
	$('div.manualConfiguration').hide()
	
	$('p.text-red').remove();
	$('p.help-block').show();
}
////ADD USER FUNCTION///END//
/////////////////////////////////////////////
////EDIT USER FUNCTION///START//
function editGroup(id,name){ //GET INFO ABOUT USER//
	var data = {
		"action": "GET",
		"name": name,
		"id": id,
		"test" : "none"
	};
	$.ajax({
		type: "GET",
		dataType: "json",
		url: API_LINK+"tacacs/user/group/edit/",
		cache: false,
		data: data,
		success: function(data) {
			console.log(data);
			$('name.text-green').text(data['group']['name'])
			$(editForm + ' input[name="name"]').val(data['group']['name'])
			$(editForm + ' input[name="name_old"]').val(data['group']['name'])
			$(editForm + ' input[name="enable"]').val(data['group']['enable'])
			$(editForm + ' input[name="id"]').val(data['group']['id'])
			
			var enable_encryption = ( (data['group']['enable_flag'] == 1 || data['group']['enable_flag'] == 2) && data['group']['enable'] != '') ? 'uncheck' : 'check';
			$(editForm + ' input[name="enable_encrypt"]').iCheck(enable_encryption)
			if (data['group']['enable_flag'] == 0) {$(editForm + ' div.enable_encrypt_section').hide()}
			else ($(editForm + ' div.enable_encrypt_section').show())
			
			$(editForm + ' select[name="enable_flag"] option[value="'+data['group']['enable_flag']+'"]').prop('selected', true)
			
			var default_service = (data['group']['default_service'] == 1) ? 'check' : 'uncheck';
			$(editForm + ' input[name="default_service"]').iCheck(default_service)
			
			$(editForm + ' textarea[name="message"]').val(data['group']['message'])
			$(editForm + ' textarea[name="manual"]').val(data['group']['manual'])
			
			$('text.created_at').text('Created at '+data['group']['created_at']);
			$('text.updated_at').text('Last update was at '+data['group']['updated_at']);
			$('#editGroup').modal('show')
		},
		error: function(data) {
			//console.log(data);
			errorHere(data);
		}
	});
}
function submitGroupChanges(){
	$('.form-group.has-error').removeClass('has-error');
	$('p.text-red').remove();
	$('p.help-block').show();
		var data = {
		"action": "POST",
		"name": $(editForm + ' input[name="name"]').val(),
		"name_old": $(editForm + ' input[name="name_old"]').val(),
		"enable": $(editForm + ' input[name="enable"]').val(),
		"enable_flag": $(editForm + ' select[name="enable_flag"]').val(),
		"enable_encrypt": $(editForm + ' input[name="enable_encrypt"]').prop('checked'),
		"id": $(editForm + ' input[name="id"]').val(),
		"message": $(editForm + ' textarea[name="message"]').val(),
		"manual": $(editForm + ' textarea[name="manual"]').val(),
		"test" : "none"
	};
	$.ajax({
		type: "POST",
		dataType: "json",
		url: API_LINK+"tacacs/user/group/edit/",
		cache: false,
		data: data,
		success: function(data) {
			console.log(data);
			if (data['error']['status']){
				//console.log(data['error']['validation'])
				for (v in data['error']['validation']){
					//console.log(v)
					if (!(data['error']['validation'][v] == null)){
						//console.log($(editForm + ' div.'+v))
						$(editForm + ' div.'+v).addClass('has-error')
						$('div.form-group.'+v+' p.help-block').hide()
						var error_message='';
						for (num in data['error']['validation'][v]){
							error_message='<p class="text-red">'+data['error']['validation'][v][num]+'</p>';
						}
						$('div.form-group.'+v).append(error_message)
					}
				}
				return;
			}
			toastr["success"]("User Group "+ $(editForm + ' input[name="name"]').val() +" was updated")
			$("#editGroup").modal("hide");
			changeApplyStatus(data['changeConfiguration'])
			clearEditGroupModal();
			setTimeout( function () {dataTable.ajax.reload()}, 2000 );
		},
		error: function(data) {
			//console.log(data);
			errorHere(data);
		}
	});
}
///////////////////////////
function clearEditGroupModal(){
	$(editForm + 'input[name="name"]').val('')
	$(editForm + 'input[name="enable"]').val('')
	$(editForm + 'div.enable_encrypt_section').show()
	$(editForm + 'input[name="enable_encrypt"]').iCheck('check')
	$(editForm + 'textarea[name="message"]').val('')
	$(editForm + 'textarea[name="manual"]').val('')
	$('.form-group.has-error').removeClass('has-error');
	
	$('a.manualConfTrigger').show()
	$('div.manualConfiguration').hide()	
	
	$('p.text-red').remove();
	$('p.help-block').show();
}
////EDIT USER FUNCTION///END//
//////////////////////////////
//////////////////////////////
////DELETE USER Group FUNCTION////START//
function deleteGroup(id,name){
	console.log('Deleting GroupID:'+id+' with name '+name)
	if (confirm("Do you want delete '"+name+"'?")){
		/////////DELETE USER Group///START//
		var data = {
			"action": "POST",
			"name": name,
			"id": id,
			"test" : "none"
			};	
		$.ajax({
			type: "POST",
			dataType: "json",
			url: API_LINK+"tacacs/user/group/delete/",
			cache: false,
			data: data,
			success: function(data) {
				console.log(data);
				if(data['deleteGroup']!=1){toastr["error"]("Oops! Unknown error appeared :(");return;}
				toastr["success"]("User Group "+ name +" was deleted")
				changeApplyStatus(data['changeConfiguration'])				
				setTimeout( function () {dataTable.ajax.reload()}, 2000 );
			},
			error: function(data) {
				//console.log(data);
				errorHere(data);
			}
		});
		/////////DELETE USER Group///END////
	}
	return;
}
////DELETE USER FUNCTION////END//
/////////////////////////////////

////CLEAR MODALS FUNCTIONS//////
$('#addGroup').on('hidden.bs.modal', function(){
	clearAddGroupModal()
})
$('#editGroup').on('hidden.bs.modal', function(){
	clearEditGroupModal()
})
////////////////////////////////
////////////////////////////////
////MANUAL CONFIGURATION TRIGGER//START//
$('a.manualConfTrigger').click(function(){
	$('a.manualConfTrigger').hide()
	$('div.manualConfiguration').show()
})
////MANUAL CONFIGURATION TRIGGER//END//
////////////////////////////////////////