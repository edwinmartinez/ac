
(function() {
  var MyHome = {};
  window.MyHome = MyHome;
	
  var template = function(name) {
    return Mustache.compile($('#'+name+'-template').html());
  };

  // Model ------------------------------
  MyHome.Message = Backbone.Model.extend({ });
  
  // Collection -------------------------------
  MyHome.Messages = Backbone.Collection.extend({
  	initialize: function(){
  		console.log('init messages collection');
  	},
  	model:MyHome.Message,
  	url:'/index.php/api/v1/messages.json'
  	//,localStorage: new Store("messages")
  });

  // View ----------------------------------------
  
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
    msg_user:        function() { return this.model.get('msg_thread_username'); },
    msg_user_img_url: function() { return this.model.get('msg_thread_username_img_url'); },
    msg_text: function() { return this.model.get('msg_text'); },
    msg_type: function() { return this.model.get('msg_type'); },
   msg_date: function() { return this.model.get('msg_date'); }
  });

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
