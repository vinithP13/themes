(function ($, Drupal) {
  Drupal.behaviors.customBehavior = {
    attach: function (context, settings) {

      // Hide and show paragraphs
      $("p", context).once('customBehavior').each(function () {
        $(this).hide();
        $(this).show(3000);
      });

      // Slide toggle figures
      $(".figure", context).once('customBehavior').each(function () {
        $(this).slideToggle(1000);
      });

      // Slide down figures
      $(".figure", context).once('customBehaviorSlideDown').each(function () {
        $(this).slideDown(1000);
      });

      // Slide up and down bottles
      $(".bottle", context).once('customBehaviorSlide').each(function () {
        $(this).slideUp(1000).slideDown(1000);
      });

      // Alert on button click
      $(".button1", context).once('customBehaviorButton').each(function () {
        $(this).click(function () {
          alert("Hello\nHow are you?");
        });
      });

      // Hide spoon on click
      $(".spoon", context).once('customBehaviorSpoon').each(function () {
        $(this).click(function () {
          $(this).hide();
        });
      });

    }
  };
})(jQuery, Drupal);
