(function(window, $){
    var teaPaginate = function(elem, options){
	this.elem = elem;
        this.$elem = $(elem);
        this.options = options;
    };
    var content;
    teaPaginate.prototype = {
        defaults: {
            type: 'POST',
            url: '',
            data:{page:1},
            textLoading: 'Loading...',
            buttonsContainer: '',
            contentType: "charset=utf-8", 
            OnBefore : '',
            OnLoad: ''
        },
        _ContainerBtn: function(content){
            if(this.defaults.buttonsContainer !== ''){
                var typeContainer;
                if($('.'+this.defaults.buttonsContainer).length > 0)
                    typeContainer = '.';
                else
                    typeContainer = '#';
                if(content !== null)$(typeContainer+this.defaults.buttonsContainer).html(content);
                else return $(typeContainer+this.defaults.buttonsContainer);
            }
            return false;
        },
        init: function() {
            this.defaults = $.extend({}, this.defaults, this.options);
            var setting = this.defaults;
            var page;
            var buttonsElement = false;
            var idElem = this.$elem.attr('id');
            this.$elem.html(setting.textLoading);
            var elemAction = this.$elem;
            if(this._ContainerBtn(null) !== false){
                elemAction = this._ContainerBtn(null);
                buttonsElement = elemAction;
            }
            $.ajax({type:setting.type,dataType:'json',url:setting.url,data:setting.data}).done(function(data){$('#'+idElem).html(data.list); if(buttonsElement)buttonsElement.html(data.buttons)});
            elemAction.live("click", function(e) {
                if($(e.target).is("a")){
                    var type = $(e.target).attr('class');
                    switch(type){
                        case '_first':
                            setting.data = {
                                page:1
                            };
                            page = 1;
                            $.ajax({type:setting.type,dataType:'json',url:setting.url,data:setting.data,beforeSend:setting.OnBefore,success:setting.OnLoad});
                            break;
                        case '_next':
                            page = $(e.target).attr('rel');
                            page = page.replace("_", "");
                            setting.data = {
                                page:page
                            };
                            $.ajax({type:setting.type,dataType:'json',url:setting.url,data:setting.data,beforeSend:setting.OnBefore,success:setting.OnLoad});
                            break;
                        case '_last':
                            page = $(e.target).attr('rel');
                            page = page.replace("_", "");
                            setting.data = {
                                page:page
                            };
                            $.ajax({type:setting.type,dataType:'json',url:setting.url,data:setting.data,beforeSend:setting.OnBefore,success:setting.OnLoad});
                            break;
                        case '_prev':
                            page = $(e.target).attr('rel');
                            page = page.replace("_", "");
                            setting.data = {
                                page:page
                            };
                            $.ajax({type:setting.type,dataType:'json',url:setting.url,data:setting.data,beforeSend:setting.OnBefore,success:setting.OnLoad});
                            break;
                        case '_page':
                            page = $(e.target).attr('rel');
                            page = page.replace("_", "");
                            setting.data = {
                                page:page
                            };
                            $.ajax({type:setting.type,dataType:'json',url:setting.url,data:setting.data,beforeSend:setting.OnBefore,success:setting.OnLoad});
                            break;
                        default:
                            return true;
                    }
                }
                return false;
            });
            return this;
        },
        update: function(options) {
            var setting = $.extend({}, this.defaults, options);
            this.defaults.data.page = options.data.page;
            $.ajax({type:setting.type,dataType:'json',url:setting.url,data:setting.data,beforeSend:setting.OnBefore,success:setting.OnLoad});
        },
        currentPage: function(){
            return this.defaults.data.page;
        }
    };

    $.fn.plugin = function() {
        return this.each(function() {
            new teaPaginate(this).init();
        });
    };

    window.teaPaginate = teaPaginate;
})(window, jQuery);
