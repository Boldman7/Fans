document_title = document.title;
$(function () {
  
    $('.chat-list').css('display', 'none');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        }
    });

   var validFiles = [];       
  //Admin panel user sorting
  $('.usersort').on('change', function() {    
    window.location = SP_source() + 'admin/users?sort=' + this.value;
  });

  //Admin panel page sorting
  $('.pagesort').on('change', function() {        
    window.location = SP_source() + 'admin/pages?sort=' + this.value;    
  });

  //Admin panel group sortingb
  $('.groupsort').on('change', function() {        
    window.location = SP_source() + 'admin/groups?sort=' + this.value;    
  });

  //Admin panel event sorting
  $('.eventsort').on('change', function() {        
    window.location = SP_source() + 'admin/events?sort=' + this.value;    
  });

  $(".form_datetime").datetimepicker({
    format: "mm/dd/yyyy hh:ii"
  });

  // save/unsave the timelines by logged user
    $('.save-timeline').on('click',function(e){
      e.preventDefault();
      $.post(SP_source() + 'ajax/save-timeline', {timeline_id: $(this).data('timeline-id')}, function(data) {
         if (data.status == 200) {
            notify(data.message,'success');
          }
         else if (data.status == 201) {
          notify(data.message,'warning');
         }
     });
    });

  // To switch language
  $('.switch-language').on('click',function(e){
    e.preventDefault();
    $.post(SP_source() + 'ajax/switch-language', {language: $(this).data('language')}, function(data) {
       if (data.status == 200) {
          window.location = data.url;
        }
       else if (data.status == 201) {
        notify(data.message,'warning');
       }
   });
  });

  // $('.login-form').ajaxForm({
  //   url: SP_source() + 'login',
  //
  //   beforeSend: function() {
  //       login_form = $('.login-form');
  //       login_button = login_form.find('.btn-submit');
  //       login_button.attr('disabled', true);
  //       $('.login-progress').removeClass('hidden');
  //       $('.login-errors').html('');
  //   },
  //
  //   success: function(responseText) {
  //       alert('dddd');
  //     login_button.attr('disabled', false);
  //     $('.login-progress').addClass('hidden');
  //       if (responseText.status == 200) {
  //           window.location = responseText.url;
  //       } else {
  //           //console.log(responseText.message)
  //           var n = noty({
  //              text: responseText.message,
  //              layout: 'topRight',
  //              type : 'error',
  //              theme : 'relax',
  //              timeout:5000,
  //              animation: {
  //                      open: 'animated fadeIn', // Animate.css class names
  //                      close: 'animated fadeOut', // Animate.css class names
  //                      easing: 'swing', // unavailable - no need
  //                      speed: 500 // unavailable - no need
  //                    }
  //                  });
  //       }
  //
  //   }
  // });

    $(document).on('click','.fans-logo',function(e){
        e.preventDefault();
        $.confirm({
            title: 'Confirm!',
            content: 'Do you want to leave this fans site?',
            confirmButton: 'Yes',
            cancelButton: 'No',
            confirmButtonClass: 'btn-primary',
            cancelButtonClass: 'btn-danger',

            confirm: function(){
                $(location).attr('href', 'https://diaskedazwdiscounts.com');
            },
            cancel: function(){
                $(location).attr('href', '/');
            }
        });
    });

    // save/unsave the posts by logged user
    $('.save-post').on('click',function(e){
      e.preventDefault();
      postPanel = $(this).closest('.panel-post');
      $.post(SP_source() + 'ajax/save-post', {post_id: $(this).data('post-id')}, function(data) {
         if (data.status == 200) {
            notify(data.message,'success');
          }
         else if (data.status == 201) {
          notify(data.message,'warning');
         }
     });
    });

    // pin/unpin the posts by logged user
    $('.pin-post').on('click',function(e){
        e.preventDefault();
        postPanel = $(this).closest('.panel-post');
        $.post(SP_source() + 'ajax/pin-post', {post_id: $(this).data('post-id')}, function(data) {
            if (data.status == 200) {
                notify(data.message,'success');
            }
            else if (data.status == 201) {
                notify(data.message,'warning');
            }
        });
    });

    $(".modal-list-item .red-checkbox").on("change", function() {

        var elementId = "#" + this.id;

        $(elementId).attr('disabled', 'disabled');

        var typeId = this.id.substring(10);

        $.post(SP_source() + 'ajax/update-user-list', {list_type_id: typeId, saved_user_id: $("#saved-user-id").val()}, function(data) {

            if (data.status == 200) {

                $(elementId).removeAttr('disabled');
                notify(data.message,'success');
            }
            else {

                if ($(elementId).is(":checked"))
                    $(elementId).prop('checked', false);
                else
                    $(elementId).prop('checked', true);

                notify(data.message,'warning');
            }
        });
    });


    $( "#listsModal" ).on('shown.bs.modal', function(){
        console.log("ok");

        $.post(SP_source() + 'ajax/get-user-list', {saved_user_id: $("#saved-user-id").val()}, function(data) {

            if (data.status == 200) {
            }
            else {

            }
        });

    });

    $( "#newListModal" ).on('shown.bs.modal', function(){

        $("#listsModal").modal('hide');
    });

    $("#etNewListName").on("input", function() {
        var myLength = $("#etNewListName").val().length;

        if (myLength > 0)
            $("#saveNewList").removeAttr("disabled");
        else
            $("#saveNewList").attr("disabled", "disabled");
    });

    $("#cancelNewList").click(function() {
        $("#listsModal").modal("show");
        $("#etNewListName").val('');
    });

    $("#saveNewList").click(function() {

        $.post(SP_source() + 'ajax/add-new-user-list', {saved_user_id: $("#saved-user-id").val(), new_list_name: $("#etNewListName").val()}, function(data) {

            if (data.status == 200) {
                notify(data.message,'success');
                $("#newListModal").modal("hide");
                location.reload();
            }
            else if (data.status == 202) {
                notify(data.message,'warning');
            } else {

            }
        });
    });

    $("#etTipAmount").on("input", function() {
        var myLength = $("#etTipAmount").val().length;

        if (myLength > 0)
            $("#sendTip").removeAttr("disabled");
        else
            $("#sendTip").attr("disabled", "disabled");
    });

    $("#sendTip").click(function() {

        $.post(SP_source() + 'ajax/send-tip-post', {post_id: $("#post-id").val(), amount: $("#etTipAmount").val()}, function(data) {

            if (data.status == 200) {

                notify(data.message,'success');
                $("#sendTipModal").modal("hide");
                location.reload();
            }
            else {

            }
        });
    });

    $("input[name = 'sort-lists']").change(function() {

        $.post(SP_source() + 'ajax/get-lists-sort-by', {sort_by: $("input[name = 'sort-lists']:checked").val(), order_by: $("input[name = 'order-lists']:checked").val()}, function(data) {

            if (data.status == 200) {

                var userLists = data.user_lists;
                var userList;
                $(".my-lists").html("");

                var myListsHTML = "";

                for (userList of userLists) {

                    var myRow =
                        "                    <a href=\"" + base_url  + 'mylist/' + userList.id + "\">\n" +
                        "                        <div class=\"modal-mylist-item\">\n" +
                        "                            <span class=\"red-mylist-label\">" + userList.name + "</span>\n" +
                        "                            <span class=\"red-mylist-count-label\">" + userList.count + "</span>\n" +
                        "                        </div>\n" +
                        "                    </a>\n";

                    myListsHTML += myRow;
                }

                $(".my-lists").html(myListsHTML);
            }
            else {

            }
        });
    });


    $("input[name = 'order-lists']").change(function() {

        $.post(SP_source() + 'ajax/get-lists-sort-by', {sort_by: $("input[name = 'sort-lists']:checked").val(), order_by: $("input[name = 'order-lists']:checked").val()}, function(data) {

            if (data.status == 200) {

                var userLists = data.user_lists;
                var userList;
                $(".my-lists").html("");

                var myListsHTML = "";

                for (userList of userLists) {

                    var myRow =
                        "                    <a href=\"" + base_url  + 'mylist/' + userList.id + "\">\n" +
                        "                        <div class=\"modal-mylist-item\">\n" +
                        "                            <span class=\"red-mylist-label\">" + userList.name + "</span>\n" +
                        "                            <span class=\"red-mylist-count-label\">" + userList.count + "</span>\n" +
                        "                        </div>\n" +
                        "                    </a>\n";

                    myListsHTML += myRow;
                }

                $(".my-lists").html(myListsHTML);
            }
            else {

            }
        });
    });


    $("input[name = 'period-post']").change(function() {

        window.location = base_url  + current_username + '?p=' + $("input[name = 'period-post']:checked").val() + '&s=' + $("input[name = 'sort-profile-post']:checked").val() + '&o=' + $("input[name = 'order-profile-post']:checked").val();
    });

    $("input[name = 'sort-profile-post']").change(function() {

        window.location = base_url  + current_username + '?p=' + $("input[name = 'period-post']:checked").val() + '&s=' + $("input[name = 'sort-profile-post']:checked").val() + '&o=' + $("input[name = 'order-profile-post']:checked").val();
    });

    $("input[name = 'order-profile-post']").change(function() {

        window.location = base_url  + current_username + '?p=' + $("input[name = 'period-post']:checked").val() + '&s=' + $("input[name = 'sort-profile-post']:checked").val() + '&o=' + $("input[name = 'order-profile-post']:checked").val();
    });

    // Unsave a page from saved list of pages
    $('body').on('click','.unsave-timeline',function(e){
      e.preventDefault();
      follow_btn = $(this).closest('.follow-links');
      $.post(SP_source() + 'ajax/save-timeline', {timeline_id: $(this).data('timeline-id')}, function(data) {
         if (data.status == 200) {
            follow_btn.find('.follow').closest('.holder').slideToggle();
            notify(data.message,'success');
         }
     });
    });

  $('#link_other a').attr('target', '_blank');
    // $.fn.isolatedScroll = function() {
    //     this.bind('mousewheel DOMMouseScroll', function (e) {
    //         var delta = e.wheelDelta || (e.originalEvent && e.originalEvent.wheelDelta) || -e.detail,
    //             bottomOverflow = this.scrollTop + $(this).outerHeight() - this.scrollHeight >= 0,
    //             topOverflow = this.scrollTop <= 0;

    //         if ((delta < 0 && bottomOverflow) || (delta > 0 && topOverflow)) {
    //             e.preventDefault();
    //         }
    //     });
    //     return this;
    // };
        
    // show users modal
    $('body').on('click','.show-users-modal',function(e){
        e.preventDefault();
        // $(this).tooltip('hide');

        $.post(SP_source() + 'ajax/get-users-modal',{user_ids: $(this).data('users'), heading: $(this).data('heading')}, function(responseText) {
           if(responseText.status == 200) { 
               $('.modal-content').html(responseText.responseHtml);
           }
         });

        $('#usersModal').modal('show');
    }); 

    $('body').on('click','.edit-post',function(e){
        e.preventDefault();
        $.post(SP_source() + 'ajax/edit-post',{post_id: $(this).data('post-id'),}, function(responseText) {
           if(responseText.status == 200) { 
               $('.modal-content').html(responseText.data);
                jQuery("time.timeago").timeago();
           }
        });
        $('#usersModal').modal('show');
        setTimeout(function(){
                jQuery("time.timeago").timeago();
        },1000);
    }); 

    $('body').on('click','.btn-delete-user',function(){
        if(confirm('are you sure to delete?'))
        {
            window.location = base_url  + current_username + '/settings/deleteme';
        }
    });

    emojify.setConfig({img_dir : theme_url + 'images/emoji/basic'});
    // This will show modal when the settings are saved and flashed with overlay
    $('#flash-overlay-modal').modal();

    $( "#datepick2" ).datepicker();
    jQuery("time.timeago").timeago();
    $('time.timeago').show();

    // 2020/07/1
    $('#addPaymentForm').ajaxForm({
        url: SP_source() + $('#username').data('username') + '/settings/save-payment-details',
        beforeSubmit: function validate(formData, jqForm, options) {
            let error_message = '';
            if (!$.payform.validateCardNumber($('input[name="card_number"]').val())) {
                error_message += "Invalid Card Number\n";
            }
            if (!$.payform.validateCardCVC($('input[name="cvc"]').val())) {
                error_message += "Invalid CVC\n";
            }
            if (!$.payform.validateCardExpiry($('input[name="expiry"]').val())) {
                error_message += "Invalid Expire Date\n";
            }
            if (error_message != '') {
                alert(error_message);
                return;
            }
        },
    });



    $('.create-post-form').ajaxForm({
        url: SP_source() + 'ajax/create-post',
        beforeSubmit : function validate(formData, jqForm, options) {
          var form = jqForm[0];   
                
           //Uploading selected images on create post box 
            var hasFile = false;
            for(var i=0; i<=validFiles.length; i++){
              if(validFiles[i] != null)
              {
                hasFile = true
                var file = new File([validFiles[i]], validFiles[i].name  ,{type: validFiles[i].type});             
                formData.push({name:'post_images_upload_modified[]', value: file})
              }
            }
            validFiles = []; // making array empty           

           if (!hasFile && !$('.post-video-upload').val() && !form.description.value && !form.youtube_video_id.value && !form.location.value && !form.soundcloud_id.value) {
             alert("Your post cannot be empty!")

               return false;
           }
         
        },
        beforeSend: function() {
            create_post_form = $('.create-post-form');
            create_post_button = create_post_form.find('.btn-submit');
            create_post_button.attr('disabled', true).append(' <i class="fa fa-spinner fa-pulse "></i>');
            create_post_form.find('.post-message').fadeOut('fast');
        },

        success: function(responseText) {
          create_post_button.attr('disabled', false).find('.fa-spinner').addClass('hidden');
            if (responseText.status == 200)
            {
              $('.timeline-posts').prepend(responseText.data.original);
              jQuery("time.timeago").timeago();
              jQuery("time.timeago").show();
              $('.no-posts').hide();
              // Resetting the create post form after successfull message
              $('.video-addon').hide();
              $('.music-addon').hide();
              $('.emoticons-wrapper').hide();
              $('.user-tags-addon').hide();
              $('.user-tags-added').hide();
              $(".user-results").hide();
              create_post_form.find("input[type=text], textarea, input[type=file]").val("");
              create_post_form.find('.youtube-iframe').empty();
              create_post_form.find('#post-image-holder').empty();
              create_post_form.find('.post-images-selected').hide();
              create_post_form.find('#post-video-holder').empty();
              create_post_form.find('.post-videos-selected').hide();
              $('[name="youtube_video_id"]').val('');
              $('[name="youtube_title"]').val('');
              $('[name="soundcloud_id"]').val('');
              $('[name="soundcloud_title"]').val('');
              $('[name="user_tags[]"]').val('');
              $('.user-tags').val('');
              $('.user-tag-names').empty('');
              emojify.run();
              hashtagify();
              mentionify();              
              $('.post-description').linkify()
              $('[data-toggle="tooltip"]').tooltip();
              $('[name="description"]').focus();              
              notify('Your post has been successfully published');
            }
            else
            {
                $('.login-errors').html(responseText.message);
                notify(responseText.message);
            }

        }
    });

    // Toggle youtube input in create post form
    $('#videoUpload').on('click',function(e){
        e.preventDefault();
        $('.video-addon').slideToggle();
        if($(".music-addon").css("display") === "block"){
            $(".music-addon").slideUp(300);
        }
    });

    // Toggle add Tags input in create post form
    $('#addUserTags').on('click',function(e){
        e.preventDefault();
        $('.user-tags-addon').slideToggle();
        $('.user-tags-added').slideToggle();

    });
    
    

    // Toggle music input in create post form
    $('#musicUpload').on('click',function(e){
        e.preventDefault();
        $('.music-addon').slideToggle();
        if($(".video-addon").css("display") === "block"){
            $(".video-addon").slideUp(300);
        }
    });
     // Toggle location input in create post form
    $('#locationUpload').on('click',function(e){
        e.preventDefault();
        $('.location-addon').slideToggle();
    });
     // Toggle emoticons input in create post form
    $('#emoticons').on('click',function(e){
        e.preventDefault();
        var emoticonButton = $(this);
        if(!emoticonButton.hasClass('loaded-emoji'))
        {
          $.get( SP_source() + 'ajax/load-emoji')
              .done(function( data ) {
                  $('.emoticons-wrapper').html(data.data);
                  emojify.run();
                  emoticonButton.addClass('loaded-emoji')
          });  
        }
        
        $('.emoticons-wrapper').slideToggle();
    });

    // Fetch users id when keyup
    // $('.user-tags').on('keyup',function(){

    //     if($(".user-results").length){
    //         $(".user-results").show();
    //         $('.user-results').html('<i class="fa fa-spinner fa-pulse"></i> Fetching Sound Cloud results...');
    //     }
    //     else
    //     {
    //         $('.users-results-wrapper').html('<div class="list-group user-results"><i class="fa fa-spinner fa-pulse"></i> Fetching Sound Cloud results...</div>');
    //     }
        
    //     $.get( SP_source() + 'ajax/get-users-mentions' , { query: $('.user-tags').val() , csrf_token: $('[name="csrf_token"]').attr('content') })
    //         .done(function( data ) {
    //             $('.user-results').html('');
    //             $.each(data, function(key, value) {
    //                 $('.user-results').append('<a class="list-group-item user-result-item" data-user-id="' + value.id  + '" data-user-name="' + value.name  + '" href="#"> <img src="' +  value.image + '"> '+ value.name + '<span></span><div class="clearfix"></div></a>');
    //             });
    //     });
    // });


    // Fetch the youtube title and id when keyup
    $('#youtubeText').on('keyup',function(){
      var video_addon = $(this).closest('.video-addon');
      video_addon.find('.fa-film').addClass('fa-spinner fa-spin');
      $(this).closest('.video-addon').find('.fa-film').addClass('fa-spinner fa-spin');
      $.post( SP_source() + 'ajax/get-youtube-video' , { youtube_source: $('#youtubeText').val() , csrf_token: $('[name="csrf_token"]').attr('content') })
        .done(function( data ) {
          if(data.status == 200)
          {
              $('.youtube-iframe').html(data.message.iframe);
              $('[name="youtube_video_id"]').val(data.message.id);
              $('[name="youtube_title"]').val(data.message.title);
              video_addon.find('.fa-film').removeClass('fa-spinner fa-spin');
          }
      });
    });

    $('#youtubeText').bind('input propertychange', function() {
       var video_addon = $(this).closest('.video-addon');
        video_addon.find('.fa-film').addClass('fa-spinner fa-spin');
        $(this).closest('.video-addon').find('.fa-film').addClass('fa-spinner fa-spin');
        $.post( SP_source() + 'ajax/get-youtube-video' , { youtube_source: $('#youtubeText').val() , csrf_token: $('[name="csrf_token"]').attr('content') })
          .done(function( data ) {
            if(data.status == 200)
            {
                $('.youtube-iframe').html(data.message.iframe);
                $('[name="youtube_video_id"]').val(data.message.id);
                $('[name="youtube_title"]').val(data.message.title);
                video_addon.find('.fa-film').removeClass('fa-spinner fa-spin');
            }
        });
    });

    // Fetch the youtube title and id when keyup
    $('#soundCloudText').on('keyup',function(){
        var music_addon = $(this).closest('.music-addon');
      if($(".soundcloud-results").length){
          $(".soundcloud-results").show();
          music_addon.find('.fa-music').addClass('fa-spinner fa-spin');
          $(this).closest('.music-addon').find('.fa-music').addClass('fa-spinner fa-spin')
      }
      else
      {
        $('.soundcloud-results-wrapper').html('<div class="list-group soundcloud-results"></div>');
      }
      $.post( SP_source() + 'ajax/get-soundcloud-results' , { q: $('#soundCloudText').val() , csrf_token: $('[name="csrf_token"]').attr('content') })
        .done(function( responseText ) {
          if(responseText.status == 200)
          { 
            music_addon.find('.fa-music').removeClass('fa-spinner fa-spin');
            $('.soundcloud-results').html('');
            var soundCloud_results = jQuery.parseJSON(responseText.data);
            $.each(soundCloud_results, function(key, value) {
                $('.soundcloud-results').append('<a class="list-group-item soundcloud-result-item" data-soundcloud-id="' + value.id  + '" data-soundcloud-title="' + value.title  + '" href="#"> <img src="' +  value.artwork_url + '"> '+ value.title + '</a>');
            });

          }
      });
    });



    // Like/Unlike the post by user
    $(document).on('click','.soundcloud-result-item',function(e){
      e.preventDefault();
      $('#soundCloudText').val($(this).data('soundcloud-title'));
      $('.soundcloud-results').slideToggle();
      $('[name="soundcloud_id"]').val($(this).data('soundcloud-id'));
      $('[name="soundcloud_title"]').val($(this).data('soundcloud-title'));

    });

    // Add user to the post as tag
    $(document).on('click','.user-result-item',function(e){
      e.preventDefault();
      $('.user-tags-added').append('<input type="hidden" value="' + $(this).data('user-id') + '" name="user_tags[]" >');

        var values = $("input[name='user_tags[]']")
              .map(function(){return $(this).val();}).get();
        if(values.length <= 1)
        {
            $('.user-tags-added').find('.user-tag-names').append('<a href="#">' + $(this).data('user-name')  + '</a>');    
        }
        else
        {
            $('.user-tags-added').find('.user-tag-names').append(', <a href="#">' + $(this).data('user-name')  + '</a>');    
        }
        

    });


    // Like/Unlike the post by user
    $(document).on('click','.like-post',function(e){
      e.preventDefault();
      like_btn = $(this).closest('.panel-post');
      postId = $(this).data('post-id');
      $.post(SP_source() + 'ajax/like-post', {post_id: $(this).data('post-id')}, function(data) {
       if (data.status == '200') {
         if (data.liked == 'true') {
           like_btn.find('.like-'+postId).parent().addClass('hidden');
           like_btn.find('.unlike-'+postId).parent().removeClass('hidden');
           like_btn.find('.notify').parent().addClass('hidden');
           like_btn.find('.unnotify').parent().removeClass('hidden');
           $('.footer-list').find('.like1-'+postId).parent().remove();
           $('.like2-'+postId).empty();
           $('.footer-list').find('.like2-'+postId).removeClass('hidden').append('<a href="#" class=".show-likes">' + data.likecount + '<i class="fa fa-thumbs-up"></i></a>');
           $('.liked-post').text(data.post_likes);
           $('.tag-like-'+postId).removeClass('hidden');
           //$('.tag-like-'+postId).attr('data-users', postId);
            $('.circle-like-count-'+postId).text(data.likecount);

            var userIds = $('.tag-like-' + postId).attr('data-users');
            if (userIds == '' || userIds == null)
                $('.tag-like-' + postId).attr('data-users', data.user_id);
            else {
                userIds += ',' + data.user_id;
                $('.tag-like-' + postId).attr('data-users', userIds);
            }

         }else{
            like_btn.find('.like-'+postId).parent().removeClass('hidden');
            like_btn.find('.unlike-'+postId).parent().addClass('hidden');
            like_btn.find('.notify').parent().removeClass('hidden');
            like_btn.find('.unnotify').parent().addClass('hidden');
            $('.footer-list').find('.like1-'+postId).parent().remove();
            $('.like2-'+postId).empty();
            $('.footer-list').find('.like2-'+postId).removeClass('hidden').append('<a href="#" class=".show-likes">' + data.likecount + '<i class="fa fa-thumbs-down"></i></a>');
            $('.liked-post').text(data.post_likes);

            if (data.likecount <= 0) {
                $('.tag-like-' + postId).addClass('hidden');
                $('.tag-like-' + postId).attr('data-users', '');
            } else {
                $('.circle-like-count-'+postId).text(data.likecount);

                var userIds = $('.tag-like-' + postId).attr('data-users');
                if (userIds != '') {
                    var arr = userIds.split(',');
                    var newUserIds = '';
                    for (var i = 0; i < arr.length; i ++) {

                        if (arr[i] != postId) {
                            newUserIds += arr[i];
                            newUserIds += ',';
                        }
                    }

                    newUserIds = newUserIds.substr(0, newUserIds.length);

                    $('.tag-like-' + postId).attr('data-users', newUserIds);
                }
            }
        }
        }
      });
    });

    // Join/Joined the timeline user  by  logged user
    $('.join-user').on('click',function(e){
      e.preventDefault();
      join_btn = $(this).closest('.join-links');
      $.post(SP_source() + 'ajax/join-group', {timeline_id: $(this).data('timeline-id')}, function(data) {
         if (data.status == 200) {
             if (data.joined == true) {
                 join_btn.find('.join').parent().addClass('hidden');
                 join_btn.find('.joined').parent().removeClass('hidden');
             } else {
               join_btn.find('.join').parent().removeClass('hidden');
               join_btn.find('.joined').parent().addClass('hidden');
             }
         }
     });
    });

    // Join/Joined the event guests  by  logged user
    $('.join-guest').on('click',function(e){
      e.preventDefault();
      join_btn = $(this).closest('.join-links');
      $.post(SP_source() + 'ajax/join-event', {timeline_id: $(this).data('timeline-id')}, function(data) {
         if (data.status == 200) {
             if (data.joined == true) {
                 join_btn.find('.join').parent().addClass('hidden');
                 join_btn.find('.joined').parent().removeClass('hidden');
             } else {
               join_btn.find('.join').parent().removeClass('hidden');
               join_btn.find('.joined').parent().addClass('hidden');
             }
         }
     });
    });


    // Join group/Joined the timeline user  by  logged user
    $('.join-close-group').on('click',function(e){
      e.preventDefault();
      join_btn = $(this).closest('.join-links');
      $.post(SP_source() + 'ajax/join-close-group', {timeline_id: $(this).data('timeline-id')}, function(data) {
         if (data.status == 200) {
          if (data.joinrequest == true) {
            join_btn.find('.join').parent().addClass('hidden');
            join_btn.find('.joinrequest').parent().removeClass('hidden');
          } else if(data.join == true) {                
            join_btn.find('.joined').parent().addClass('hidden');
            join_btn.find('.join').parent().removeClass('hidden');
          }else{
            join_btn.find('.join').parent().removeClass('hidden');
            join_btn.find('.joinrequest').parent().addClass('hidden');
          }
         }
     });
    });


    /**
     * Begining of the Stripe checkout
     */
    let stripe;
    // Follow/UnFollow the timeline user  by  logged user
    $('body').on('click','.follow-user',function(e){
        e.preventDefault();

        if ($(this).data('price') == "0.00") {
            follow_btn = $(this).closest('.follow-links');
            $.post(SP_source() + 'ajax/follow-post-free', {timeline_id: $(this).data('timeline-id')}, function(data) {
                if (data.status == 200) {
                    if (data.followed == true) {
                        follow_btn.find('.follow').parent().addClass('hidden');
                        follow_btn.find('.unfollow').parent().removeClass('hidden');
                    } else {
                        follow_btn.find('.follow').parent().removeClass('hidden');
                        follow_btn.find('.unfollow').parent().addClass('hidden');
                    }
                    follow_btn.find('.unfollow').closest('.holder').slideToggle();
                }
            });
        }
        else {
            let timeline_id = $(this);
            $.post(SP_source() + 'checkout/config/' + $(this).data('timeline-id'), {}, function(data) {
                stripe = Stripe(data.publicKey, {
                    stripeAccount: data.stripe_id
                });
                // createCheckoutSession($(this));
                
                    console.log("timeline_id: " + timeline_id.data('timeline-id'));
                    $.post(SP_source() + 'checkout/create-checkout-session/' + timeline_id.data('timeline-id'), function(data) {
                        stripe
                            .redirectToCheckout({
                                sessionId: data.sessionId,
                            })
                            .then(function(result) {
                                follow_btn = timeline_id.closest('.follow-links');
                                $.post(SP_source() + 'ajax/follow-post', {timeline_id: timeline_id.data('timeline-id')}, function(data) {
                                    if (data.status == 200) {
                                        if (data.followed == true) {
                                            follow_btn.find('.follow').parent().addClass('hidden');
                                            follow_btn.find('.unfollow').parent().removeClass('hidden');
                                        } else {
                                            follow_btn.find('.follow').parent().removeClass('hidden');
                                            follow_btn.find('.unfollow').parent().addClass('hidden');
                                        }
                                        follow_btn.find('.unfollow').closest('.holder').slideToggle();
                                    }
                                });
                            });
                    });
        
        
            });
        }
    });

    $('body').on('click','.unfollow',function(e){
        e.preventDefault();

        if ($(this).data('price') == "0.00") {
            follow_btn = $(this).closest('.follow-links');
            $.post(SP_source() + 'ajax/unfollow-post-free', {timeline_id: $(this).data('timeline-id')}, function(data) {
                if (data.status == 200) {
                    if (data.followed == true) {
                        follow_btn.find('.follow').parent().addClass('hidden');
                        follow_btn.find('.unfollow').parent().removeClass('hidden');
                    } else {
                        follow_btn.find('.follow').parent().removeClass('hidden');
                        follow_btn.find('.unfollow').parent().addClass('hidden');
                    }
                    follow_btn.find('.unfollow').closest('.holder').slideToggle();
                }
            });
        }
        else {
            follow_btn = $(this).closest('.follow-links');
            $.post(SP_source() + 'ajax/unfollow-post', {timeline_id: $(this).data('timeline-id')}, function(data) {
                if (data.status == 200) {
                    if (data.followed == true) {
                        follow_btn.find('.follow').parent().addClass('hidden');
                        follow_btn.find('.unfollow').parent().removeClass('hidden');
                    } else {
                        follow_btn.find('.follow').parent().removeClass('hidden');
                        follow_btn.find('.unfollow').parent().addClass('hidden');
                    }
                    follow_btn.find('.unfollow').closest('.holder').slideToggle();
                }
            });
        }
    });

    // Create Checkout Session
    let createCheckoutSession = function(e) {
        console.log("timeline_id: " + $(this).data('timeline-id'));
        $.post(SP_source() + 'checkout/create-checkout-session/' + $(this).data('timeline-id'), function(data) {
            stripe
                .redirectToCheckout({
                    sessionId: data.sessionId,
                })
                .then(function(result) {
                    follow_btn = $(this).closest('.follow-links');
                    $.post(SP_source() + 'ajax/follow-post', {timeline_id: $(this).data('timeline-id')}, function(data) {
                        if (data.status == 200) {
                            if (data.followed == true) {
                                follow_btn.find('.follow').parent().addClass('hidden');
                                follow_btn.find('.unfollow').parent().removeClass('hidden');
                            } else {
                                follow_btn.find('.follow').parent().removeClass('hidden');
                                follow_btn.find('.unfollow').parent().addClass('hidden');
                            }
                            follow_btn.find('.unfollow').closest('.holder').slideToggle();
                        }
                    });
                });
        });
    }

    let handleResult = function (result) {
        if (result.error) {
        }
    };

    // $('.oauth-link').on('click', function(e) {
    //     let bank_details = $('.bank-details').serializeArray();
    //     alert(bank_details);
    //     $.get(SP_source() + 'checkout/get-oauth-link', bank_details, function (data) {
    //        if (data.url) {
    //            window.location = data.url;
    //        }
    //        else {
    //             console.log('data', data);
    //        }
    //     });
    // });
    /**
     * End of Stripe checkout
     */


    // Beginning of Jin's code
    /**
     * This will copy the innerHTML of an element to the clipboard
     * @param element reference OR string
     */
    function copyToClipboard(e) {
        var tempItem = document.createElement('input');

        tempItem.setAttribute('type','text');
        tempItem.setAttribute('display','none');

        let content = e;
        if (e instanceof HTMLElement) {
            content = e.innerHTML;
        }

        tempItem.setAttribute('value',content);
        document.body.appendChild(tempItem);

        tempItem.select();
        document.execCommand('Copy');

        tempItem.parentElement.removeChild(tempItem);
        notify('Copied to clipboard');
    }

    $('#copy-link').on('click', function(e) {
        e.preventDefault();
        alert('copy link');
        copyToClipboard(document.getElementById('copy-link-content'));
    })

    //End of Jin's code

    //Accept user request through join request tab in close group
    $('.accept-user').on('click',function(e){
      e.preventDefault();
      input_ids = $(this).data('user-id').split('-');
      user_id = input_ids[0];
      group_id = input_ids[1];

      accept_btn = $(this).closest('.follow-links');
      $.post(SP_source() + 'ajax/join-accept', {user_id: user_id,group_id: group_id}, function(data) {
       if (data.status == 200) {
         if (data.accepted == true) {
           accept_btn.find('.accept').closest('.holder').slideToggle();
         }
       }
     });
    });


