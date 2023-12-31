$(function(){

	var files_remove = 0, file_extract = 0, folders_remove, website_lock_time = 5, auto_update = 0;

	function dli(value,str1,str2,str3){
		return ((value == 1) ? (str1) : (((value % 10 > 1) && (value % 10 < 5) && !((value % 100 >= 10) && (value % 100 <= 21))) ? (str2) : (str3)));
	}

	function Extractor_DisplayErrors(errors){
		var output = '';
		errors.forEach(function(element){
			output += '<div class="col-xs-12"><div class="alert alert-danger" role="alert"><strong>Błąd</strong> '+element+'</div></div>';
		});
		$('#errors').html(output);
		$('#btn_init_extract').html('Get started').prop('disabled',false);
		$('#upgrade_button_panel').prop('disabled',false);
		website_lock_time = 5;
	}

	function Extractor_RemoveUnusedFiles(){
		$('#btn_init_extract').html('Deleting '+files_remove+' '+dli(files_remove,"file","files","files"));
		$.post('upgrade/driver.php',{
			'action' : 'remove_unused'
		}).done(function(response){
			try {
				response = JSON.parse(response);
				if(response.error){
					Extractor_DisplayErrors(response.messages);
				} else {
					Extractor_RemoveUnusedFolders();
				}
			} catch (error){
				Extractor_DisplayErrors(['Error performing operation']);
			}
		}).fail(function(xhr, status, error){
			Extractor_DisplayErrors(['Error performing operation']);
		});
	}

	function Extractor_RemoveUnusedFolders(){
		$('#btn_init_extract').html('Deleting '+folders_remove+' '+dli(folders_remove,"folder","folders","folders"));
		$.post('upgrade/driver.php',{
			'action' : 'remove_unused_dir'
		}).done(function(response){
			try {
				response = JSON.parse(response);
				if(response.error){
					Extractor_DisplayErrors(response.messages);
				} else {
					Extractor_ExtractFiles();
				}
			} catch (error){
				Extractor_DisplayErrors(['Error performing operation']);
			}
		}).fail(function(xhr, status, error){
			Extractor_DisplayErrors(['Error performing operation']);
		});
	}

	function Extractor_ExtractFiles(){
		$('#btn_init_extract').html('Unpacking '+files_extract+' '+dli(files_extract,"file","files","files"));
		$.post('upgrade/driver.php',{
			'action' : 'extract'
		}).done(function(response){
			try {
				response = JSON.parse(response);
				if(response.error){
					Extractor_DisplayErrors(response.messages);
				} else {
					var app_url = '';
					if(auto_update == 1){
						app_url = window.location.href.replace('/upgrade.php','/setup.php?auto_update=true');
					} else {
						app_url = window.location.href.replace('/upgrade.php','/setup.php');
					}
					window.location.href = app_url;
				}
			} catch (error){
				Extractor_DisplayErrors(['Error performing operation']);
			}
		}).fail(function(xhr, status, error){
			Extractor_DisplayErrors(['Error performing operation']);
		});
	}

	function Extractor_InitExtract(){
		$('#btn_init_extract').html('Scanning page files');
		$.post('upgrade/driver.php',{
			'action' : 'init_extract'
		}).done(function(response){
			try {
				response = JSON.parse(response);
				if(response.error){
					Extractor_DisplayErrors(response.messages);
				} else {
					auto_update = response.auto_update;
					files_remove = response.files_remove;
					files_extract = response.files_extract;
					folders_remove = response.folders_remove;
					Extractor_LockWebsite();
				}
			} catch (error){
				Extractor_DisplayErrors(['Error performing operation']);
			}
		}).fail(function(xhr, status, error){
			Extractor_DisplayErrors(['Error performing operation']);
		});
	}

	function Extractor_LockWebsite(){
		$('#btn_init_extract').html('Blocking access to the website');
		$('#errors').html('');
		$.post('upgrade/driver.php',{
			'action' : 'lock_website'
		}).done(function(response){
			try {
				response = JSON.parse(response);
				if(response.error){
					Extractor_DisplayErrors(response.messages);
				} else {
					$('#errors').html('Left '+website_lock_time+' seconds');
					setTimeout(Extractor_OnWebsiteLock, 1000);
				}
			} catch (error){
				Extractor_DisplayErrors(['Error performing operation']);
			}
		}).fail(function(xhr, status, error){
			Extractor_DisplayErrors(['Error performing operation']);
		});
	}

	function Extractor_OnWebsiteLock(){
		website_lock_time--;
		if(website_lock_time == 0){
			$('#errors').html('');
			Extractor_RemoveUnusedFiles();
		} else {
			$('#errors').html('Left '+website_lock_time+' '+dli(website_lock_time,'second','seconds','seconds'));
			setTimeout(Extractor_OnWebsiteLock, 1000);
		}
	}

	$('#upgrade_button_panel').on('click',function(e){
		if($(this).is(':disabled')) return;
		var app_url = window.location.href.replace('/upgrade.php','');
		window.location.href = app_url;
	});

	$('#btn_init_extract').on('click',function(e){
		var btn = $(this);
		if(btn.is(':disabled')) return false;
		btn.prop('disabled',true);
		$('#upgrade_button_panel').prop('disabled',true);
		Extractor_InitExtract();
		return true;
	});

});
