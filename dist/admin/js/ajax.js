//ajax

   function getJSON(url,data,callback){
      $.ajax({
        xhr: function()
          {
            var xhr = new window.XMLHttpRequest();
            //Download progress
            xhr.upload.addEventListener("progress", function(evt){
                  loading(evt);
            }, false);
            return xhr;
        },
        type: "POST",
        dataType: "json",
        context: this,
        url: './'+url, //Relative or absolute path to ajax-index.php file
        data: data,
        beforeSend: function( xhr ) {
            $('select,radio,input,button,textarea,a').prop('disabled', true);
            console.log('disabled start');
        },
        success: function(rs) {
          $('select,radio,input,button,textarea,a').prop('disabled', false);
          console.log('success');
          callback(rs);
        },
        complete:function(){
          $('select,radio,input,button,textarea,a').prop('disabled', false);
        }
      });
   }

   function postUpload(url,data,callback){
        $.ajax({
              url: "./"+url, // Url to which the request is send
              type: "POST",             // Type of request to be send, called as method
              data: data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
              contentType: false,       // The content type used when sending data to the server.
              cache: false,             // To unable request pages to be cached
              processData:false,        // To send DOMDocument or non processed data file it is set to false
              success: function(data)   // A function to be called if request succeeds
              {
                callback(data);
              }
         });
   }

   
   function loading(evt){
      var percentComplete = Math.ceil(evt.loaded / evt.total)*100;
      if($('#loadingblackbg').length){
         $('#loadingblackbg').fadeIn();
      }else{
        $('.loading').css('display','block').wrap('<div id="loadingblackbg" class="blackbg">');
      }
      if (percentComplete === 100) {
          $('#loading').parent().fadeOut();
      }
   }