//Accept follow request through join request tab in close group
    $('.accept-follow').on('click',function(e){
      e.preventDefault();           

      accept_btn = $(this).closest('.follow-links');
      $.post(SP_source() + 'ajax/follow-accept', {user_id: $(this).data('user-id')}, function(data) {
       if (data.status == 200) {
         if (data.accepted == true) {          
          accept_btn.find('.accept').closest('.holder').slideToggle();
         }
       }
     });
    });


  //Reject follow user request through join request tab in close group
 $('.reject-follow').on('click',function(e){
      e.preventDefault();           

      reject_btn = $(this).closest('.follow-links');
      $.post(SP_source() + 'ajax/follow-reject', {user_id: $(this).data('user-id')}, function(data) {
       if (data.status == 200) {
         if (data.rejected == true) {          
          reject_btn.find('.reject').closest('.holder').slideToggle();
         }
       }
     });
    });





  //Adding follower through add members tab in close group
  $(document).on('click','.add-member',function(e){
      e.preventDefault();
      input_ids = $(this).data('user-id').split('-');
      user_id = input_ids[0];
      group_id = input_ids[1];
      user_status = input_ids[2];

      add_btn = $(this).closest('.follow-links');
      $.post(SP_source() + 'ajax/add-memberGroup', {user_id: user_id,group_id: group_id,user_status: user_status}, function(data) {
       if (data.status == 200) {
         if (data.added == true) {
           add_btn.find('.add').closest('.holder').slideToggle();
         }
       }
     });
    });

  //Adding follower through add members tab in page
  $(document).on('click','.add-page-member',function(e){
      e.preventDefault();
      input_ids = $(this).data('user-id').split('-');
      user_id = input_ids[0];
      page_id = input_ids[1];      
      user_status = input_ids[2];

      add_btn = $(this).closest('.follow-links');
      $.post(SP_source() + 'ajax/add-page-members', {user_id: user_id,page_id: page_id,user_status: user_status}, function(data) {
       if (data.status == 200) {
         if (data.added == true) {
           add_btn.find('.add').closest('.holder').slideToggle();
         }
       }
     });
    });

  //Adding follower through add members tab in page
  $(document).on('click','.add-event-member',function(e){
      e.preventDefault();
      input_ids = $(this).data('user-id').split('-');
      user_id = input_ids[0];
      event_id = input_ids[1];      
      user_status = input_ids[2];

      add_btn = $(this).closest('.follow-links');
      $.post(SP_source() + 'ajax/add-event-members', {user_id: user_id,event_id: event_id,user_status: user_status}, function(data) {
       if (data.status == 200) {
         if (data.added == true) {
           add_btn.find('.add').closest('.holder').slideToggle();
         }
       }
     });
    });

  //Reject user request through join request tab in close group
    $('.reject-user').on('click',function(e){
      e.preventDefault();
      input_ids = $(this).data('user-id').split('-');
      user_id = input_ids[0];
      group_id = input_ids[1];

      reject_btn = $(this).closest('.follow-links');
      $.post(SP_source() + 'ajax/join-reject', {user_id: user_id,group_id: group_id}, function(data) {
       if (data.status == 200) {
         if (data.rejected == true) {
           reject_btn.find('.reject').closest('.holder').slideToggle();
         }
       }
     });
    });

    //Manage report user request 
    $('.manage-report').on('click',function(e){
      e.preventDefault();
      post_id = $(this).data('post-id');
      
      report_btn = $(this).closest('.list-inline');
      $.post(SP_source() + 'ajax/report-post', {post_id: post_id}, function(data) {
       if (data.status == 200) {
         if (data.reported == true) {
           //report_btn.find('.report').closest('.holder').slideToggle();
           $('#post'+post_id).slideToggle();
           notify('You have successfully reported the page');
         }
       }
     });

    });

    // smiley's on posts
    $(document).on('click','.smiley-post',function(e){
        e.preventDefault();
        textbox = $("#createPost");
        textbox.val(textbox.val() +' '+$(this).data('smiley-id'));
        textbox.focus();
    });


    // Page Like/Liked the timeline user  by  logged user
    $('.page-like').on('click',function(e){
      e.preventDefault();
      pagelike_btn = $(this).closest('.pagelike-links');
      $.post(SP_source() + 'ajax/page-like', {timeline_id: $(this).data('timeline-id')}, function(data) {
         if (data.status == 200) {
             if (data.liked == true) {
                 pagelike_btn.find('.like').parent().addClass('hidden');
                 pagelike_btn.find('.liked').parent().removeClass('hidden');
             } else {
               pagelike_btn.find('.like').parent().removeClass('hidden');
               pagelike_btn.find('.liked').parent().addClass('hidden');
             }
         }
     });
    });

    // Page Report/Reported the timeline user  by  logged user
    $('.page-report').on('click',function(e){
      e.preventDefault();
      pagereport_btn = $(this).closest('.pagelike-links');
      $.post(SP_source() + 'ajax/page-report', {timeline_id: $(this).data('timeline-id')}, function(data) {
         if (data.status == 200) {
             if (data.reported == true) {
                 pagereport_btn.find('.report').parent().addClass('hidden');
                 pagereport_btn.find('.reported').parent().removeClass('hidden');
                 notify('You have successfully reported');
             } else {
               pagereport_btn.find('.report').parent().removeClass('hidden');
               pagereport_btn.find('.reported').parent().addClass('hidden');
               notify('You have successfully unreported');
             }
         }
     });
    });

    // Comment Like/Liked the timeline user  by  logged user
    $(document).on('click','.like-comment',function(e){
      e.preventDefault();
      commentId = $(this).data('comment-id');
      commentlike_btn = $(this).closest('.comments-list');
      $.post(SP_source() + 'ajax/comment-like', {comment_id: $(this).data('comment-id')}, function(data) {
         if (data.status == 200) {
             if (data.liked == true) {
               commentlike_btn.find('.like').parent().addClass('hidden');
               commentlike_btn.find('.unlike').parent().removeClass('hidden');
               $('.comments-list').find('.like3-'+commentId).parent().addClass('hidden');
               $('.like4-'+commentId).empty();
               $('.comments-list').find('.like4-'+commentId).removeClass('hidden').append('<a href="#" class=".show-likes">' + data.likecount + '<i class="fa fa-thumbs-up"></i></a>');
             } else {
               commentlike_btn.find('.like').parent().removeClass('hidden');
               commentlike_btn.find('.unlike').parent().addClass('hidden');
               $('.comments-list').find('.like3-'+commentId).parent().addClass('hidden');
               $('.like4-'+commentId).empty();
               $('.comments-list').find('.like4-'+commentId).removeClass('hidden').append('<a href="#" class=".show-likes">' + data.likecount + '<i class="fa fa-thumbs-up"></i></a>');
             }
         }
     });
    });

    // Post Share/shared the timeline user  by  logged user
    $('body').on('click','.share-post',function(e){
      e.preventDefault();
      post_id = $(this).data('post-id');
      sharepost_btn = $(this).closest('.list-inline');
      $.post(SP_source() + 'ajax/share-post', {post_id: post_id}, function(data) {
         if (data.status == 200) {
             if (data.shared == true) {              
                sharepost_btn.find('.share').parent().addClass('hidden');
                sharepost_btn.find('.shared').parent().removeClass('hidden');
                $('.list-inline').find('.share1-'+post_id).parent().addClass('hidden');
                $('.share2-'+post_id).empty();
                $('.list-inline').find('.share2-'+post_id).removeClass('hidden').append('<a href="#" class=".show-share">' + data.share_count + '<i class="fa fa-share"></i></a>');
             } else {
                sharepost_btn.find('.share').parent().removeClass('hidden');
                sharepost_btn.find('.shared').parent().addClass('hidden');
                $('.list-inline').find('.share1-'+post_id).parent().addClass('hidden');
                $('.share2-'+post_id).empty();
                $('.list-inline').find('.share2-'+post_id).removeClass('hidden').append('<a href="#" class=".show-share">' + data.share_count + '<i class="fa fa-share"></i></a>');
             }
         }
     });
    });

    // Timeline Page Liked/Unliked the timeline user  by  logged user
    $(document).on('click','.page-liked',function(e){
      e.preventDefault();
      pagelike_btn = $(this).closest('.page-links');
      $.post(SP_source() + 'ajax/page-liked', {timeline_id: $(this).data('timeline-id')}, function(data) {
         if (data.status == 200) {
             if (data.like == true) {
              pagelike_btn.find('.pageliked').parent().addClass('hidden');
              pagelike_btn.find('.pagelike').parent().removeClass('hidden');
              pagelike_btn.find('.pagelike').closest('.holder').slideToggle();
             }
         }
     });
    });

    // Timeline Group Join/Joined the timeline user  by  logged user
    $(document).on('click','.group-join',function(e){
      e.preventDefault();
      pagelike_btn = $(this).closest('.page-links');
      timeline_id = $(this).data('timeline-id');
      $.confirm({
        title: 'Confirm!',
        content: 'Do you want to unjoin this group?',
        confirmButton: 'Yes',
        cancelButton: 'No',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',

        confirm: function(){
         $.post(SP_source() + 'ajax/group-join', {timeline_id: timeline_id}, function(data) {
           if (data.status == 200) {
               if (data.join == true) {
                pagelike_btn.find('.joined').parent().addClass('hidden');
                pagelike_btn.find('.join').parent().removeClass('hidden');
                pagelike_btn.find('.join').closest('.holder').slideToggle();
                notify('You have successfully unjoined this group','warning');
               }
           }
        });
       },
       cancel: function(){

       }
     });
      
    });

    //DeleteComment  the timeline user  by  logged user
    $('body').on('click','.delete-comment',function(e){
      e.preventDefault();
      commentdelete_btn = $(this).closest('.delete_comment_list');
      $.post(SP_source() + 'ajax/comment-delete', {comment_id: $(this).data('commentdelete-id')}, function(data) {
         if (data.status == 200) {
             if (data.deleted == true) {
                 commentdelete_btn.find('.delete_comment').closest('.comments').slideToggle();
                 notify('You have successfully deleted the comment','warning');
             }
         }
     });
    });

    //DeleteComment  the timeline user  by  logged user
    $('body').on('click','.delete-post',function(e){
      e.preventDefault();
      postPanel = $(this).closest('.panel-post');
      post_id = $(this).data('post-id');
      $.confirm({
        title: 'Confirm!',
        content: 'Are you sure to delete the post?',
        confirmButton: 'Yes',
        cancelButton: 'No',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',

        confirm: function(){
         $.post(SP_source() + 'ajax/post-delete', {post_id: post_id}, function(data) {
         if (data.status == 200) {
            postPanel.addClass('fadeOut');
            setTimeout(function(){
                postPanel.remove();
            },800);
            notify('You have successfully deleted the post','warning');
         }
        });
       },
       cancel: function(){

       }
     });
  });

    //Hide notification  the timeline user  by  logged user
    $('body').on('click','.hide-post',function(e){
      e.preventDefault();
      postPanel = $(this).closest('.panel-post');
      post_id = $(this).data('post-id');
      $.confirm({
        title: 'Confirm!',
        content: 'Are you sure to hide the post?',
        confirmButton: 'Yes',
        cancelButton: 'No',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',

        confirm: function(){
         $.post(SP_source() + 'ajax/post-hide', {post_id: post_id}, function(data) {
         if (data.status == 200) {
                postPanel.addClass('fadeOut');
                setTimeout(function(){
                    postPanel.remove();
                },800);
                notify('You have successfully hidden the post','warning');
         }
         });
       },
       cancel: function(){

       }
     });

    });

    //ReplyComment  the timeline user  by  logged user
    $(document).on('click','.show-comment-reply',function(e){
      e.preventDefault();
      $(this).parents('.main-comment').find('.comment-reply').slideToggle(100).find('.post-comment').focus();
    });

    //Removing member from group
    $(document).on('click','.remove-member',function(e){
      e.preventDefault();
      input_ids = $(this).data('user-id').split('-');
      user_id = input_ids[0];
      group_id = input_ids[1];
      commentdelete_btn = $(this).closest('.follow-links');

      $.confirm({
        title: 'Confirm!',
        content: 'Do you want to delete member?',
        confirmButton: 'Yes',
        cancelButton: 'No',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',

        confirm: function(){
         $.post(SP_source() + 'ajax/groupmember-remove', {user_id: user_id, group_id: group_id}, function(data) {
           if (data.status == 200) {
             if (data.deleted == true) {
               commentdelete_btn.find('.remove-member').closest('.holder').slideToggle();
               notify('You have successfully deleted the member','warning');
             }else if(data.deleted == false) {
              notify('Assign admin role for member and remove','warning');
             }
           }
         });
       },
       cancel: function(){

       }
     });

     }); 

     //Removing member from page
    $(document).on('click','.remove-pagemember',function(e){
      e.preventDefault();
      input_ids = $(this).data('user-id').split('-');
      user_id = input_ids[0];
      page_id = input_ids[1];
      commentdelete_btn = $(this).closest('.follow-links');

      $.confirm({
        title: 'Confirm!',
        content: 'Do you want to delete member?',
        confirmButton: 'Yes',
        cancelButton: 'No',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',

        confirm: function(){
         $.post(SP_source() + 'ajax/pagemember-remove', {user_id: user_id, page_id: page_id}, function(data) {
           if (data.status == 200) {
             if (data.deleted == true) {
               commentdelete_btn.find('.remove-pagemember').closest('.holder').slideToggle();
               notify('You have successfully deleted the member','warning');
             }else if(data.deleted == false) {
              notify('Assign admin role for member and remove','warning');
             }
           }
         });
       },
       cancel: function(){

       }
     });

     });  


    //Delete Page  the timeline user  by  logged user
    $(document).on('click','.delete-page',function(e){
      e.preventDefault();
      pagedelete_btn = $(this).closest('.deletepage');
      page_id = $(this).data('pagedelete-id');
      $.confirm({
        title: 'Confirm!',
        content: 'Do you want to delete page?',
        confirmButton: 'Yes',
        cancelButton: 'No',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',

        confirm: function(){
         $.post(SP_source() + 'ajax/page-delete', {page_id: page_id}, function(data) {
           if (data.status == 200) {
             if (data.deleted == true) {
               pagedelete_btn.find('.delete_page').closest('.deletepage').slideToggle();
               notify('Page deleted successfully');
             }
           }
         });
       },
       cancel: function(){

       }
     });
    });

    //Delete event list display by logged user
    $(document).on('click','.delete-event',function(e){
      e.preventDefault();
      eventdelete_btn = $(this).closest('.deleteevent');
      event_id = $(this).data('eventdelete-id');
      $.confirm({
        title: 'Confirm!',
        content: 'Do you want to delete event?',
        confirmButton: 'Yes',
        cancelButton: 'No',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',

        confirm: function(){
         $.post(SP_source() + 'ajax/event-delete', {event_id: event_id}, function(data) {
           if (data.status == 200) {
             if (data.deleted == true) {
               eventdelete_btn.find('.delete_event').closest('.deleteevent').slideToggle();
             }
           }
         });
       },
       cancel: function(){

       }
     });
    });

    //Delete notification by logged user
    $('.notification-delete').on('click',function(e){
      e.preventDefault();     
      notification_btn = $(this).closest('.notification-delete'); 
      notification_id = $(this).data('notification-id');
      $.confirm({
        title: 'Confirm!',
        content: 'Do you want to delete notification?',
        confirmButton: 'Yes',
        cancelButton: 'No',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',

        confirm: function(){
          $.post(SP_source() + 'ajax/notification-delete', {notification_id: notification_id}, function(data) {
            if (data.status == 200) 
            {
              if (data.notify == true) 
              {
                notification_btn.closest('tr').hide();
              } 
            }
          });
       },
       cancel: function(){

       }
     });
    });

    //Delete event on user timeline by logged user
    $(document).on('click','.event-report',function(e){
      e.preventDefault();
      eventdelete_btn = $(this).closest('.deleteevent');
      input_ids = $(this).data('event-id').split('-');
      event_id = input_ids[0];
      username = input_ids[1];
      $.confirm({
        title: 'Confirm!',
        content: 'Do you want to delete event?',
        confirmButton: 'Yes',
        cancelButton: 'No',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',

        confirm: function(){
         $.post(SP_source() + 'ajax/event-delete', {event_id: event_id}, function(data) {
           if (data.status == 200) {
             if (data.deleted == true) {
               window.location = SP_source() + username + '/events';
             }
           }
         });
       },
       cancel: function(){

       }
     });
    });

    // get/stop notifications in the timeline post by user
    $('body').on('click', '.notify-user', function (e) {
      e.preventDefault();
      notify_btn = $(this).closest('.list-inline');
      $.post(SP_source() + 'ajax/notify-user', {post_id: $(this).data('post-id')}, function(data) {
       if (data.status == 200) {        
         if (data.notified == true) {
           notify_btn.find('.notify').parent().addClass('hidden');
           notify_btn.find('.unnotify').parent().removeClass('hidden');
         } else {
           notify_btn.find('.notify').parent().removeClass('hidden');
           notify_btn.find('.unnotify').parent().addClass('hidden');
         }
       }
     });
    });

        // Post comments on the post
    $('body').on('keypress', '.post-comment', function (e) {
      if(e.keyCode==13)
      {

        e.preventDefault();

        var current_post = $(this).closest('.panel-post');
        var comment_id = $(this).data('comment-id');
        if($(this).val() ) {
          if(comment_id)
          {
            current_post = $(this).closest('.commented');
          }

          $.post(SP_source() + 'ajax/post-comment', {post_id: $(this).data('post-id'),comment_id: comment_id,description : $(this).val() }, function(responseText) {
           if (responseText.status == 200) {
                if(comment_id)
                {
                    // $(current_post).find('.comment-replies').show();
                    // $(current_post).find('.comment-replies').append(responseText.data.original);
                    // $(current_post).find('.commented').find('.post-comment').val('');
                    var commentTag = '.comment' + comment_id;
                    if($(commentTag).hasClass('has-replies'))
                    {
                        $(commentTag).find('.comment-replies').show();
                    }
                    else
                    {
                        $(commentTag).append('<li>' + 
                                                '<div class="comment-replies" style="">' + 
                                                    ' <ul class="list-unstyled comment-replys">' + 
                                                    '</ul>' + 
                                                '</div>' + 
                                            '</li>');
                        $(commentTag).addClass('has-replies');
                    }

                    $(commentTag).find('.comment-replies').find('.comment-replys').prepend(responseText.data.original);
                    $(commentTag).find('.post-comment').val('');

                }
                else
                {
                    $(current_post).find('div.post-comments-list').prepend(responseText.data.original);
                    $(current_post).find('.post-comment').val('');
                }

             $(current_post).find('.post-comment').val('');
             jQuery("time.timeago").timeago();
           }
         });
        }
      }
    });

    $(document).on('click','.show-comments',function(e){
        e.preventDefault();
        var comments_section = $(this).closest('.panel-footer').next('.comments-section');
        comments_section.slideToggle();
        setTimeout(function(){
            comments_section.find('.post-comment').focus();
        },100);
    });

    $(document).on('click','.show-all-comments',function(e){
        e.preventDefault();
        var all_comments = $(this).closest('.panel-post');
        all_comments.find('.comments-section').slideToggle();
    });

    $(document).on('click','.show-comment-replies',function(e){
      e.preventDefault();
      $(this).next().slideToggle();
    });
    

    // Change avatar button click event
    $(document).on('click','.change-avatar',function(e){
      e.preventDefault();
      $('.change-avatar-input').trigger('click');
    });

    $(document).on('change','.change-avatar-input',function(e){
      e.preventDefault();
      $('form.change-avatar-form').submit();
    });

    $('form.change-avatar-form').ajaxForm({
         url: SP_source() + 'ajax/change-avatar',

         beforeSend: function() {
             $('.user-avatar-progress').html('0%<br>Uploaded').fadeIn('fast').removeClass('hidden');
         },

         uploadProgress: function(event, position, total, percentComplete) {
             var percentVal = percentComplete+'%';


             $('.user-avatar-progress').html(percentVal+'<br>Uploaded');

             if (percentComplete == 100) {

                 setTimeout(function () {
                     $('.user-avatar-progress').html('Processing');
                     setTimeout(function () {
                         $('.user-avatar-progress').html('Please wait');
                     }, 2000);
                 }, 500);
             }
         },
         success: function(responseText) {

             if (responseText.status == 200) {
                 $('.timeline-user-avtar').find('img')
                     .attr('src', responseText.avatar_url)
                     .load(function() {
                         $('.user-avatar-progress').fadeOut('fast').addClass('hidden').html('');
                         $('.change-avatar-input').val();
                     });
             }
             else {
                 $('.user-avatar-progress').fadeOut('fast').addClass('hidden').html('');
                 $('.change-avatar-input').val();
                 notify(responseText.message,'warning');
             }
         }
     }); 


    // $('form.create-album-form').ajaxForm({
    //      url: SP_source() + 'ajax/create-album',

    //      beforeSend: function() {
    //      },

      
    //      success: function(responseText) {

    //          if (responseText.status == 200) {
                
    //          }
    //          else {
                
    //          }
    //      }
    //  });

    // Change cover button click event
    $(document).on('click','.change-cover',function(e){
      e.preventDefault();
      $('.change-cover-input').trigger('click');
    });

    $(document).on('change','.change-cover-input',function(e){
      e.preventDefault();
      $('form.change-cover-form').submit();
    });


    $("#createPost").mention({
        remote: SP_source() + 'ajax/get-users-mentions',
        limit : 10,
    });


    function hashtagify()
    {
        // Lets turn hashtags in the post clickable
        $('.text-wrapper').each(function() {
            $(this).html($(this).html().replace(
                /#([a-zA-Z0-9]+)/g,
                '<a class="hashtag" href="' + SP_source() + '?hashtag=$1">#$1</a>'
            ));
        });
    }
    hashtagify();
    emojify.run();


    function mentionify()
    {
        // Lets turn usernames in the post clickable
        $('.text-wrapper').each(function() {
            $(this).html($(this).html().replace(
                /@([a-zA-Z0-9]+)/g,
                '<a class="hashtag" href="' + SP_source() + '$1">@$1</a>'
            ));
        });    
    }
    mentionify();

    $('.post-description').linkify()
    $('form.change-cover-form').ajaxForm({
         url: SP_source() + 'ajax/change-cover',

         beforeSend: function() {
             $('.user-cover-progress').html('0%<br>Uploaded').fadeIn('fast').removeClass('hidden');
         },

         uploadProgress: function(event, position, total, percentComplete) {
             var percentVal = percentComplete+'%';


             $('.user-cover-progress').html(percentVal+'<br>Uploaded');

             if (percentComplete == 100) {

                 setTimeout(function () {
                     $('.user-cover-progress').html('Processing');
                     setTimeout(function () {
                         $('.user-cover-progress').html('Please wait');
                     }, 2000);
                 }, 500);
             }
         },
         success: function(responseText) {

             if (responseText.status == 200) {
                 $('.timeline-cover').find('img')
                     .attr('src', responseText.cover_url)
                     .load(function() {
                         $('.user-cover-progress').fadeOut('fast').addClass('hidden').html('');
                         $('.change-cover-input').val();
                     });
             }
             else {
                 $('.user-cover-progress').fadeOut('fast').addClass('hidden').html('');
                 $('.change-cover-input').val();
                notify(responseText.message,'warning');

             }
         }
     });

     //Image upload trigger on create post    // Change cover button click event
       $(document).on('click','#imageUpload',function(e){
         e.preventDefault();
         $('.post-images-upload').trigger('click');
       });

       $(document).on('click','#selfVideoUpload',function(e){
         e.preventDefault();
         $('.post-video-upload').trigger('click');
       });

       // $(document).on('click','#albumImageUpload',function(e){
       //   e.preventDefault();
       //   $('.album-images-upload').trigger('click');
       // });             

       // Image upload on create post on timeline
      $(document).on('change','.post-images-upload',function(e){
       e.preventDefault();
       var files = !!this.files ? this.files : [];             
       $('.post-images-selected').find('span').text(files.length);
       $('.post-images-selected').show('slow');
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
        
        // size validator
        var files = !!this.files ? this.files : [];

             var countFiles = $(this)[0].files.length;
             var imgPath = $(this)[0].value;
             var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
             var image_holder = $("#post-image-holder");
             image_holder.empty();
              if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") 
              {
                if (typeof(FileReader) != "undefined") 
                {
                   //loop for each file selected for uploaded.             
                   $.each(files, function(key,val) {    
                       
                       
                     if((files[key].size/1024)/1024 > 15)
                     {
                        $('.post-images-upload').val("");
                        alert('file size is more than 15 MB');
                     }
         
                     validFiles.push(files[key]); 
    
                     var reader = new FileReader();
                     reader.onload = function(e) {
                       var file = e.target;                  
                       $("<span class=\"pip\">" +
                        "<img class=\"thumb-image\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                        "<a data-id=" + (key) + " class='remove-thumb'><i class='fa fa-times'></i></a>" +
                        "</span>").appendTo(image_holder);                
                     }
                     image_holder.show();
                     reader.readAsDataURL(files[key]);             
                   });
                } else {
                   alert("This browser does not support FileReader.");
                }
              } else {
                 alert("Pls select only images");
              }
      });

    // Image upload on create post on timeline
    $(document).on('change','.post-video-upload',function(e){
        e.preventDefault();
        var files = !!this.files ? this.files : [];
        $('.post-video-selected').find('span').text(files.length);
        $('.post-video-selected').show('slow');
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        var countFiles = $(this)[0].files.length;
        var imgPath = $(this)[0].value;
        var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        var image_holder = $("#post-video-holder");
        image_holder.empty();
        if (extn == "mp4")
        {
            if (typeof(FileReader) != "undefined")
            {
                //loop for each file selected for uploaded.
                $.each(files, function(key,val) {
                    validFiles.push(files[key]);

                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var file = e.target;
                        $("<span class=\"pip\">" +
                            "<video width='60px' height='60px' class=\"thumb-image video-video-playe\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/><source src=\" + e.target.result + \"  type=\"video/mp4\"></video>"+
                            "<a data-id=" + (key) + " class='remove-thumb'><i class='fa fa-times'></i></a>" +
                            "</span>").appendTo(image_holder);
                    }
                    image_holder.show();
                    reader.readAsDataURL(files[key]);
                });
            } else {
                alert("This browser does not support FileReader.");
            }
        } else {
            alert("Pls select only images");
        }
    });

       // Removing selected image here
      $('body').on('click','.remove-thumb',function(e){
        e.preventDefault()
        var count = 0;
        var key = $(this).data('id');
        validFiles[key] = null;                
        $(this).parent(".pip").remove();

          $.each(validFiles, function(key,val) {
            if(val != null){
              count++;
            }
          });

        $('.post-images-selected').find('span').text(count);
      });

        
        $(document).on('change','.post-video-upload',function(e){
         e.preventDefault();
            var files = !!this.files ? this.files : [];

         if((files[0].size/1024)/1024 < 500)
         {

           $('.post-video-selected').find('span').text(files[0]['name']);
           $('.post-video-selected').show('slow');
           if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support 
         }
         else
         {
            $('.post-video-upload').val("");
            alert('file size is more than 500 MB');
         }

       });
        
       //  $(document).on('change','.album-images-upload',function(e){
       //   e.preventDefault();

       //   var files = !!this.files ? this.files : [];

       //   $('.post-images-selcted').find('span').text(files.length);
       //   $('.post-images-selcted').show('slow');
       //   if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

       //   var countFiles = $(this)[0].files.length;
       //   var imgPath = $(this)[0].value;
       //   var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
       //   var image_holder = $(".albums-list .row");
       //   image_holder.empty();
       //   if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
       //     if (typeof(FileReader) != "undefined") {
       //       //loop for each file selected for uploaded.
       //       for (var i = 0; i < countFiles; i++)
       //       {
       //         var reader = new FileReader();
       //         reader.onload = function(e) {

       //          image_holder.append('<div class="col-md-4 album-images">' + 
       //                              '<div class="album">' + 
       //                                  '<img src="' + e.target.result + '" alt="images">' + 
       //                                  '<a class="btn btn-remove"><i class="fa fa-times" aria-hidden="true"></i></a>' + 
       //                              '</div>' + 
       //                              '</div>');                 
       //         }
       //         image_holder.show();
       //         reader.readAsDataURL($(this)[0].files[i]);
       //       }
       //     } else {
       //       alert("This browser does not support FileReader.");
       //     }
       //   } else {
       //     alert("Pls select only images");
       //   }
       // });


      //  Navbar Search suggestions
       var bigSearchUrl = $('#navbar-search').data('url');

       $('#navbar-search').selectize({
           valueField: 'username',
           labelField: 'name',
           optgroupField: 'type',
           searchField: 'name',
           optgroups: [
               {value: 'user', label: 'Users'},
               {value: 'group', label: 'Groups'},
               {value: 'page', label: 'Pages'},
               {value: 'event', label: 'Events'}
           ],

           render: {
               option: function(item, escape) {

                //  get default images
                var item_image = "default-male-avatar.png";
                if(item.type=="group")
                {
                  item_image = "default-group-avatar.png";
                }
                else if(item.type == "page")
                {
                  item_image = "default-page-avatar.png";
                }
                else if(item.type == "event")
                {
                  item_image = "default-group-avatar.png";
                }


               if (item.avatar_url[0] != null)
               {
                   var photo_content = '<a class="media-left" href="'+ SP_source() + item.type + '/avatar/' + escape(item.avatar_url[0].source) + '">' +
                     '<img src="'+ SP_source() + item.type +'/avatar/' + escape(item.avatar_url[0].source) + '" alt="...">' +
                   '</a>';
               }
               else
               {
                   var photo_content = '<a class="media-left" href="#">' +
                     '<img src="'+ SP_source()  + item.type + '/avatar/' + item_image + '" alt="...">' +
                   '</a>';
               }

               if(item.about != null)
               {
                   var about = escape(item.about);
               }
               else
               {
                   var about = '(no description added)';
               }

               var verified = '';

               if(item.verified == 1)
               {
                  var verified = '<span class="verified-badge verified-small bg-success"> <i class="fa fa-check"></i></span>';
               }

             return '<div class="media big-search-dropdown">' + photo_content +
               '<div class="media-body">' +
                 '<h4 class="media-heading">' + escape(item.name) + verified + ' </h4>' +
                  '<p>' +  about +  '</p>' +               '</div>' +
             '</div>';

           },
           optgroup_header: function(data, escape) {
               return '<div class="optgroup-header">' + escape(data.label) + '</div>';
           }
           },
           onChange: function(value)
           {
              window.location.href = SP_source() +  value;
           },
           load: function(query, callback) {
               if (!query.length) return callback();
               $.ajax({
                   url: bigSearchUrl,
                   type: 'GET',
                   dataType: 'json',
                   data: {
                       search: query
                   },
                   error: function() {
                       callback();
                   },
                   success: function(res) {
                       callback(res.data);
                   }
               });
           }
       });
        
         //  Create post user tags
       var bigSearchUrl = $('#navbar-search').data('url');

        var selectizeUsers = $('#userTags').selectize({
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            plugins: ['remove_button'],
            render: {
                option: function(item, escape) {
                    if(item.about != null)
                    {
                        var about = escape(item.about);
                    }
                    else
                    {
                        var about = '(no description added)';
                    }

                    return '<div class="media big-search-dropdown">' + 
                        '<a class="media-left" href="#">' +
                            '<img src="'+ item.avatar + '" alt="...">' +
                        '</a>' +
                    '<div class="media-body">' +
                        '<h4 class="media-heading">' + escape(item.name) + '</h4>' +
                        '<p>' +  about +  '</p>' +               '</div>' +
                    '</div>';
                },
           
           },
           onChange: function(value)
           {
                $('[name="user_tags"]').val(value);
                // $('.user-tags-added').find('.user-tag-names').append('<a href="#">' + value  + '</a>');    
                        var selectize =selectizeUsers[0].selectize;
                  var values = selectize.items;
                
                  getUsersData();
           },
              load: function(query, callback) {
               if (!query.length) return callback();
               $.ajax({
                   url: base_url  + 'api/v1/users',
                   type: 'GET',
                   dataType: 'json',
                   data: {
                       search: query
                   },
                   error: function() {
                       callback();
                   },
                   success: function(res) {
                       callback(res.data);
                   }
               });
           }
       });

         function getUsersData() {
                var selectize = selectizeUsers[0].selectize;
            var values = selectize.getValue();
          var array = values.split(',');
          var selectedUserTags = ''
          $.each(array, function(key, value) {
            selectedUserTags = selectedUserTags  + '<a href="#">' + selectize.options[value].name  + '</a>, ';
          });

            $('.user-tags-added').find('.user-tag-names').html(selectedUserTags);    

          // $.each(values,function(value){
          // });
          // return $.map(values, function(value) {
          //   return selectize.options[value];
          // });
        }

       $('.timeline-posts').jscroll({
            // loadingHtml: '<img src="loading.gif" alt="Loading" /> Loading...',
            nextSelector: 'a.jscroll-next:last',
            callback : function()
            {
                emojify.run();
                hashtagify();
                mentionify();                
                $('.post-description').linkify()
                jQuery("time.timeago").timeago();
                $('time.timeago').show();
            }
        });

    

