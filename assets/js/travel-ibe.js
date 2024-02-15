jQuery(function ($) {
  const ibe = $('#pwr_ibe_iframe')

  $(window).on('message', function (event) {
    const message = event.originalEvent.data

    if (message.event === 'iberesized') {
      ibe.css('height', message.height + 'px')
    } else if (message.event === 'scroll') {
      const posIbe = ibe.offset().top
      const newPos = posIbe + $(window).scrollTop() + message.position
      $('html, body').animate({
        scrollTop: newPos,
      }, message.behavior === 'smooth' ? 600 : 0)
    }
  })

  $(window).on('scroll', function () {
    ibe[0].contentWindow.postMessage({
      event: 'parent-scroll',
      position: $(document).scrollTop(),
    }, '*')
  })
})
