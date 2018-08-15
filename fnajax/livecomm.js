/* require jQuery */
var livecomm = (function () {
    //a channel
    var YAJRPCchannel_alwayson = function (_endpoint, options) {
        var _this = this;

        var _console = {//debug purpose
            log: function (msg) {
            },
            error: function (msg) {
            },
            warn: function (msg) {
            },
            dir: function (msg) {
            },
            set_status: function (txt) {
            }
        };
        var _always_on = true;
        var _auto_check_interval_id = null;
        var _sync_interval = 4000; //ms

        var _content_subscription_req_handlers = {};
        var _content_resp_callbacks = {
            __KEEPALIVE__: function () {
            }
        };
        this.response_code_callbacks = {
            302: function () {
            } //set your action for 302 ie. usually meaning login required
        };

        var _json_has_error = function (json) {
            try {
                JSON.parse(json);
            } catch (e) {
                return true;
            }
            return false;
        };

        //requester and responser-parser as per our protocol
        var protocol_single_request_builder = function (req_content_name, req_content_param) {
            return {
                name: req_content_name,
                param: req_content_param
            };
        };

        var _handle_response = function (content_name, data, content_collection, error) {
            var _trigger_resp_callback = function (name, data, error) {
                var callback = _content_resp_callbacks[name];
                if (!$.isFunction(callback)) {
                    _console.warn("not a function: cannot trigger callbacks for `" + name + "`");
                    return;
                }
                //if (name=='set_remote_tracking_code') _console.log('trigger');
                callback(data, error);
            };

            _trigger_resp_callback(content_name, data, error);

            if (!$.isEmptyObject(content_collection)) {
                $.each(content_collection, function (i, content) {
                    _trigger_resp_callback(content.name, content.data, error);
                });
            }
        };
        /**
         *
         * @param method request method
         * @param url the endpoint
         * @param req_content_name
         * @param req_content_param the data you supply
         * @param req_array more requests, ie. an array of {req_content_name:req_content_param}
         * @param {function} receiver the response/error handler, it is supplied with name, data, collection, error
         */
        var protocolAjax = function (method, url, req_content_name, req_content_param, req_array, receiver) {

            if (!url) {
                _console.error("fail to perform request: target empty");
                return;
            }

            //content name cannot be empty
            if (!req_content_name) {
                _console.error("cannot make request: you must provide a request content name");
                return;
            }

            if (!$.isFunction(receiver)) {
                _console.error('livecomm protocolajax receiver not a function');
                return;
            }

            var collection_protocol_request_builder = function (req_content_name, req_content_param, req_collection) {
                return {
                    name: req_content_name,
                    param: req_content_param,
                    collection: req_collection //its an array of { name: <...>, param: <...> }
                };
            };

            $.ajax({
                type: method,
                url: url,
                data: collection_protocol_request_builder(req_content_name, req_content_param, req_array),
                dataType: 'text', //will be parsed as json

                statusCode: _this.response_code_callbacks,

                success: function (resp_json) {
                    var error = '';

                    if (_json_has_error(resp_json)) {
                        //try to capture the json from the soup of texts
                        var m = /\{.+\}/g.exec(resp_json);

                        if (m) { //found some json
                            error = resp_json.replace(m[0], '');
                            alert(error);
                            _console.error(error);
                            resp_json = m[0];
                        } else { //no json, its all error texts
                            //no need to proceed as we wont be able to get even the response name
                            error = "Error: malformed response json. " + resp_json;
                            alert(error);
                            _console.error(error);
                            return;
                        }
                    }

                    var event = JSON.parse(resp_json);

                    /** name is the name you used in subscribe or emit */
                    var name = event.hasOwnProperty('name') ? event.name : null;
                    var data = event.hasOwnProperty('data') ? event.data : null;
                    var collection = event.hasOwnProperty('collection') ? event.collection : null;

                    if (!name && !collection) {
                        error = 'unnamed response';
                    }

                    if (name) {
                        receiver(name, data, collection, error);
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var error = 'AjaxError:' + textStatus + ':' + errorThrown;
                    _console.error(error);
                    receiver(false, false, false, error);
                }
            });
        };

        this.set_opt = function (options) {
            if (options && options.hasOwnProperty('always_on')) {
                _always_on = options.always_on;
            }
            if (options && options.hasOwnProperty('console')) {
                _console = options.console;
            }
            if (options && options.hasOwnProperty('sync_interval')) {
                _sync_interval = options.sync_interval;
            }
        };

        this.set_console = function (console) {
            _console = console;
        };

        //listen for name, if got do callback, remember that the callback will be called with data and error ie. callback(data, error)
        this.listen = function (name, callback) { //set a listener
            _content_resp_callbacks[name] = callback;
            return this;
        };

        this.request = function (content_name, params, callback) {
            protocolAjax('post', _endpoint, content_name, params, undefined, function (name, data, collection, error) {
                if (collection) {
                    _console.warn('response collection shouldnt be returned for standalone requests');
                }
                callback(data, error);
            });
        };
        this.get = function (content_name, params, callback) {
            protocolAjax('get', _endpoint, content_name, params, undefined, function (name, data, collection, error) {
                if (collection) {
                    _console.warn('response collection shouldnt be returned for standalone requests');
                }
                callback(data, error);
            });
        };
        this.post = function (content_name, params, callback) {
            protocolAjax('post', _endpoint, content_name, params, undefined, function (name, data, collection, error) {
                if (collection) {
                    _console.warn('response collection shouldnt be returned for standalone requests');
                }
                callback(data, error);
            });
        };

        /**
         * create ondemand channel for your service request
         * @param {string} content_name
         * @param {object|string} param whatever data you want to send||parameters
         */
        this.emit = function (content_name, param) {
            _console.log("emit `" + content_name + "` with param " + (param ? param.toString() : param));
            if (!_content_resp_callbacks[content_name]) {
                _console.error("cannot emit `" + content_name + "`! set listener before then emit");
                return;
            }
            protocolAjax('post', _endpoint, content_name, param, undefined, _handle_response);
        };

        //get the subscription manager for a specific service || subscribe to the always on
        this.subscribe = function (content_name) {
            var subscriptionManager = function (content_name) {
                this.set_request_filter = function (criteria_builder_method) {
                    _content_subscription_req_handlers[content_name] = criteria_builder_method;
                    return this;
                };
                this.deliver_this_way = function (deliver_method) {
                    if (!deliver_method) {
                        delete _content_subscription_req_handlers[content_name];//clean if any entry
                        _console.error("cannot subscribe to `" + content_name + "`: set delivery function(eg. how can I transfer the content to you?)");
                        return;
                    }
                    _this.listen(content_name, deliver_method);
                };
            };
            if (!content_name) {
                _console.error('cannot subscribe: content name needed');
                return;
            }
            return new subscriptionManager(content_name);
        };

        this.stop_auto = function () {
            clearInterval(_auto_check_interval_id);
        };

        var _init = function () {
            _this.set_opt(options);

            if (!_endpoint) {
                _console.error("always on channel init fail! no endpoint given");
                return;
            }
            _auto_check_interval_id = setInterval(function () {
                if (!_always_on) {
                    _console.set_status('sync stopped(always_on false)!');
                    return false;
                } else {
                    //build the subscription request collection
                    var collection = [];
                    $.each(_content_subscription_req_handlers, function (content_name, query_generator) {
                        var param;
                        if ($.isFunction(query_generator)) {
                            param = query_generator();
                        } else {
                            param = query_generator;
                        }
                        //_display.log("building query for "+content_name);
                        collection.push(protocol_single_request_builder(content_name, param));
                    });
                    _console.set_status('sync running with ' + collection.length + ' subscriptions ...');
                    protocolAjax('get', _endpoint, '__KEEPALIVE__', null, collection, _handle_response);
                }
            }, _sync_interval);
        };

        /** now init the channel*/
        _init();
    };

    /* you override the methods */
    function ReactiveElement(selector, channel) {
        this.selector = selector;
        this.channel = channel;
        this.getState = function () {
        };
        this.push = function () {
        };
        this.subscribe = function () {
        };
    }

    return {
        connect: function (endpoint, options) {
            return new YAJRPCchannel_alwayson(endpoint, options || {});
        },
        element: {
            initFromSelector: function (selector, channel, init_func) {
                var element = new ReactiveElement(selector, channel);
                init_func.bind(element)();
                element.subscribe();
                return element;
            }
        }
    };
}());