// Adding members to the group
      $('#add-members-group').on('keyup',function(){
        $('.group-suggested-users').empty();
        if($('#add-members-group').val() != null && $('#add-members-group').val() != "")
          groupId = $(this).data('group-id');
        $.post( SP_source() + 'ajax/get-users' , { searchname: $('#add-members-group').val() ,group_id: groupId, csrf_token: $('[name="csrf_token"]').attr('content') })
          .done(function( responseText ) {

            if(responseText.status == 200)
            {
              var users_results = responseText.data;

              $.each(users_results, function(key, value) {

                var user = value[0];
                var joinStatus = '';
                var user_id = '';
                var group_id = '';

                if(user.groups[0] != null)
                {
                   user_id = user.groups[0].pivot.user_id;
                   group_id = user.groups[0].pivot.group_id;

                  if(user.groups[0].pivot.status == "pending")
                  {
                    joinStatus = 'Join Requested';

                  }
                  else if(user.groups[0].pivot.status == "approved")
                  {
                    joinStatus = 'Joined';
                  }
                }
                else
                {
                  user_id = user.id;
                  group_id = groupId;
                  joinStatus = 'Join';
                }


                 if(user.avatar_id != null){
                    avatarSource = user.avatar_url[0].source;

                  }else{
                    avatarSource = "default-"+user.gender+"-avatar.png";
                  }

                  var verified = '';

                 if(user.verified == 1)
                 {
                    var verified = '<span class="verified-badge verified-small bg-success"> <i class="fa fa-check"></i></span>';
                 }

                  $('.group-suggested-users').append('<div class="holder">' +
                    '<div class="follower side-left">' +
                      '<a href="' +  SP_source() + user.username + '">' +
                        '<img src="' + SP_source() + 'user/avatar/'+ avatarSource +'" alt="images">' +
                      '</a>' +
                      '<a href="' +  SP_source() + user.username + '">' +
                        '<span>' + user.name + '</span>' +
                      '</a>' + verified +
                    '</div>' +
                    '<div class="follow-links side-right">' +
                      '<div class="left-col">' +
                        '<a href="#" class="btn btn-to-follow btn-default add-member  add" data-user-id="'+user_id+' - '+group_id+'-'+joinStatus+'">' + joinStatus + '</a>' +
                      '</div>' +
                    '</div>' +
                    '<div class="clearfix"></div>'+
                    '</div>');

              });
            }
        });
      });

      // Adding members to the event
      $('#add-members-event').on('keyup',function(){
        $('.event-suggested-users').empty();
        if($('#add-members-event').val() != null && $('#add-members-event').val() != "")
          eventId = $(this).data('event-id');
        $.post( SP_source() + 'ajax/get-members-invite', { searchname: $('#add-members-event').val() ,event_id: eventId, csrf_token: $('[name="csrf_token"]').attr('content') })
          .done(function( responseText ) {

            if(responseText.status == 200)
            {
              var users_results = responseText.data;

              $.each(users_results, function(key, value) {

                var user = value[0];
                var joinStatus = '';
                var user_id = '';
                var event_id = '';

                if(user.events[0] != null)
                {                  
                   user_id = user.events[0].pivot.user_id;
                   event_id = user.events[0].pivot.event_id;
                   joinStatus = 'Invited';                  
                }
                else
                {
                  user_id = user.id;
                  event_id = eventId;
                  joinStatus = 'Invite';
                }


                 if(user.avatar_id != null){
                    avatarSource = user.avatar_url[0].source;

                  }else{
                    avatarSource = "default-"+user.gender+"-avatar.png";
                  }

                  var verified = '';
                  if(user.verified == 1)
                   {
                      var verified = '<span class="verified-badge verified-small bg-success"> <i class="fa fa-check"></i></span>';
                   }

                  $('.event-suggested-users').append('<div class="holder">' +
                    '<div class="follower side-left">' +
                      '<a href="' +  SP_source() + user.username + '">' +
                        '<img src="' + SP_source() + 'user/avatar/'+ avatarSource +'" alt="images">' +
                      '</a>' +
                      '<a href="' +  SP_source() + user.username + '">' +
                        '<span>' + user.name + '</span>' +
                      '</a>' + verified +
                    '</div>' +
                    '<div class="follow-links side-right">' +
                      '<div class="left-col">' +
                        '<a href="#" class="btn btn-to-follow btn-default add-event-member  add" data-user-id="'+user_id+' - '+event_id+'-'+joinStatus+'">' + joinStatus + '</a>' +
                      '</div>' +
                    '</div>' +
                    '<div class="clearfix"></div>'+
                    '</div>');
              });
            }
        });
      });


