(function($) {
    "use strict";
     $(document).on('ready', function() {	
		
		$('.nftmax__sicon').on( "click", function(){
			$('.nftmax-smenu ,.nftmax-header ,.nftmax-adashboard').toggleClass('nftmax-close');
		});	
		

		    /* sidebar nav events */
			$(".sidebar_nav .has-child ul").show();
			$(".sidebar_nav .has-child  ul").hide();
			$(".sidebar_nav .has-child >a").on("click", function (e) {
			  e.preventDefault();
			  $(this).parent().next("has-child").slideUp();
			  $(this).parent().parent().children(".has-child").children("ul").slideUp();
			  $(this).parent().parent().children(".has-child").removeClass("open");
			  if ($(this).next().is(":visible")) {
				$(this).parent().removeClass("open");
			  } else {
				$(this).parent().addClass("open");
				$(this).next().slideDown();
			  }
			});

		
		
		
	});


      let testToggler = document.getElementsByClassName("icon-dropdown");
      let togglerFunc = (event) => {
        let name = event.target?.attributes["data-name"]?.value;
		
		
	
        for (let i = 0; i < testToggler.length; i++) {
          if (name === testToggler[i].attributes["data-name"]?.value) {
            document.getElementById(name)?.classList.add("active-icon");
          } else {
            document
              .getElementById(testToggler[i]?.attributes["data-name"]?.value)?.classList?.remove("active-icon");
          }
        }
      };
      for (let i = 0; i < testToggler.length; i++) {
        testToggler[i].addEventListener("click", (e) => {
          togglerFunc(e);
        });
		
      }

	  document.getElementsByTagName("html")[0].addEventListener("click", (e) => {
		togglerFunc(e);
	  });
  







	$(document).on("click", ".header__area-menubar-right-sidebar-popup-icon", function () {
				e.preventDefault()
		$('.header__area-menubar-right-sidebar-popup').addClass('active');
		$('.sidebar-overlay').addClass('show');
	  });
	  $(document).on("click", ".header__area-menubar-right-sidebar-popup .sidebar-close-btn", function () {
				e.preventDefault()
		$('.header__area-menubar-right-sidebar-popup').removeClass('active');
		$('.sidebar-overlay').removeClass('show');
	  });



///Editor
function QuillIsExists() {
	const editorOne = document.querySelector("#editor");
	const editorTwo = document.querySelector("#editor2");
	var toolbarOptions = [
	  [{ header: [1, 2, 3, 4, 5, 6, false] }],
	  ["bold", "italic", "underline"], // toggled buttons
	  [{ list: "ordered" }, { list: "bullet" }],
	  ["link", "image"],
	];
	if (editorOne) {
	  var editor = new Quill("#editor", {
		modules: {
		  toolbar: toolbarOptions,
		},
		theme: "snow",
	  });
	} else if (editorTwo) {
	  let editorTwo = new Quill("#editor2", {
		modules: {
		  toolbar: "#toolbar2",
		},
		theme: "snow",
	  });
	} else {
	  return false;
	}
  }
  QuillIsExists();















	
$('.my-walet-item-box-img-slick').slick({
	
	fade: true,
	slidesToShow: 1,
	slidesToScroll: 1,
	dots:true,
	arrows:false,
	autoplay:true,
	autoplaySpeed:2500,
	speed:500,
	cssEase: 'linear'
  });
	
$('.my-walet-two-slick').slick({
	
	fade: true,
	slidesToShow: 1,
	slidesToScroll: 1,
	dots:true,
	arrows:false,
	autoplay:true,
	autoplaySpeed:2500,
	speed:500,
	cssEase: 'linear'
  });







	





		  



})(jQuery);