(function() {
  jQuery(function($) {
    var fetchData, init, resetSinceTimes;
    init = function() {
      return $(document).on('click.sa', '#sa-options', function(e) {
        var id;
        e.preventDefault;
        id = $(e.target).attr('id');
        $('#' + id).parent().find('.spinner').css({
          visibility: 'visible'
        });
        switch (id) {
          case 'sa-btn-fetch-fb':
            return fetchFBData();
          case 'sa-btn-fetch':
            return fetchData();
          case 'sa-btn-reset':
            return resetSinceTimes();
        }
      });
    };

    fetchFBData = function(id) {
      return $.ajax({
        type: 'GET',
        url: ajaxurl,
        data: {
          action: 'fetch_facebook_feed'
        }
      }).done(function(response) {
        if (response.message != null) {
          $('#sa-btn-fetch-fb').parent().find('.message').html(response.message);
        }
        return $('#sa-btn-fetch-fb').parent().find('.spinner').css({
          visibility: 'hidden'
        });
      });
    };

    fetchData = function(id) {
      return $.ajax({
        type: 'GET',
        url: ajaxurl,
        data: {
          action: 'fetch_social_feeds'
        }
      }).done(function(response) {
        if (response.message != null) {
          $('#sa-btn-fetch').parent().find('.message').html(response.message);
        }
        return $('#sa-btn-fetch').parent().find('.spinner').css({
          visibility: 'hidden'
        });
      });
    };
    resetSinceTimes = function() {
      return $.ajax({
        type: 'GET',
        url: ajaxurl,
        data: {
          action: 'reset_since_times'
        }
      }).done(function(response) {
        return $('#sa-btn-reset').parent().find('.spinner').css({
          visibility: 'hidden'
        });
      });
    };
    return init();
  });

}).call(this);