//Adding members to the page

$('#add-members-page').on('keyup',function(){
        $('.page-suggested-users').empty();
        if($('#add-members-page').val() != null && $('#add-members-page').val() != "")
          pageId = $(this).data('page-id');
        $.post( SP_source() + 'ajax/get-members-join' , { searchname: $('#add-members-page').val() ,page_id: pageId, csrf_token: $('[name="csrf_token"]').attr('content') })
          .done(function( responseText ) {

            if(responseText.status == 200)
            {
              var users_results = responseText.data;

              $.each(users_results, function(key, value) {

                var user = value[0];
                var joinStatus = '';
                var user_id = '';
                var page_id = '';                

                if(user.pages[0] != null)
                {
                  user_id = user.pages[0].pivot.user_id;
                  page_id = user.pages[0].pivot.page_id;                  
                  joinStatus = 'Joined';                 
                }
                else
                {
                  user_id = user.id;
                  page_id = pageId;                  
                  joinStatus = 'Join';
                }


                 if(user.avatar_id != null){
                    avatarSource = user.avatar_url[0].source;

                  }else{
                    avatarSource = "default-"+user.gender+"-avatar.png";
                  }
                  var verified = '';

                 if(user.verified == 1)
                 {
                    var verified = '<span class="verified-badge verified-small bg-success"> <i class="fa fa-check"></i></span>';
                 }

                  $('.page-suggested-users').append('<div class="holder">' +
                    '<div class="follower side-left">' +
                      '<a href="' +  SP_source() + user.username + '">' +
                        '<img src="' + SP_source() + 'user/avatar/'+ avatarSource +'" alt="images">' +
                      '</a>' +
                      '<a href="' +  SP_source() + user.username + '">' +
                        '<span>' + user.name + '</span>' +
                      '</a>' + verified +
                    '</div>' +
                    '<div class="follow-links side-right">' +
                      '<div class="left-col">' +
                        '<a href="#" class="btn btn-to-follow btn-default add-page-member  add" data-user-id="'+user_id+' - '+page_id+'-'+joinStatus+'">' + joinStatus + '</a>' +
                      '</div>' +
                    '</div>' +
                    '<div class="clearfix"></div>'+
                    '</div>');

              });
            }
        });
      });


        $('.postmessage').on('keypress',function(e) {

            if(e.keyCode==13)
            {
                e.preventDefault();
                $.post(SP_source() + 'ajax/post-message',{conversation_id: $('.conversation-id').val(), description: $(this).val()}, function(responseText) {
                    $('.post-message').val('');
                    $('.coversations-thread').append(responseText.data);
                    jQuery("time.timeago").timeago();
                    emojify.run();
                 });
            }
        });
        // chat-list-toggle
        $('.chat-list-toggle').on('click',function(e){
            e.preventDefault();
            $('.chat-list').animate({width: 'toggle'});
            $('.chat-box').slideToggle();
        });
        // for timeline-list toggle in small screens
        $('.btn-status').on('click',function(e){
            // $('.timeline-list .list-inline').slideToggle('slow');
              // $('.timeline-list .list-inline').toggle('slow');
              e.preventDefault();
               if($(window).width() < 1200) {
                
                 $('.timeline-list .list-inline').slideToggle('slow');
              }
        });
        $(window).on('resize',function(){
            var win = $(this);
           if (win.width()>= 1200){
          $('.timeline-list .list-inline').show('slow');
        }
        });


        
        //smooth scroll intialization

        $(".smooth-scroll").mCustomScrollbar("scrollTo","bottom",{
            autoHideScrollbar:true,
            theme:"rounded",
            mouseWheel:{ preventDefault: true }
        });

        //tooltip intialization 
         $('[data-toggle="tooltip"]').tooltip();

        //date-picker
        $( "#datepicker" ).datepicker();
        $( "#datepicker1" ).datepicker();
        $( "#datepicker2" ).datepicker();

        // focus fix for input
        $('.input-group-addon').on('click',function(){
          $(this).parents('.input-group').find('.form-control').trigger('select');
        });
         $('.input-group .form-control').on('focus',function(){
          $(this).parents('.input-group').addClass('input-group-focus');
        });
        $('.input-group .form-control').on('blur',function(){
          $(this).parents('.input-group').removeClass('input-group-focus');
        });
        

