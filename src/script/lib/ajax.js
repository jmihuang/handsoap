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
        url: './'+url, //Relative or absolute path to ajax-index.php file
        data: data,
        beforeSend: function( xhr ) {
            $('input,button,textarea').prop('disabled', true);
            console.log('disabled start');
        },
        success: function(rs) {
          console.log('success');
          callback(rs);
        },
        complete:function(){
          $('input,button,textarea').delay(800).prop('disabled', false);
        }
      });
   }

