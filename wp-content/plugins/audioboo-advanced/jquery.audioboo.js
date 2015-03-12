jQuery.fn.audioboo = function(clip_id) {

    /* IE caches too much */
    var date = new Date();
    var _this = this;
    jQuery.ajax({
      'url': 'http://api.audioboo.fm/audio_clips/'+clip_id+'.jsonp?_='+date.getTime(),
      'dataType': 'jsonp',
      cache: false,
      contentType: "application/json",
      success: function(data){
        if(swfobject.hasFlashPlayerVersion("1")) {
          _this.flash({
            'swf': 'http://boos.audioboo.fm/swf/fullsize_player.swf',
            'height': 129,
            'width': 300,
            'flashvars': {
                'scale': 'noscale',
                'salign': 'lt',
                'bgColor': '#000000',
                'allowScriptAccess': 'always',
                'wmode': 'window',
                'mp3': data.body.audio_clip.urls.high_mp3,
                'mp3Title': data.body.audio_clip.title,
                'mp3LinkURL': data.body.audio_clip.urls.high_mp3,
                'rootID': 'boo_player_1',
                'mp3Author': data.body.audio_clip.user.username
            }
          });
        } else {
          _this.html('<iframe style="margin: 0px; padding: 0px; border: none; display: block; width: 400px; height: 145px;" allowtransparency="allowtransparency" cellspacing="0" frameborder="0" hspace="0" marginheight="0" marginwidth="0" scrolling="no" vspace="0" src="'+data.body.audio_clip.urls.detail+'/embed" title="Audioboo player"></iframe>');
        }
      }
    });
}