function notify(message,type,layout)
{
     var n = noty({
       text: message,
       layout: 'bottomLeft',
       type : type ? type : 'success',
       theme : 'relax',
       timeout:5000,
       animation: {
           open: 'animated fadeIn', // Animate.css class names
           close: 'animated fadeOut', // Animate.css class names
           easing: 'swing', // unavailable - no need
           speed: 500 // unavailable - no need
       }
   });
};

function readURL(input, imageId) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(imageId).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#imgInp").change(function(){
    readURL(this,"#blah");
});

$(".settings_switch").change(function(){
  // $(this).parent('.email_follower').css("color",this.checked ? "red" : "#354052");
  alert('vj');
});

//WYSIWYG EDITOR(TinyMCE)
  tinymce.init({
  selector: '.mytextarea',
  theme: 'modern',
  height : 84,
  max_width: 884.25,
  plugins: [
    'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
    'searchreplace wordcount visualblocks visualchars  code fullscreen insertdatetime media nonbreaking',
    'save table contextmenu directionality emoticons template paste textcolor'
  ],
  content_css: '../../../themes/default/assets/css/tinymce.css',
  toolbar: 'bold italic underline strikethrough | link blockquote image code | bullist  numlist alignjustify aligncenter alignleft alignright', 
  menubar: true,
  statusbar: false,
  resize: true,

  });

  $('.add_selectize').selectize({
        plugins: ['drag_drop'],
        delimiter: ',',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });

  //Delete notification by logged user
    $('.allnotifications-delete').on('click',function(e){
      e.preventDefault();     
      notification_btn = $(this).closest('.allnotifications-delete');      
      $.confirm({
        title: 'Confirm!',
        content: 'Do you want to delete all notifications?',
        confirmButton: 'Yes',
        cancelButton: 'No',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',

        confirm: function(){
          $.post(SP_source() + 'ajax/allnotifications-delete', {}, function(data) {
            if (data.status == 200) 
            {
              if (data.allnotify == true) 
              {
                window.location = SP_source() + 'allnotifications';
              } 
            }
          });
       },
       cancel: function(){

       }
     });
    });

   // User timeline widgets showmore feature for group 
    $('.show-all-groups').on('click',function(e){
        e.preventDefault();
        $(this).parents('.all-groups').find('.my-best-pictures').animate({"max-height": "300px"},800)
        $(this).parents('.all-groups').find('.my-best-pictures').css("overflow-y","scroll")
        $(this).parents('.all-groups').find('.my-best-pictures').addClass('with-scroll')
        $(this).parents('.all-groups').find('.less-all-groups').show()
        $(this).hide()
    });
    $('.less-all-groups').on('click',function(e){
        e.preventDefault();
        $(this).parents('.all-groups').find('.my-best-pictures').animate({"max-height": "118px"},800)
        $(this).parents('.all-groups').find('.my-best-pictures').scrollTop("");
        $(this).parents('.all-groups').find('.my-best-pictures').css("overflow-y","hidden")
        $(this).parents('.all-groups').find('.my-best-pictures').removeClass('with-scroll')
        $(this).parents('.all-groups').find('.show-all-groups').show()
        $(this).hide()
    });

    // User timeline widgets showmore feature for pages 
    $('.show-all-pages').on('click',function(e){
        e.preventDefault();
        $(this).parents('.all-groups').find('.my-best-pictures').animate({"max-height": "300px"},800)
        $(this).parents('.all-groups').find('.my-best-pictures').css("overflow-y","scroll")
          $(this).parents('.all-groups').find('.my-best-pictures').addClass('with-scroll')
        $(this).parents('.all-groups').find('.less-all-pages').show()
        $(this).hide()
    });
    $('.less-all-pages').on('click',function(e){
        e.preventDefault();
        $(this).parents('.all-groups').find('.my-best-pictures').animate({"max-height": "118px"},800)
        $(this).parents('.all-groups').find('.my-best-pictures').scrollTop("");
         $(this).parents('.all-groups').find('.my-best-pictures').removeClass('with-scroll');
        $(this).parents('.all-groups').find('.my-best-pictures').css("overflow-y","hidden")
        $(this).parents('.all-groups').find('.show-all-pages').show()
        $(this).hide()
    });

    // User timeline widgets showmore feature for events 
    $('.show-all-events').on('click',function(e){
        e.preventDefault();
        $(this).parents('.all-groups').find('.my-best-pictures').animate({"max-height": "300px"},800)
        $(this).parents('.all-groups').find('.my-best-pictures').css("overflow-y","scroll")
          $(this).parents('.all-groups').find('.my-best-pictures').addClass('with-scroll')
        $(this).parents('.all-groups').find('.less-all-events').show()
        $(this).hide()
    });
    $('.less-all-events').on('click',function(e){
        e.preventDefault();
        $(this).parents('.all-groups').find('.my-best-pictures').animate({"max-height": "118px"},800)
        $(this).parents('.all-groups').find('.my-best-pictures').scrollTop("");
        $(this).parents('.all-groups').find('.my-best-pictures').css("overflow-y","hidden")
         $(this).parents('.all-groups').find('.my-best-pictures').removeClass('with-scroll')
        $(this).parents('.all-groups').find('.show-all-events').show()
        $(this).hide()
    });


    // $(document).on('click','#imageComment',function(e){
    //     e.preventDefault();        
    //     $(this).closest('.comment-form').find('.comment-images-upload').trigger('click');
    // });

    // $(document).on('change','.comment-images-upload',function(e){
    //    e.preventDefault();
    //    var files = !!this.files ? this.files : [];             
    //    //$('.comment-images-selected').find('span').text(files.length);
    //    //$('.comment-images-selected').show('slow');       
    //     if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

    //      var countFiles = $(this)[0].files.length;
    //      var imgPath = $(this)[0].value;
    //      var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
    //      var image_holder = $(this).closest('.comment-form').find("#comment-image-holder");         
    //      image_holder.empty();
    //       if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") 
    //       {
    //         if (typeof(FileReader) != "undefined") 
    //         {
    //           commentFiles = [];
    //            //loop for each file selected for uploaded.             
    //            $.each(files, function(key,val) {      
    //              commentFiles.push(files[key]); 

    //              var reader = new FileReader();
    //              reader.onload = function(e) {
    //                var file = e.target;
                                    
    //                $("<span class=\"pip\">" +
    //                 "<img class=\"thumb-image\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
    //                 "<a data-id=" + (key) + " class='remove-thumb'><i class='fa fa-times'></i></a>" +
    //                 "</span>").appendTo(image_holder);                
    //              }
    //              image_holder.show();
    //              reader.readAsDataURL(files[key]);             
    //            });

    //         } else {
    //            alert("This browser does not support FileReader.");
    //         }
    //       } else {
    //          alert("Pls select only images");
    //       }
    //   });
    $('.light-album').lightGallery({
      selector: '.btn-lightgallery'
    });


});

