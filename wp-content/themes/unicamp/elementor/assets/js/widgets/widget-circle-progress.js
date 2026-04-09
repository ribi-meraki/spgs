(
  function ($) {
	'use strict'

	var CircleProgressChartHandler = function ($scope, $) {
	  const $element = $scope.find('.tm-circle-progress-chart')

	  const observer = new IntersectionObserver((entries, _observer) => {
		entries.forEach((entry) => {
		  if (entry.isIntersecting) {
			const countHtml = $element.find('.chart-number')

			const chart = $element.find('.chart').circleProgress({
			  startAngle: -Math.PI / 4 * 2,
			  animation: { duration: 1700 }
			})

			if ($element.data('use-number') == '1') {
			  chart.on('circle-animation-progress', function (event, progress) {
				countHtml.html(parseInt((
				  countHtml.data('max')
				) * progress) + '<span>' + countHtml.data('units') + '</span>')
			  })
			}
		  }
		})
	  }, { rootMargin: '0px 0px 0px 0px' })

	  observer.observe($element.get(0))
	}

	$(window).on('elementor/frontend/init', function () {
	  elementorFrontend.hooks.addAction('frontend/element_ready/tm-circle-progress-chart.default', CircleProgressChartHandler)
	})
  }
)(jQuery)
