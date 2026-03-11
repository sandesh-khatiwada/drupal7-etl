(function ($) {
  Drupal.behaviors.etlChatbot = {
    attach: function (context, settings) {

      $('#etl-chatbot-window').hide();
      $('#etl-chatbot-icon').show();

      // Open chat container
      $('#etl-chatbot-icon').once().click(function () {
        var win = $('#etl-chatbot-window');
        if (!win.is(':visible')) {
          win.css('display','flex').hide().fadeIn(200);
          $('#etl-chatbot-icon').fadeOut(200);
        }
      });

      // Close chat
      $('#etl-chatbot-close').once().click(function () {
        var win = $('#etl-chatbot-window');
        win.fadeOut(200, function() {
          $('#etl-chatbot-icon').fadeIn(200);
        });
      });

      // Dynamic today date
      var today = new Date();
      var yyyy = today.getFullYear();
      var mm = (today.getMonth() + 1).toString().padStart(2,'0');
      var dd = today.getDate().toString().padStart(2,'0');
      var todayStr = yyyy+'-'+mm+'-'+dd;
      $('#etl-chatbot-samples .etl-sample').each(function(){
        var text = $(this).text();
        if (text.includes('Show reminders on')) {
          $(this).text('Show reminders on ' + todayStr);
        }
      });

      // Send message function with typing animation
      function sendQuery(q) {
        $('#etl-chatbot-messages').append('<div class="user-msg">'+q+'</div>');

        // Typing animation
        var typingDiv = $('<div class="bot-msg typing">Bot is typing...</div>');
        $('#etl-chatbot-messages').append(typingDiv);
        $('#etl-chatbot-messages').scrollTop(999999);

        $.post(settings.etl_chatbot.ajax_url, {query: q}, function (res) {
          typingDiv.remove();
          $('#etl-chatbot-messages').append('<div class="bot-msg">'+res.response+'</div>');
          $('#etl-chatbot-messages').scrollTop(999999);

          // Bind click on quick action buttons
          $('.etl-btn-view').off('click').on('click', function(){
            window.open($(this).data('url'),'_blank');
          });
        });
      }

      // Send button
      $('#etl-chatbot-send').once().click(function () {
        var q = $('#etl-chatbot-input').val().trim();
        if (!q) return;
        $('#etl-chatbot-input').val('');
        sendQuery(q);
      });

      // Enter key
      $('#etl-chatbot-input').once().keypress(function(e){
        if (e.which == 13) {
          $('#etl-chatbot-send').click();
          return false;
        }
      });

      // Sample questions click
      $('.etl-sample').once().click(function(){
        sendQuery($(this).text());
      });
    }
  };
})(jQuery);