/**
 * @author Edwin
 * https://www.youtube.com/watch?v=HsEw2i4wQMM
 */

var MyHome = (function(MyHome) {
	console.log('loading statusfeed'); 
	
	var template = function(name,partials) {
		
		// for partials documentation see https://github.com/janl/mustache.js/
		if(typeof partials !== "undefined") {
			if($.isArray(partials)) {
				//TODO: compile array
			} else {
				Mustache.compilePartial(partials,$('#'+partials+'-template').html());
			}
		}
		return Mustache.compile($('#'+name+'-template').html());
	};

	MyHome.Userfeed = Backbone.Model.extend({ });
	
	MyHome.Userfeeds = Backbone.Collection.extend({
	  	initialize: function(){
  			//console.log('init feeds collection');
  		},
	  	model:MyHome.Userfeed,
	  	url:'/api/v1/statusfeed.json'
	}); 
	
	MyHome.UserFeedsView = Backbone.View.extend({
		//el: '.new-users',
		tagName:'div',
		template: template('wall-list'),
		initialize: function() {
			this.feeds = new MyHome.Userfeeds();
			this.feeds.on('reset', this.render, this);
			this.feeds.fetch();
		},
		render: function() {
			this.$el.html(this.template(this));
			var form = new MyHome.Userfeeds.Form({collection: this.feeds});
        	//$('#feeds-container').html(form.render().el);
        	$('#statusUpdateForm').html(form.render().el);
			this.feeds.each(this.renderNewFeed, this);
			$("abbr.timeago").timeago();
      		return this;
		},
		renderNewFeed:function(userFeed) {
			//console.log(user);
			//var form = new MyHome.Userfeeds.Form({collection: this.feeds});
        	//$('#feeds-container').html(form.render().el);
			var view = new MyHome.UserFeedView({model: userFeed});
			$('#feeds-container').append(view.render().el);
			//$('#feeds-container').append(view.render().el);
			
			//$('div.messageItem').click(function(){
				//document.location.href = $(this).attr('rel');
			//});
		}
		//username: function() { return this.model.get('user_username'); }
	});
	
	
	MyHome.UserFeedView = Backbone.View.extend({
  		tagName: 'div',
  		className: 'wall-unit',
    	template: template('wall-unit','statusfeedCommentUnit'), //template and partial name
    	render: function() {
			this.$el.html(this.template(this));
			return this;
		},
		events:{
			'click .statusfeed_commentlink': 'commentClicked',
			'click .statusfeed_likelink': 'likeClicked',
			'click .statusfeed_post_comment_button': 'postCommentClicked'
		},
		commentClicked: function(e){
			//alert(this.);
			//var id = $(e.currentTarget).attr('data-id'); 
			$(e.currentTarget).parents('.statusfeed_wrapper').addClass('myclass');
			var id = $(e.currentTarget).parents('.statusfeed_wrapper').attr('data-id');
			var comment_form = $(e.currentTarget).parents('.statusfeed_wrapper').find('.statusfeed_comment_form');
			if(comment_form.hasClass( "hidden" ))
				comment_form.removeClass('hidden');
			else
				comment_form.addClass('hidden');
				
			e.preventDefault();
			//$(this).parents('.statusfeed_wrapper').addClass('myclass');
		},
		likeClicked: function(e){
			var id = $(e.currentTarget).parents('.statusfeed_wrapper').attr('data-id');
			e.preventDefault();
			//$(this).parents('.statusfeed_wrapper').addClass('myclass');
		},
		postCommentClicked: function(e) {
			var id = $(e.currentTarget).parents('.statusfeed_wrapper').attr('data-id');
			var comment_text_field = $(e.currentTarget).parents('.statusfeed_wrapper').find('.statusfeed_comment_text');
			var comment_text =comment_text_field.val();
			var comment_list = $(e.currentTarget).parents('.statusfeed_wrapper').find('.statusfeed_comments_list');
			console.log(comment_text);
			if(comment_text.length !== 0){
				$.ajax({
					  type: "POST",
					  url: "/api/v1/statuscomment.json",
					  data: { status_id: id, comment_text: comment_text }
					})
				.done(function( json ) {
					    console.log(json);
					    if(json.success == true) {
					    	comment_text_field.val('');
					    	json.username = MyHome.username;
					    	json.profile_pic_url = MyHome.username_img_url;
					    	//comment_list.append('<div class="statusfeed_comments_unit">'+json.status_comm_text+'</div>');
					    	var template = $('#statusfeedCommentUnit-template').html();
						    var html = Mustache.to_html(template, json);
						    comment_list.append(html);
						    $("abbr.timeago").timeago();
					    }
				});
			}
			
		},
    	//msg_user:        function() { return this.model.get('msg_thread_username'); },
		username: 			function() { return this.model.get('status_username'); },
		profile_pic_url: 	function() { return this.model.get('profile_pic_url'); },
		statusfeed_img: 	function() { return this.model.get('status_img');},
		likes_num: 			function() { return this.model.get('likes_num');},
		comments_num: 		function() { return this.model.get('comments_num');},
		comments: 			function() { return this.model.get('comments'); },
		statusfeed_content: function() { return this.model.get('status_text'); },
		statusfeed_id: 		function() { return this.model.get('status_id'); },
		statusfeed_form_visible: 		function() { return this.model.get('comments_num') > 0 ? "" : "hidden"; }
	});
	
	MyHome.Userfeeds.Form = Backbone.View.extend({
	    tagName: 'form',
	    className: 'form-horizontal messageThreadForm',
	    template: template('statusForm'),
	    events: {
	      'submit': 'add'
	    },
	    render: function() {
	     this.$el.html(this.template(this));
	     return this;
	    },
	    add: function(event) {
	      event.preventDefault();
			$('#status_text').val($.trim($('#status_text').val())); //trim it
			if( this.$('#status_text').val().length != 0 ) { //if the value has something
				this.collection.create({
			       status_text: this.$('#status_text').val(),
			       //to_username: MyHome.thread_username
				});
				this.render();
			}
	    }
	});
	
	MyHome.nl2br = function (str, is_xhtml) {   
    	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
    	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
	};
	
	return MyHome;
})(MyHome || {} );


/*
  
var Router = Backbone.Router.extend({
	routes:{
		'':'home'
	}
});

var myrouter = new Router();
myrouter.on('route:home',function(){
	console.log('my router home');
});
*/