//deleting group
$(document).on('click','.delete-group',function(e){
      e.preventDefault();
      groupdelete_btn = $(this).closest('.deletegroup');
      group_id = $(this).data('groupdelete-id');
      $.confirm({
        title: 'Confirm!',
        content: 'Are you sure to delete group?',
        confirmButton: 'Yes',
        cancelButton: 'No',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',

        confirm: function(){
         $.post(SP_source() + 'ajax/group-delete', {group_id: group_id}, function(data) {
           if (data.status == 200) {
             if (data.deleted == true) {
               groupdelete_btn.find('.delete_group').closest('.deletegroup').slideToggle();
               notify('Group deleted successfully');
             }
           }
         });
       },
       cancel: function(){

       }
     });
    });
 $('.checkbox-panel .checkbox-label').on('click',function(){
      $(this).parents('.checkbox-panel').find('.checkbox-input').trigger('click')
        if($(this).parents('.checkbox-panel').find('input.checkbox-input').is(':checked')) {
          $(this).parents('.checkbox-panel').find('.widget-card.preview.with-slim').addClass('pd-10')
          $(this).parents('.checkbox-panel').find('.input-label').addClass('extra-space')
        } 
        else{
          $(this).parents('.checkbox-panel').find('.widget-card.preview.with-slim').removeClass('pd-10')
          $(this).parents('.checkbox-panel').find('.input-label').removeClass('extra-space')
        }
    })
  $('.checkbox-panel').on('click',function(){
      $(this).find('.checkbox-input').trigger('click')
      if($(this).find('input.checkbox-input').is(':checked')) {
        $(this).find('.widget-card.preview.with-slim').addClass('pd-10')
        $(this).find('.input-label').addClass('extra-space')
      } 
      else{
        $(this).find('.widget-card.preview.with-slim').removeClass('pd-10')
        $(this).find('.input-label').removeClass('extra-space')
      }
    })
  // user profile page unjoin the timeline user  by  logged user
    $(document).on('click','.unjoin-page',function(e){
      e.preventDefault();
      timeline_id = $(this).data('timeline-id')
      pagelike_btn = $(this).closest('.page-unjoin');
      
      $.confirm({
        title: 'Confirm!',
        content: 'Are you sure to unjoin this page?',
        confirmButton: 'Yes',
        cancelButton: 'No',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',

        confirm: function(){

          $.post(SP_source() + 'ajax/unjoinPage', {timeline_id: timeline_id}, function(data) {
           if (data.status == 200) {
             if (data.join == true) {

              pagelike_btn.closest('.holder').slideToggle();
              notify('You have successfully unjoined the page','warning');
            }
          }
        });
        },
        cancel: function(){

        }
      });

    });

    // Timeline page unjoin the timeline user  by  logged user
    $(document).on('click','.unjoin-page-timeline',function(e){
      e.preventDefault();
      timeline_id = $(this).data('timeline-id')
      
      pagelike_btn = $(this).closest('.page-unjoin');

      $.confirm({
        title: 'Confirm!',
        content: 'Are you sure to unjoin this page?',
        confirmButton: 'Yes',
        cancelButton: 'No',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',

        confirm: function(){

          $.post(SP_source() + 'ajax/unjoinPage', {timeline_id: timeline_id}, function(data) {
           if (data.status == 200) {
             if (data.join == true) {

              $('.user-profile-buttons').addClass('hidden');
              notify('You have successfully unjoined the page','warning');

            }
          }
        });
        },
        cancel: function(){

        }
      });

    });

