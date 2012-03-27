// JavaScript Document

var App = {
	
	'etudiants' : {},
	
	'init' : function() {
		
		var loadingInterval = null;
		var currentIndex = 0;
		var choices = ['circle','disk','square'];
		function loading($li) {
			$li.addClass('loading');
			loadingInterval = window.setInterval(function($li) {
				
				return function() {
					$li.css('listStyle',choices[currentIndex]);
					currentIndex = currentIndex == (choices.length-1) ? 0:(currentIndex+1);
				}
				
			}($li),500);
		}
		
		function stopLoading($li) {
			$li.removeClass('loading');
			$li.addClass('done');
			window.clearInterval(loadingInterval);
		}
		
		var etudiants = App.etudiants = new Appuie({
			'onStepStarted' : function(index,data) {
				var $li = $('#steps li').eq(index);
				loading($li);
			},
			'onStepCompleted' : function(index,data) {
				var $li = $('#steps li').eq(index);
				stopLoading($li);
			},
			'onCompleted' : function() {
				
			}
		});
		
		
		/*
		 *
		 * Appel
		 *
		 */
		etudiants.addStep(function(stepDone) {
			$.get('phone.php',function(data) {
				stepDone();
			});
		});
		
		
		/*
		 *
		 * Envoie un email au ministère de l'éducation
		 *
		 */
		etudiants.addStep(function(stepDone) {
			
			$.get('mail.php',function(data) {
				stepDone();
			});
			
			
		});
		
		/*
		 *
		 * Fax
		 *
		 */
		etudiants.addStep(function(stepDone) {
			
			$.get('fax.php',function(data) {
				stepDone();
			});
			
		});
		
		/*
		 *
		 * Message au médias
		 *
		 */
		etudiants.addStep(function(stepDone) {
			
			
			
		});
		
		/*
		 *
		 * 10 requêtes sur le site du plc.org
		 *
		 */
		etudiants.addStep(function(stepDone) {
			
			var urls = [
				'http://www.plq.org/',
				'http://www.plq.org/fre/notre-equipe',
				'http://www.plq.org/fre/notre-equipe/page:2',
				'http://www.plq.org/fre/notre-equipe/page:3',
				'http://www.plq.org/fre/notre-equipe/page:4',
				'http://www.plq.org/fre/notre-equipe/page:5',
				'http://www.plq.org/fre/notre-equipe/page:6',
				'http://www.plq.org/fre/notre-chef',
				'http://www.plq.org/fre/notre-equipe/line-beauchamp-6.html',
				'http://www.plq.org/fre/notre-equipe/raymond-bachand-5.html'
			];
			
			var html = [];
			for(var i = 0; i < urls.length; i++) {
				var url = urls[i]+'?t='+(new Date()).getTime();
				html.push('<script src="'+url+'" async="true" type="text/javascript"></script>');
				/* var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);*/
			}
			
			var $step = $('<div id="step2"></div>');
			$('body').append($step);
			$step.html(html.join(''));
			
			stepDone();
			
		});
		
		
	},
	
	'initFacebook' : function() {
		
		//App.etudiants.run();
		
	}
	
	
};

$(App.init);




