function keepScrollingDown(id) {
  var box = document.getElementById(id);
  box.scrollTop = box.scrollHeight;
}

$(document).ready(function(){

  var username = $('#username');
  var chatInput = $('#chatText');
  var chatBox = $('#chat');

  $('#chatBox').on('submit', function(e){
    if ( chatInput.val().trim() === '' ) return false;

    $.post('/chat.php', {"chatText": chatInput.val(), user: username.val()});
//    username.attr('disabled', true);
    chatInput.val('');
    chatInput.focus();
    e.stopPropagation();
    return false;
  });

  setInterval(function(){
    $.get('/chat.php', function(data){
      keepScrollingDown('chat');
      chatBox.html(data.chatText);

    });
  },250);

});
