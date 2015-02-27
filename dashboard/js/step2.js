function campaignData(file){

	/*div1,div2,div3
	$("#"+div1).show();
	$("#"+div2).hide();
	$("#"+div3).hide();*/
    $.ajax({
	   type: "POST",
		url: file+".php",
		success: function(msg){
		    $("#div_inbox").html();
		    $("#div_inbox").html(msg);
        }
    });
}

function passValue(value, id){
    $('#link1').removeClass('active');
    $('#link2').removeClass('active');
    $('#link3').removeClass('active');
    $('#link4').removeClass('active');
    $('#dScheduleTime').attr('value', value);
    $('#'+id).addClass('active');
}

$('#followupMailSubmit').click(function(){
    tinyMCE.triggerSave();
    $('#followupMail').validate();
    $('form').ajaxForm({
         beforeSend: function() {
            //status.empty();
        },
        success: function() {
            var percentVal = '100%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        complete: function(xhr) {
            status.html(xhr.responseText);
        }
    });
});


function showHide(div1,div2){
    $('#'+div1).show();
    $('#'+div2).hide();
    if(div1 == 'writeText'){
    	tinymce.init({selector:'textarea'});
    }
}

$('#directMailSubmit').click(function(){
	if($('#scriptupload').is(':checked')){
		//alert('scriptupload');
		var file = $('input[type="file"]').val();
		if(file == ''){
			$('label.error').html('Please Fill the field');
			return false;
		}
		else{
			var bar = $('.bar');
			var percent = $('.percent');
			var status = $('#status');
			$('form').ajaxForm({
			    beforeSend: function() {
			        status.empty();
			        var percentVal = '0%';
			        bar.width(percentVal)
			        percent.html(percentVal);
			    },
			    uploadProgress: function(event, position, total, percentComplete) {
			        var percentVal = percentComplete + '%';
			        bar.width(percentVal)
			        percent.html(percentVal);
			    },
			    success: function() {
			        var percentVal = '100%';
			        bar.width(percentVal)
			        percent.html(percentVal);
			    },
			    complete: function(xhr) {
			    	status.html(xhr.responseText);
			    }
			});
		}
	}
	else{
		var bar = $('.bar');
		var percent = $('.percent');
		var status = $('#status');
		$('form').ajaxForm({
		    beforeSend: function() {
		        status.empty();
		        var percentVal = '0%';
		        bar.width(percentVal)
		        percent.html(percentVal);
		    },
		    uploadProgress: function(event, position, total, percentComplete) {
		        var percentVal = percentComplete + '%';
		        bar.width(percentVal)
		        percent.html(percentVal);
		    },
		    success: function() {
		        var percentVal = '100%';
		        bar.width(percentVal)
		        percent.html(percentVal);
		    },
		    complete: function(xhr) {
		    	status.html(xhr.responseText);
		    }
		});
	}
});

$('#callSubmit').click(function(){
	var bar = $('.bar');
	var percent = $('.percent');
	var status = $('#status');
	$('form').ajaxForm({
	    beforeSend: function() {
	        status.empty();
	        var percentVal = '0%';
	        bar.width(percentVal)
	        percent.html(percentVal);
	    },
	    uploadProgress: function(event, position, total, percentComplete) {
	        var percentVal = percentComplete + '%';
	        bar.width(percentVal)
	        percent.html(percentVal);
	    },
	    success: function() {
	        var percentVal = '100%';
	        bar.width(percentVal)
	        percent.html(percentVal);
	    },
	    complete: function(xhr) {
	    	//window.location.href= "step2.php";
	    	status.html(xhr.responseText);
	    }
	});
});