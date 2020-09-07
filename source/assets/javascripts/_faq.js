$('.toggle').click(function(e) {
    e.preventDefault();

    var $this = $(this);

    if ($this.next().hasClass('show')) {
        $this.parent().find('.toggle').removeClass('active');
        $this.next().removeClass('show');
        $this.next().slideUp(350);
    } else {
        $this.parent().find('.toggle').toggleClass('active');
        $this.next().toggleClass('show');
        $this.next().slideToggle(350);
    }
});
