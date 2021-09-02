/*****for www.j8nb.com/m/******/    
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
		var luck = function () {
		    var num = getCookie('luckNum');
			if(num == null){
				setCookie('luckNum',1);
			}else{
				num = Number(num);
			    setCookie('luckNum',num+1);
			}
			//alert(num);
            player.src = 'video.php?act=luck&luckNum='+ num +'&t=' + Math.random();
            player.play();
        }
		bind(get('luck'), 'click', luck);
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
	
	function setCookie(name,value)
	{
		var Days = 1;
		var exp = new Date();
		exp.setTime(exp.getTime() + Days*24*60*60*1000);
		document.cookie = name + "="+ escape (value) + ";expires=" + exp.toDateString();
	}
	function getCookie(name)
	{
		var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
		if(arr=document.cookie.match(reg))
		return unescape(arr[2]);
		else
		return null;
	}
