// XXX: どうにかする、したい
var hostname = window.location.hostname;
var ws = new WebSocket('ws://'+hostname+':3000/');
var token = "";
var flgSending = false;


$(function () {
    $('form#chat').submit(function(){
	var $this = $(this);
	var mval = $('#m').val();
	
	// どうやって防ぐのさ
	if (mval.indexOf('@') !== -1) {
	    alert('@ は使用不可文字です!');
	}else{
	    var value = mval + "@" + token;
	    console.log(value);
	    ws.send(value);
	}
	
	$('#m').val('');
	return false;
    });

    $("form#file").submit(function(e){
	$("#blackout").show();
	
	var fd = new FormData();
	if ($("input[name='image']").val()!== '') {
	    fd.append( "file", $("input[name='image']").prop("files")[0] );
	}
	
	var postData = {
	    type : "POST",
	    dataType : "text",
	    data : fd,
	    processData : false,
	    contentType : false
	};
	$.ajax(
	    "./upload.php", postData
	).done(function(imgname){
	    $("input[type='file']").val("");

	    // DRY
	    var value = "bot present " + imgname + "@" + token;
	    console.log(value);
	    ws.send(value);
	}).error(function(err){
	    alert(err);
	});

	return false;
    });
    
    ws.onmessage = function(msg){
	var returnObject = JSON.parse(msg.data);
	console.log(returnObject);
	token = returnObject.token || token;

	if(returnObject.word){
	    //$('#messages').append($('<li class="botword">')).append($('<span id="clientId">').text("ぼっと子： ")).append($('<span id="clientMessage">').text(returnObject.word));
	    var word = nl2br(returnObject.word);
	    $('#messages').prepend($('<li class="botword"><span id="clientId">ぼっと子：</span><span id="clientMessage">'+ word + '</span></li>'));
	}else if(returnObject.chat){
	    console.log("fafa");
	    var id = returnObject.id?returnObject.id:"ななし";
	    $('#messages').prepend('<li><span id="clientId">'+id+':</span><span id="clientMessage">'+ returnObject.chat + '</span></li>');
	}

	// $('#messages').append($('<li>')).append($('<span id="clientId">').text(returnObject.id)).append($('<span id="clientMessage">').text(returnObject.data));
	
	$("#blackout").hide();
    };
    ws.onerror = function(err){
	console.log("err", err);
    };
    ws.onclose = function close() {
	console.log('disconnected');
    };
});

function nl2br(str){
    return str.replace(/\n/g, "<br />");
}
