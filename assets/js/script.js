//change: highlight ticker with thubnail hover and vice versa
//change: honour seekPoints
//change: load related videos videoId along with a video and update UI (excluding related videos) on click rather than on dataload


//Disable Text Selection
(function($) {
    $.fn.disableSelection = function() {
        return this.attr('unselectable', 'on').css('user-select', 'none').on('selectstart', false);
    };
})(jQuery);

//Extract GET parameters
$.extend({
    getUrlVars: function() {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }

        return vars;
    },

    getUrlVar: function(name){
        return $.getUrlVars()[name] || 0;
    }
});

//Drop
(function($) {
	$.drop = function() {

        var scrollTicker = true,
			videoHistory = new Array(),
            data = {},
            current_video = null;

        var paymentDialogContent = {
        	"headerIcon":"assets/images/star.png",
			"headerText":"Premium Content",
    		"contentHeader": "Phone Transaction",
			"content": "Buy this video for 39 cents using your phone. No accounts necessary for the transaction and we don't store your information",
			"buttonText": "Send Code"
		};

        //Tooltips
        jQuery.fn.addToolTip = function(tip) {

            $(this).mouseover(function(e) {
                $('body').children('div#tooltip').remove(); //Just in case

                $('body').append('<div id="tooltip" class="shadow"><div class="tipBody textShadow">' + tip + '</div></div>');

                $('#tooltip').fadeIn('500');
                $('#tooltip').fadeTo('10',0.9);

            })

            .mousemove(function(e) {
                $('#tooltip').css('top', e.pageY + 10 );
                $('#tooltip').css('left', e.pageX + 20 );
            })

            .mouseout(function() {
                $('body').children('div#tooltip').remove();
            });
        }

        var flushContents = function() {
			$('body').children('div#tooltip').remove();
			$(".heading_wrapper .heading").empty();
			$(".heading_wrapper .news_detail").empty();
            $('#video_player').tubeplayer('destroy');
            $(".ticker_container").empty();
			$(".ticker_container").html('<ul id="ticker"></ul>');
            $("#thumbnail_container").empty();
        };

		var addRelatedVideo = function(relatedVideo) {

            var index = $("#ticker li").length;

            var ticker = $($("#ticker-template").html());

            ticker.find(".serial").html(index+1);
            ticker.find(".title").html(relatedVideo.title);
            ticker.find(".news_detail").html(relatedVideo.description);
            ticker.addClass("videoId-"+index+"-"+relatedVideo.id);

            $("#ticker").append(ticker);

            var thumbnail = $($("#thumbnail-template").html());

            thumbnail.find(".thumbnail_image").attr("src", "assets/images/thumbnails/"+relatedVideo.thumbnail);

            if(relatedVideo.premium == 1) {
                thumbnail.find(".thumbnail_overlay_star").css("display", "block");
            }

            thumbnail.addClass("videoId-"+index+"-"+relatedVideo.id);
			thumbnail.addToolTip(relatedVideo.title);
			thumbnail.css('z-index',index+2);

            $("#thumbnail_container").append(thumbnail);
        };

        var showDialog = function(dialogContentObject) {

            var dialog = $($("#dialog-template").html());

            dialog.find(".dialog-header img").attr("src", dialogContentObject.headerIcon);
            dialog.find(".dialog-header-text").html(dialogContentObject.headerText);

            //Page 1
            dialog.find(".page-1 .dialog-content-header").html(dialogContentObject.contentHeader);
            dialog.find(".dialog-content").html(dialogContentObject.content);
            dialog.find(".sendCodeToTelephone").html(dialogContentObject.buttonText);

            //Page 2 .. Hard coding
            dialog.find(".page-2 .dialog-content-header").html("Enter SMS Code");
            dialog.find(".page-2 .dialog-content").html("Enter the 4-digit SMS code to buy this video for 39 cents");
            dialog.find(".verifyCode").html("Verify Code");
            dialog.find(".cancelDialog").html("Cancel");

            //Page 3 .. Hard coding
            dialog.find(".page-3 .dialog-content-header").html("Video URL");
            dialog.find(".page-3 .dialog-content").html("Please follow the link to access the video you have just purchased.");
            dialog.find(".videoURL").html("Watch the video!");

            dialog.disableSelection();

            $("#container").append(dialog);
        };

        var loadVideo = function() {
            var video = (data.premium == 1) ? data.video2 : data.video;

            var options = {
                width: 500,
                allowFullScreen: true,
                initialVideo: video,
                start: data.start,
                showControls: true,
                autoPlay: true
            };

            $('#video_player').tubeplayer(options);
        };

        var loadVideoByLease = function() {
            $.getJSON('lease.php?id='+data.video, function(result) {
              if(result.url)
                data.video2 = result.url;
                loadVideo();
            });
        }

        var handleVideo = function() {
            if(data.premium == 1) {
                if(data.video) {
                    loadVideoByLease();
                }
                else {
                    showDialog(paymentDialogContent);
                }
            }
            else {
                loadVideo();
            }
        };

        var init = function() {
            handleVideo();

			$("#nav_bar .heading").html(data.title);
			$("#nav_bar .news_detail").html(data.description);

			if(!$("ul#ticker").is(".liScrollnewsticker")){ //if liScroll is not called yet
				$("ul#ticker").liScroll({travelocity: 0.05});
			}

			$("#left_nav_button").unbind('click');
			$("#left_nav_button").click(function() {

                previousVideoId = videoHistory.pop();
                previousVideoHeading = data.heading;

                $('#container .dialog-wrapper').remove();

				loadDropPlayer(previousVideoId);

				if(videoHistory.length==0) {
					$(this).hide();
				}
				else {
					//$("#left_nav_button").addToolTip(previousVideoHeading);
				}
	        });


			$(".videoChangeButton").unbind('click');
            $('.videoChangeButton').click(function() {

                var tmp = $(this).attr('class').split('-');
                var index = tmp[1];
				var nextVideoId = tmp[2];
				var nextVideoHeading = data.heading;

                $('#container .dialog-wrapper').remove();

                videoHistory.push(data.id);

				loadDropPlayer(nextVideoId);

				$("#left_nav_button").show();
				//$("#left_nav_button").addToolTip(nextVideoHeading);
            });

            $('.sendCodeToTelephone').click(function() {
				$(".page-1").slideUp();
				$(".page-2").slideDown();
            });

            $('.verifyCode').click(function() {

				if($(".code-field").val() == 1234) {
                    $.getJSON("new_lease.php?id="+data.id, function(result) {
                        if(result.lease) {
                            data.video = result.lease;
                            $(".page-2").slideUp();
                            $(".page-3").slideDown();
                        }
                    });

				}
				else{
					alert("Incorrect code");
				}

            });

            $('.cancelDialog').click(function() {
				$(".page-2").slideUp();
				$(".page-1").slideDown();
            });

            $('.videoURL').click(function() {
                $('#container .dialog-wrapper').remove();
                loadVideoByLease();
            });

        };

        return {
			initWithData: function(d) {

				data = d;

                flushContents();

				numberOfRelatedVideos = data.relatedVideos.length;

				if (data.relatedVideos.length !=0) {
					$("#ticker_panel:hidden").slideDown();
					$("#thumbnail_panel:hidden").slideDown();
					$("#no_more_related_videos").slideUp();
					$(data.relatedVideos).each(function(index, relatedVideo) {
                    	addRelatedVideo(relatedVideo);
                	});
 				} else {
					$("#ticker_panel").slideUp();
					$("#thumbnail_panel").slideUp();
					$("#no_more_related_videos").slideDown();
				}

                init();
			},
			connectLoadDropPlayer: function(passedLoadDropPlayer){
				loadDropPlayer = passedLoadDropPlayer;
			}
        };
    }();

})(jQuery);


$(document).ready(function() {

	var loadDropPlayer = function(videoId){

		$.getJSON("video.php?id="+videoId, function(data) {
			$.drop.initWithData(data);
	    });
	}

    $.drop.connectLoadDropPlayer(loadDropPlayer);

    var video_id = $.getUrlVar('id');
    if(video_id === 0)
        video_id = 1;

    loadDropPlayer(video_id);
});
