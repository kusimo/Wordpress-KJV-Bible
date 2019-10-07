jQuery(document).ready(function($){
    if($( ".bibeli-chapter" ).length) {
        //credit : https://codepen.io/brenden/pen/Kwbpyj
        $( ".bibeli-chapter" ).wrapAll( "<div class='bibeli-section' />");
        let $current = $(".bibeli-section .bibeli-inner").slice(0, 1);
        $($current).css("display", "block");
        $($current).addClass('show');
        let $currentLink = $(".bibeli-toggle").slice(0, 1);
        $currentLink.addClass('active')

            $('.bibeli-toggle').click(function(e) {
                e.preventDefault();
                $(".bibeli-toggle").removeClass('active');

                var $this = $(this);


            if ($this.next().hasClass('show')) {
                $this.next().removeClass('show');
                $this.next().slideUp(350);
                $this.removeClass('active')
                
            } else {
                $this.parent().parent().find('.bibeli-inner').removeClass('show');
                $this.parent().parent().find('.bibeli-inner').slideUp(350);
                $this.next().toggleClass('show');
                $this.toggleClass('active');
                $this.next().slideToggle(350);
                
            }
        });  
    }

}) //end jQuery

