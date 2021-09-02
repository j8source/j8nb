/*****j8nb.com/m/******/    
	(function (window, document) {
        if (top != self) {
            window.top.location.replace(self.location.href);
        }
        var get = function (id) {
            return document.getElementById(id);
        }
        var bind = function (element, event, callback) {
            return element.addEventListener(event, callback);
        }
        var auto = true;
        var player = get('player');
        var randomm = function () {
            player.src = 'video.php?t=' + Math.random();
            player.play();
        }
        bind(get('next'), 'click', randomm);
        bind(player, 'error', function () {
            randomm();
        });
        bind(get('switch'), 'click', function () {
            auto = !auto;
            this.innerText = '连续: ' + (auto ? '开' : '关');
        });
        bind(player, 'ended', function () {
            if (auto) randomm();
        });
    })(window, document);
	
	
