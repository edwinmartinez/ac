
(function() {
  var MyHome = {};
  window.MyHome = MyHome;
	
  var template = function(name) {
    return Mustache.compile($('#'+name+'-template').html());
  };

  // Model ------------------------------
  MyHome.Message = Backbone.Model.extend({ });
  MyHome.ThreadMessage = Backbone.Model.extend({ });
  
  // Collection -------------------------------------------------------
  MyHome.Messages = Backbone.Collection.extend({
  	initialize: function(){
  		console.log('init messages collection');
  	},
  	model:MyHome.Message,
  	url:'/index.php/api/v1/messages.json'
  });

  MyHome.ThreadMessages = Backbone.Collection.extend({
  	initialize: function(){
  		console.log('init messagesThread collection thread_username:'+MyHome.thread_username);
  		this.url = '/index.php/api/v1/messagethread.json?thread_username='+MyHome.thread_username;
  		
  	},
  	model:MyHome.ThreadMessage
  });

  // View --------------------------------------------------------------
MyHome.ThreadMessagesIndex = Backbone.View.extend({
  	tagName: 'div',
  	className: 'messagesThreadWrapper',
    template: template('messagesThread'),
    initialize: function() {
     this.messages = new MyHome.ThreadMessages();
      //this.messages.on('reset', this.render, this);
      this.messages.on('reset',   this.render, this);
      this.messages.fetch();
		
		this.paneSettings = {
					showArrows: false
				};
		this.pane = $('#threadMessages');
		//this.pane.jScrollPane(this.paneSettings);
		this.paneApi = this.pane.jScrollPane(this.paneSettings).data('jsp');
		//this.paneApi.reinitialise();
		//$('#threadMessages').jScrollPane();
		
      console.log('init threadMessagesIndex');
      //console.log(this);
    },
    render: function() {
		this.$el.html(this.template(this));
		this.messages.each(this.addMessage, this);
		//var form = new MyHome.ThreadMessages.Form({collection: this.messages});
		//this.$el.append(form.render().el);
		
		var element = $('#threadMessages').jScrollPane(this.paneSettings);
		var api = element.data('jsp');
		
		console.log('paneApi',element);
		
		//$('#threadMessages').jScrollPane(this.paneSettings).data('jsp').scrollToBottom();
		//element.data('jsp').scrollToBottom();
		//this.paneApi.reinitialise();
		
		
		return this;
    },
    refetch: function() {
    	this.messages.fetch({remove: false});
    	console.log('refecthing');
    },
    addMessage: function(message) {
		var view = new MyHome.ThreadMessage({model: message});
		this.$('#threadMessageList').append(view.render().el);
		console.log('added message to thread');
		//var api = element.data('jsp');
		$('div.messageItem').click(function(){
			document.location.href = $(this).attr('rel');
		});
      //$('#messagesHeader-title').append(view.render().el);
    },
    count: function() {
      return this.messages.length;
    }
  });
 
   
  MyHome.ThreadMessage = Backbone.View.extend({
  	tagName: 'li',
    template: template('messageThreadItem'),
    render: function() {
      this.$el.html(this.template(this));
      return this;
    },
    //msg_user:        function() { return this.model.get('msg_thread_username'); },
    //msg_user_img_url: function() { return this.model.get('msg_thread_username_img_url'); },
    id: function() { return this.model.get('msg_id'); },
    msg_text: function() { return this.model.get('msg_text'); },
    //msg_type: function() { return this.model.get('msg_type'); },
   	//msg_time: function() { return $.timeago(this.model.get('msg_date')); },
   	msg_time: function() { return this.model.get('msg_date'); },
   	from_username_img_url: function() { return this.model.get('from_username_img_url'); },
   	from_username: function() { return this.model.get('from_username'); },
   	to_username: function() { return this.model.get('to_username'); }
  }); 
  

  MyHome.ThreadMessages.Form = Backbone.View.extend({
    tagName: 'form',
    className: 'form-horizontal messageThreadForm',
    template: template('messageThreadForm'),
    events: {
      'submit': 'add'
    },
    render: function() {
     this.$el.html(this.template(this));
     console.log('form render');
     return this;
    },
    add: function(event) {
      event.preventDefault();
		$('#msg_reply_text').val($.trim($('#msg_reply_text').val())); //trim it
		if( this.$('#msg_reply_text').val().length != 0 ) { //if the value has something
			this.collection.create({
		       msg_text: this.$('#msg_reply_text').val(),
		        to_username: MyHome.thread_username
			});
			this.render();
		}
    }
  });


  MyHome.SidebarMessages = Backbone.View.extend({
  	tagName: 'div',
  	//className:'dropdown',
  	//idName:'msgDropdown',
    template: template('messagesSidebar'),
    initialize: function() {
      this.messages = new MyHome.Messages();
      this.messages.on('reset', this.render, this);
      this.messages.fetch();
      console.log('init sidebarMessages');
    },
    render: function() {
      this.$el.html(this.template(this));
      this.messages.each(this.addMessage, this);
      
      console.log(this);
      return this;
    },
    addMessage: function(message) {
		var view = new MyHome.IndexMessage({model: message});
		this.$('#sidebarMessageList').append(view.render().el);
		console.log('added message to sidebar');
		$('div.messageItem').click(function(){
			document.location.href = $(this).attr('rel');
		});
		
      //$('#messagesHeader-title').append(view.render().el);
    },
    count: function() {
      return this.messages.length;
    }
  });
  
  
  MyHome.IndexMessages = Backbone.View.extend({
  	tagName: 'li',
  	className:'dropdown',
  	idName:'msgDropdown',
    template: template('messagesHeader'),
    initialize: function() {
      this.messages = new MyHome.Messages();
      this.messages.on('reset', this.render, this);
      this.messages.fetch();
      //console.log(this);
      console.log('init indexMessages');
    },
    render: function() {
      this.$el.html(this.template(this));
      this.messages.each(this.addMessage, this);
      return this;
    },
    addMessage: function(message) {
      var view = new MyHome.IndexMessage({model: message});
      this.$('.dropdown-messages').append(view.render().el);
      $('div.messageItem').click(function(){
			document.location.href = $(this).attr('rel');
		});
      //$('#messagesHeader-title').append(view.render().el);
    },
    count: function() {
      return this.messages.length;
    }
  });
  
  MyHome.IndexMessage = Backbone.View.extend({
  	tagName: 'li',
    template: template('messageItem'),
    render: function() {
      this.$el.html(this.template(this));
      return this;
    },
    //msg_user:        function() { return this.model.get('msg_thread_username'); },
    msg_user_img_url: function() { return this.model.get('msg_thread_username_img_url'); },
    msg_text: function() { return this.model.get('msg_text'); },
    msg_type: function() { return this.model.get('msg_type'); },
   	msg_time: function() { return $.timeago(this.model.get('msg_date')); },
   	msg_thread_username: function() { return this.model.get('msg_thread_username'); }
  });


	MyHome.SelectedThread = function(){
		var threadView = new MyHome.ThreadMessagesIndex();
        	$('#main-content').empty();
        	$('#main-content').append(threadView.render().el);
        	var form = new MyHome.ThreadMessages.Form({collection: threadView.messages});
      //this.$el.append(form.render().el);
      	//template: template('messageItem'),
        	$('#messagesRespond').html(form.render().el);
        	return this;
        	/*setInterval(function(){
        		//$('#main-content').empty();
        		//$('#main-content').html(threadView.render().el);
        		//MyHome.ThreadMessages().fetch();
        		threadView.refetch();
        		},5000);*/
	}

  // Router --------------------------------
  MyHome.Router = Backbone.Router.extend({
    initialize: function(options) {
      this.el = options.el
    },
    routes: {
      "": "index"
    },
    index: function() {
      var view = new MyHome.IndexMessages();
      this.el.empty();
      this.el.append(view.render().el);
      //does the messageList (the side bar for messages) exists then we are in the /messages area
      // so we should do something
      if($('#messageList').length) {
      	var sidebarView = new MyHome.SidebarMessages();
      	$('#messageList').empty();
      	$('#messageList').append(sidebarView.render().el);
        if(MyHome.thread_username.length) { //we are looking at a selected thread
        	MyHome.SelectedThread();	
        }
        
      }
    }
  });

//recipes
  /*  
  MyHome.Recipe = Backbone.Model.extend({
  });


  MyHome.Recipes = Backbone.Collection.extend({
    localStorage: new Store("recipes")
  });
  

  MyHome.Index = Backbone.View.extend({
    template: template('index'),
    initialize: function() {
      this.recipes = new MyHome.Recipes();
      this.recipes.on('all', this.render, this);
      this.recipes.fetch();
      //this.fetch({ success: this.fetchSuccess, error: this.fetchError });
      console.log('init index');
    },
    render: function() {
      this.$el.html(this.template(this));
      return this;
    },
    count: function() {
      return this.recipes.length;
    }
  });


   * To do:
   *
   * * MyHome.Index.Form
   *   A view that renders a form which can be submitted
   *   to create a new recipe
   * * MyHome.Index should add a subview for each
   *   recipe in the database
   * * MyHome.Recipe
   *   A view that renders an individual recipe
   *   Also, a delete button to remove it


  MyHome.Router = Backbone.Router.extend({
    initialize: function(options) {
      this.el = options.el
    },
    routes: {
      "": "index"
    },
    index: function() {
      var view = new MyHome.Index();
      this.el.empty();
      this.el.append(view.render().el);
    }
  });
   */
  MyHome.boot = function(container) {
    container = $(container);
    var router = new MyHome.Router({el: container})
    Backbone.history.start();
  }


})()
