(function() {
  var MyHome = {};
  window.MyHome = MyHome;
  

  var template = function(name) {
    return Mustache.compile($('#'+name+'-template').html());
  };
  
   /* 
   MyHome.Message = Backbone.Model.extend({
  });

  MyHome.Messages = Backbone.Collection.extend({
    localStorage: new Store("messages")
  });
  */
  MyHome.Index = Backbone.View.extend({
    template: template('index'),
    initialize: function() {
      //this.messages = new MyHome.Messages();
      //this.messages.on('all', this.render, this);
      //this.messages.fetch();
      console.log('initialized view');
    }
  });


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
      //this.el.append(view.render().el);
      console.log('index function done');
    }
  });


  MyHome.boot = function(container) {
    container = $(container);
    var router = new MyHome.Router({el: container})
    Backbone.history.start();
  }
  
  /*
		Member = Backbone.Model.extend({

            defaults : {
                id : "noid",
                image : "",
                username : "",
                country : "united states"
            },

            initialize : function Member() {
                console.log('User Constructor');
            }
        });
*/

})()