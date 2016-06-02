// TODO: localhost部分を現在のドメインのホスト名へ変更する
var hostname = window.location.hostname;
var ws = new WebSocket('ws://'+hostname+':3000/');
var token = "";

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

    $("form#file").submit(function(){
	console.log("changed");
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
	).done(function( text ){
	    console.log(text);
	    $("input[name='image']").val("");
	}).error(function(err){
	    alert(err);
	});

	return false;
    });
    
    ws.onmessage = function(msg){
	var returnObject = JSON.parse(msg.data);
	console.log(returnObject);
	token = returnObject.token || token;
	$('#messages').append($('<li>')).append($('<span id="clientId">').text(returnObject.id)).append($('<span id="clientMessage">').text(returnObject.data));
    };
    ws.onerror = function(err){
	console.log("err", err);
    };
    ws.onclose = function close() {
	console.log('disconnected');
    };
});
